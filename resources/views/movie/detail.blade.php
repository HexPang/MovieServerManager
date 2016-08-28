@extends('body')
@section('header')
<style>
.movie-table {
}
.block {
  display:block;
}
</style>
@endsection
@section('body')
    <div class="ali center">
        <div class="by">
            <h4 class="ty">
                {{ $data['title'] }}
            </h4>

            <div class="ph">
              <img src="{{ $data['image'] }}">
            </div>
            <div class="ph movie-table">
              <span class="dh">类型</span>
              @foreach($data['type'] as $type){{ $type }}&nbsp;@endforeach
            </div>

            <div class="ph movie-table">
              <span class="dh">国家</span>
              @foreach($data['country'] as $country){{ $country }}&nbsp;@endforeach
            </div>

            <div class="ph movie-table">
              <span class="dh">演员</span>
              @foreach($data['actor'] as $actor){{ $actor }}&nbsp;@endforeach
            </div>

            <div class="ph movie-table">
              @foreach($data['torrent'] as $index=>$torrent)
                <a href="/movie/{{ $data['id'] }}/{{ $index }}" class="block">{{ $torrent['file_name'] }}</a>
              @endforeach
            </div>
            @if(isset($data['download']))
            <div class="ph movie-table">
              <span class="dh">提示</span>
              {{ $data['download'] }}
            </div>
          @endif
        </div>
    </div>
@endsection
