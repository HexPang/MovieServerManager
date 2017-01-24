@extends('body')
@section('right_section')
  <div class="ob ape">
  <div class="tn aol">
  <div class="aor">
  <input class="form-control" id="movie_id" type="text" placeholder="请输入ID...">
  <button type="button" class="fm" onclick="location.href='/movie/' + $('#movie_id').val();">
  <span class="bv adn"></span>
  </button>
  </div>
  </div>
  </div>
@endsection
@section('header')
  <style>
  .movie-block {
    position: relative;
    padding: 10px 15px;
    margin-bottom: -1px;
    background-color: transparent;
    border: 1px solid #434857;
    height:165px;
  }
  .movie-image {
    float:left;
    width:25%;
  }
  .movie-summery {
    float:right;
    width:70%;
    min-width: 55%;
    max-width: 70%;
  }
  .movie-table {
    max-height: 48px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .ph label {
    margin-left:5px;
  }
  .title {
    max-height: 41px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .movie-filter {
    margin-bottom:5px;
  }
  </style>
@endsection
@section('body')
  <div class="ph movie-filter">
    @foreach($data['movie_type'] as $key=>$name)
      <a href="/movie/list/{{ $data['page'] }}/{{ urlencode($name) }}">{{ $name }}</a>
    @endforeach
  </div>
  <div class="fu">
    @foreach($data['movies'] as $movie)
      <div class="gr">
          <div class="by">
              <a class="ty title" href="/movie/{{ $movie['id'] }}">
                @if(@$movie['score'] > 0)
                  <label class="score">{{ $movie['score'] }}</label>
                @endif
                  {{ $movie['title'] }}
              </a>
              <div class="movie-block">
                  <div class="movie-image">
                    <img src="{{ $movie['image'] }}" height="144" width="100">
                  </div>
                  <div class="movie-summery">
                    <div class="ph movie-table">
                      <span class="dh">类型</span>
                      @foreach($movie['type'] as $type){{ $type }}&nbsp;@endforeach
                    </div>

                    <div class="ph movie-table">
                      <span class="dh">导演</span>
                      @foreach($movie['director'] as $country){{ $country }}&nbsp;@endforeach
                    </div>

                    <div class="ph movie-table">
                      <span class="dh">演员</span>
                      @foreach($movie['actor'] as $actor){{ $actor }}&nbsp;@endforeach
                    </div>
                  </div>
              </div>
          </div>
      </div>
    @endforeach
  </div>
  <div class="db">
  <ul class="ow">
    @if($param != null && $param != 1)
    <li>
      <a href="/movie/list/{{ $param-1 }}/{{ $param1 ? $param1 : 0 }}" aria-label="Previous">
        <span aria-hidden="true">«</span>
      </a>
    </li>
  @endif
    {{-- <li class="active"><a href="#">1</a></li> --}}
    @for($i=$data['page_range'][0];$i<=$data['page_range'][1];$i++)
      <li @if($data['page'] == $i) class='active' @endif><a href="/movie/list/{{ $i }}/{{ $param1 ? $param1 : 0 }}">{{ $i }}</a></li>
    @endfor
    <li>
      <a href="/movie/list/{{ ($param ? $param : 1) + 1 }}/{{ $param1 ? $param1 : 0 }}" aria-label="Next">
        <span aria-hidden="true">»</span>
      </a>
    </li>
  </ul>
</div>
@endsection
