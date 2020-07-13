<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container ">
    <a class="navbar-brand" href="{{ route('home') }}">Weibo App</a>
    <ul class="navbar-nav justify-content-end">
      @if (Auth::check())
        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">&nbsp;\|/&nbsp;用户列表&nbsp;\|/&nbsp;</a></li>

        <li class="nav-link" style="color: orange;" href="#" id="" role="">
          {{ Auth::user()->name }}
        </li>

        <li class="nav-item"><a class="nav-link" href="{{ route('users.show', Auth::user()) }}">个人中心</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('users.edit', Auth::user()) }}">编辑资料</a></li>
        <a class="my-auto ml-1" id="logout" href="#">
          <form action="{{ route('logout') }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-block btn-danger py-1 px-2" style="font-size: 14px;text-decoration: none;" type="submit" name="button">退出</button>
          </form>
        </a>

        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button">
            {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">个人中心</a>
            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}">编辑资料</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" id="logout" href="#">
              <form action="{{ route('logout') }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-block btn-danger" type="submit" name="button"></button>
              </form>
            </a>
          </div>
        </li> -->

      @else
        <li class="nav-item"><a class="nav-link" href="{{ route('help') }}">帮助</a></li>
        <li class="nav-item" ><a class="nav-link" href="{{ route('login') }}">登录</a></li>
      @endif
    </ul>
  </div>
</nav>

