<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">FrostPOS</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/orders"><span class="glyphicon glyphicon-th-list"></span> All Orders</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li>
                        @if(Auth::user()->hasRole('admin'))
                            <a href="/admin/users/{{ Auth::user()->id }}/edit">
                                @else
                                    <a href="#" >
                                        @endif

                                        @if(Auth::user()->store)
                                            Active Store:
                                            {{ config('store.stores')[Auth::user()->store] }}
                                        @else
                                            You are not clocked in!
                                        @endif
                                    </a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->email }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/users/logout">Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li><a href="/users/login">Login</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>