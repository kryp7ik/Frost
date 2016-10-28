@inject('suspended', 'App\Services\Store\SuspendedOrders')
<nav class="navbar navbar-info">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Frost POS 1.0</a>
        </div>
        <!-- Navbar Right -->
        <div id="snow" class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    @if (Auth::user()->hasRole('manager'))
                        <li><a href="/admin">Dashboard</a></li>
                    @endif
                @endif
                @if (Auth::check())
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
        </div>
    </div>
    <!-- Sidebar -->
    <div id="sidebar-wrapper" >
        <ul class="sidebar-nav nav" >
            @if (Auth::check())

                    <li>
                        <a href="/schedule"><span class="glyphicon glyphicon-calendar"></span> Schedule</a>
                    </li>
                    <li>
                        <a href="/customers"><span class="glyphicon glyphicon-user"></span> Customers</a>
                    </li>
                    <li>
                        <a href="/orders"><span class="glyphicon glyphicon-th-list"></span> All Orders</a>
                    </li>
                    <li>
                        <a href="/orders/create"><span class="glyphicon glyphicon-ok"></span> New Order</a>
                    </li>
                    @foreach($suspended->getSuspendedOrders() as $order)
                        <li>
                            <a href="/orders/{{ $order->id }}/show"><span class="text-info">Suspended {{ $order->total }}</span></a>
                        </li>
                    @endforeach


            @endif
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->
</nav>