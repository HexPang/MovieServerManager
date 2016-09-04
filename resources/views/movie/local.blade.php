@extends('body')

@section('body')
  <div class="ud">
    <div class="eg">
      <table class="cl" data-sort="table">
        <thead>
          <tr>
            <th class="header">文件名</th>
            <th class="header">大小</th>
            <th class="header">格式</th>
            <th class="header">操作</th>
          </tr>
        </thead>
        <tbody>
          {{-- @foreach($data['disks'] as $disk)
            <tr>
              <td>{{ $disk[0] }}</td>
              <td>{{ $disk[5] }}</td>
              <td>{{ $disk[1] }}</td>
              <td>{{ $disk[2] }}</td>
              <td>{{ $disk[3] }}</td>
              <td>{{ $disk[4] }}</td>
            </tr>
          @endforeach --}}
      </tbody>
      </table>
    </div>
  </div>
@endsection
