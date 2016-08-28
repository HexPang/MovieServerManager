@extends('body')
@section('header')
<style>
.ph span{
  color:#1ca8dd;
}
.red {
  color:red !important;
}
.dy a {
  color:#5add2d;
}
.stat {
  float:right;
  margin-right:10px;
  font-size:13px;
  line-height: 20px;
}
.stat-action {
  float:right;
  margin-right:10px;
  font-size:13px;
  line-height: 20px;
  color:red;
}
</style>
@endsection
@section('body')
    <div class="ali center">
        <div class="by">
            <h4 class="ty">
                任务状态
                <span class="stat">活跃任务:{{ $data['stat']['numActive'] }}</span>
                <span class="stat">下载:{{ $data['stat']['downloadSpeed'] }}/s</span>
                <span class="stat">上传:{{ $data['stat']['uploadSpeed'] }}/s</span>
            </h4>
            @foreach($data['downloading'] as $file)
              <div class="ph">
                <a href="/aria2/tasks/{{ $file['gid'] }}/remove" class="stat-action">删除</a>
                <span class="stat">
                  {{ $file['downloadSpeed'] }}/s
                </span>
                <span class="stat">进度:{{ $file['completedLength'] }}/{{ $file['totalLength'] }}</span>
                {{ $file['bittorrent']['info']['name'] }}
              </div>
            @endforeach
        </div>
    </div>
@endsection