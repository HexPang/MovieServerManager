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
</style>
@endsection
@section('body')
    <div class="ali center">
        <div class="by">
            <h4 class="ty">
                服务状态
            </h4>
            @if(!$data)
              @foreach($data['service'] as $service_name=>$service)
                <div class="ph">
                  <span class="dy dh {{ $service ? '' : 'red' }}">
                    @if($service)
                      <a href="/system/status/{{ $service_name }}/stop">停止</a>
                    @else
                      <a href="/system/status/{{ $service_name }}/start">启动</a>
                    @endif
                    {{ $service ? '运行中' : '未启动' }}
                  </span>
                  {{ $service_name }}
                </div>
              @endforeach
            @else
              <div class="ph">
                <span class="dy dh red">
                  无法连接至服务器.请检查配置文件.
                </span>
                错误
              </div>
            @endif
        </div>
    </div>
@endsection
