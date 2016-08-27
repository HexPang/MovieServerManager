@extends('body')
@section('header')
<style>
.ph span{
  color:#1ca8dd;
}
.red {
  color:red !important;
}
</style>
@endsection
@section('body')
    <div class="ali center">
        <div class="by">
            <h4 class="ty">
                服务状态
            </h4>
            @foreach($data['service'] as $service_name=>$service)
              <div class="ph">
                <span class="dy dh {{ $service ? '' : 'red' }}">{{ $service ? '运行中' : '未启动' }}</span>
                {{ $service_name }}
              </div>
            @endforeach
        </div>
    </div>
@endsection
