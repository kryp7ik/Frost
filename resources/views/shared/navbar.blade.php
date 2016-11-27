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
        </div>
    </div>
    @if (Auth::check())
        <!-- Sidebar -->
        <div id="sidebar-wrapper" >
            <ul class="sidebar-nav nav" >
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
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- asidebar (Admin Sidebar) -->
        @if(Auth::user()->hasRole('manager'))
            <div id="asidebar-wrapper">
                <ul class="asidebar-nav">
                    <li>
                        <a href="/admin/store/products/index">
                            <i class="glyphicon glyphicon-tag"></i>
                            <span class="nav-text">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/products/redline">
                            <i class="glyphicon glyphicon-alert"></i>
                            <span class="nav-text">Redline</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/shipments">
                            <i class="glyphicon glyphicon-globe"></i>
                            <span class="nav-text">Shipments</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/transfers">
                            <i class="glyphicon glyphicon-transfer"></i>
                            <span class="nav-text">Transfers</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/inventory/create">
                            <i class="glyphicon glyphicon-flash"></i>
                            <span class="nav-text">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/discounts">
                            <i class="glyphicon glyphicon-usd"></i>
                            <span class="nav-text">Discounts</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/recipes">
                            <i class="glyphicon glyphicon-tint"></i>
                            <span class="nav-text">Recipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/report/sales">
                            <i class="glyphicon glyphicon-signal"></i>
                            <span class="nav-text">Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/store/touch">
                            <i class="glyphicon glyphicon-hand-up"></i>
                            <span class="nav-text">Touch</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users">
                            <i class="glyphicon glyphicon-user"></i>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
        <!-- /asidebar -->
    @endif
</nav>