@extends('template')

@section('content')
    <div class="ge aom">
        <nav class="aot">
            <div class="aon">
                <button class="amy amz aoo" type="button" data-toggle="collapse" data-target="#nav-toggleable-sm">
                    <span class="ct">Toggle nav</span>
                </button>
                <a class="aop cn" href="index.html">
                    <span class="bv act aoq"></span>
                </a>
            </div>

            <div class="collapse and" id="nav-toggleable-sm">
                <ul class="nav of nav-stacked">
                    <li class="tq">Dashboards</li>
                    @foreach($menus as $menu)
                      <li @if($view==$menu['view'] && $action==$menu['action']) class="active" @endif>
                          <a href="/{{ $menu['view'] }}/{{ $menu['action'] }}">{{ $menu['name'] }}</a>
                      </li>
                    @endforeach
                </ul>
                <hr class="rw aky">
            </div>
        </nav>
    </div>
    <div class="hc aps">
        <div class="apa">
            <div class="apb">
                <h6 class="apd">Dashboards</h6>
                <h2 class="apc">{{ $title }}</h2>
            </div>

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
        </div>

        <hr class="aky">

        @yield('body')

      </div>
@endsection
