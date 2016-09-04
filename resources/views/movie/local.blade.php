@extends('body')

@section('body')
  <div class="ud">
    <div class="eg">
      <table class="cl" data-sort="table">
        <thead>
          <tr>
            <th class="header">文件名</th>
            <th class="header">大小</th>
            <th class="header">操作</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data['movies'] as $movie)
            <tr><td>{{ $movie['name'] }}</td><td>{{ $movie['size'] }}</td><td><a href="/movie/play/{{ urlencode($movie['file']) }}">播放</a></td></tr>
          @endforeach
      </tbody>
      </table>
    </div>
  </div>
@endsection
