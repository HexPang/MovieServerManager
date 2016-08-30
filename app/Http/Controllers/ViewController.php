<?php

namespace App\Http\Controllers;

use Request;
use Cache;
use Storage;
use hexpang\moviebotb3t\MovieBot;
use hexpang\Client\SSHClient\SSHClient;

class ViewController extends Controller
{
    public $storage;
    public function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision).' '.$suffixes[floor($base)];
    }
    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }
    private function loadMovieInfo($id)
    {
        $fileName = 'movie/'.$id.'.json';
        $info = null;
        $bot = new MovieBot();

        if ($this->storage->exists($fileName)) {
            $json = $this->storage->get($fileName);
            $info = json_decode($json, true);
        } else {
            $info = $bot->loadMovieInfo($id);
            $this->storage->put($fileName, json_encode($info));
        }
        // && !isset($info['torrent'])
        if ($info) {
            $torrent = $bot->loadTorrentInfo($info['url']);
            $info['torrent'] = $torrent;
            $this->storage->put($fileName, json_encode($info));
        }

        return $info;
    }
    private function systemAction($action, $param = null, $param1 = null)
    {
        $ssh = new SSHClient(env('SERVER_HOST'), env('SERVER_SSH_PORT', 22), env('SERVER_USERNAME'), env('SERVER_PASSWORD'));

        if ($action == 'status') {
            if ($ssh->connect() && $ssh->authorize()) {
                $diskUses_cmd = 'df -h | grep /dev/sda';
                $disk = $ssh->cmd($diskUses_cmd);
                if ($disk) {
                    while (stripos($disk, '  ')) {
                        $disk = str_ireplace('  ', ' ', $disk);
                    }
                    $d = explode("\n", $disk);
                    $disk = [];
                    foreach ($d as $dd) {
                        if ($dd != '') {
                            $disk[] = explode(' ', $dd);
                        }
                    }
                    // dd($disk);
                }
                $checks = ['minidlna', 'smbd', 'aria2c'];
                if ($param && $param1) {
                    // dd($param);
                    //服务管理
                    $cmd = '';
                    if ($param == 'aria2c') {
                        if ($param1 == 'start') {
                            $cmd = 'aria2c -D --conf-path=/home/osmc/.aria2/aria2.conf';
                        } else {
                            $cmd = "ps -A |grep 'aria2c'| awk '{print $1}'";
                            $pid = $ssh->cmd($cmd);
                            $cmd = "kill {$pid}";
                            // $r = $ssh->cmd($cmd);
                        }
                    } else {
                        $cmd = 'sudo /etc/init.d/'.$param.' '.$param1;
                    }
                    $s = $ssh->cmd($cmd);
                    sleep(1);
                }
                $result = [];
                foreach ($checks as $check) {
                    $r = $ssh->cmd('ps -aux | grep '.$check);
                    $check_str = '/'.$check;
                    if ($check == 'aria2c') {
                        $check_str = 'conf-path';
                    }
                    if (stripos($r, $check_str) === false) {
                        $result[$check] = false;
                    } else {
                        $result[$check] = true;
                    }
                }
                $ssh->disconnect();

                return ['service' => $result, 'disks' => $disk];
            } else {
                return;
            }
        }
    }
    private function movieAction($action, $param = null, $param1 = null)
    {
        $param1 = $param1 ? $param1 : 0;
        if ($action == 'list') {
            $type = [
            '0' => '全部',
            '1' => '剧情',
            '7' => '喜剧',
            '18' => '动作',
            '27' => '惊悚',
            '2' => '爱情',
            '8' => '犯罪',
            '14' => '冒险',
            '10' => '恐怖',
            '20' => '科幻',
            '21' => '悬疑',
            '3' => '奇幻',
            '15' => '家庭',
            '13' => '动画',
            '22' => '纪录片',
            '49' => '战争',
            '29' => '历史',
            '63' => '传记',
            '23' => '音乐',
            '125' => '歌舞',
            '16' => '运动',
            '64' => '西部',
            '79' => '短片',
            '91' => '同性',
            '61' => '古装',
          ];
            $bot = new MovieBot();
            $page = $param ? $param : 1;
            $cacheName = "movie_{$action}_{$param1}_{$page}";
            $result = Cache::get($cacheName);
            $start_page = 1;
            if ($page > 3) {
                $start_page = $page - 3;
            } else {
                $start_page = 1;
            }
            if ($page > 3) {
                $end_page = $page + 3;
            } else {
                $end_page = $start_page + 5;
            }
            if (!$result) {
                $result = $bot->loadMovies($page, $param1);
                Cache::put($cacheName, $result, 240);
            }
            foreach ($result['movies'] as $k => $v) {
                $id = explode('/', $v['url']);
                $id = $id[count($id) - 1];
                $id = explode('.', $id);
                $id = $id[0];
                $result['movies'][$k]['id'] = $id;
                $fileName = 'movie/'.$id.'.json';
                if (!$this->storage->exists($fileName)) {
                    $this->storage->put($fileName, json_encode($result['movies'][$k]));
                }
            }
            $result['page'] = $page;
            // $result['movies'] = $result;
            $result['movie_type'] = $type;
            $result['type'] = $param1;
            $result['page_range'] = [$start_page, $end_page];
            // dd($result['page_range']);

            return $result;
        } elseif (is_numeric($action)) {
            $info = $this->loadMovieInfo($action);
            if (is_numeric($param)) {
                //下载
                $torrent = $info['torrent'][$param];
                // dd($torrent);
                $fileName = 'torrent/'.$action.'-'.$param.'.torrent';
                if (!$this->storage->exists($fileName)) {
                    $url = $torrent['url'];
                    $bot = new MovieBot();
                    $torrent = $bot->downloadTorrent($url);
                    $this->storage->put($fileName, $torrent);
                }
                $torrent = base64_encode($this->storage->get($fileName));
                $ac = new AriaController();
                // dd($ac->addTorrent($torrent));
                $r = $ac->addTorrent($torrent);
                if (isset($r['result'])) {
                    $info['download'] = '下载任务已添加.';
                } else {
                    if ($r == null) {
                        $info['download'] = '无法连接到服务器.';
                    } else {
                        $info['download'] = '下载任务添加失败.';
                    }
                }
            }

            return $info;
        } else {
            return;
        }
    }
    public function aria2Action($action, $param = null, $param1 = null)
    {
        $result = [];
        $ac = new AriaController();
        if ($action == 'tasks') {
            //任务查看
            if ($param && $param1) {
                $gid = $param;
                $action = $param1;
                $ac->$param1($gid);
            }
            $stat = $ac->getGlobalStat();
            if ($stat == null) {
                return;
            }
            if (isset($stat['error'])) {
                $result['error'] = $stat['error'];
            } else {
                $result['stat'] = $stat['result'];
                $result['stat']['downloadSpeed'] = $this->formatBytes($result['stat']['downloadSpeed']);
                $result['stat']['uploadSpeed'] = $this->formatBytes($result['stat']['uploadSpeed']);
                //uploadSpeed
            }
            $downloading = $ac->tellActive();

            $result['downloading'] = $downloading['result'];
            foreach ($result['downloading'] as $k => $v) {
                $result['downloading'][$k]['completedLength'] = $this->formatBytes($v['completedLength']);
                $result['downloading'][$k]['totalLength'] = $this->formatBytes($v['totalLength']);
                $result['downloading'][$k]['downloadSpeed'] = $this->formatBytes($v['downloadSpeed']);
                //$this->formatBytes($result['stat']['uploadSpeed'] * 8);
            }
            // dd($result);
        }

        return $result;
    }
    public function showView(Request $request, $view = 'system', $action = 'status', $param = null, $param1 = null)
    {
        $title = '';
        $file = \File::get(storage_path('config/menu.json'));
        $menus = json_decode($file, true);
        $data = [];
        if ($view == 'movie') {
            $data = $this->movieAction($action, $param, $param1);
            if (is_numeric($action)) {
                $action = 'detail';
            }
        } elseif ($view == 'system') {
            $data = $this->systemAction($action, $param, $param1);
        } elseif ($view == 'aria2') {
            $data = $this->aria2Action($action, $param, $param1);
        }

        return view("{$view}.{$action}", ['menus' => $menus, 'view' => $view, 'action' => $action, 'data' => $data, 'title' => $title, 'param' => $param, 'param1' => $param1]);
    }
}
