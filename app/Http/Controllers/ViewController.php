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
    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }
    private function loadMovieInfo($id)
    {
        $fileName = 'movie/'.$id.'.json';
        $info = null;
        if ($this->storage->exists($fileName)) {
            $json = $this->storage->get($fileName);

            $info = json_decode($json, true);
        }
        if ($info && !isset($info['torrent'])) {
            $bot = new MovieBot();
            $torrent = $bot->loadTorrentInfo($info['url']);
            $info['torrent'] = $torrent;
            $this->storage->put($fileName, json_encode($info));
        }

        return $info;
    }
    private function systemAction($action, $param = null)
    {
        $ssh = new SSHClient(env('SERVER_HOST'), env('SERVER_PORT', 22), env('SERVER_USERNAME'), env('SERVER_PASSWORD'));

        if ($action == 'status') {
            if ($ssh->connect() && $ssh->authorize()) {
                $checks = ['minidlna', 'smbd', 'aria2c'];
                $result = [];
                foreach ($checks as $check) {
                    $r = $ssh->cmd('ps -aux | grep '.$check);
                    if (stripos($r, '/'.$check) === false) {
                        $result[$check] = false;
                    } else {
                        $result[$check] = true;
                    }
                }

                return ['service' => $result];
            } else {
                return;
            }
        }
    }
    private function movieAction($action, $param = null)
    {
        if ($action == 'list') {
            $bot = new MovieBot();
            $page = $param ? $param : 1;
            $cacheName = "movie_{$action}_$page";
            $result = Cache::get($cacheName);
            if (!$result) {
                $result = $bot->loadMovies($page);
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

            return $result;
        } elseif (is_numeric($action)) {
            $info = $this->loadMovieInfo($action);
            // dd($info);
            return $info;
        } else {
            return;
        }
    }
    public function showView(Request $request, $view = 'system', $action = 'status', $param = null)
    {
        $title = '';
        $file = \File::get(storage_path('config/menu.json'));
        $menus = json_decode($file, true);
        $data = [];
        if ($view == 'movie') {
            $data = $this->movieAction($action, $param);
            if (is_numeric($action)) {
                $action = 'detail';
            }
        } elseif ($view == 'system') {
            $data = $this->systemAction($action, $param);
        }

        return view("{$view}.{$action}", ['menus' => $menus, 'view' => $view, 'action' => $action, 'data' => $data, 'title' => $title, 'param' => $param]);
    }
}
