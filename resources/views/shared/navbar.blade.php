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
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/tickets">Tickets</a></li>
                <li><a href="/blog">Blog</a></li>
                @if (Auth::check())
                    @if (Auth::user()->hasRole('manager'))
                        <li><a href="/admin">Dashboard</a></li>
                    @endif
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">User <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @if (Auth::check())
                            <li><a href="/users/logout">Logout</a></li>
                        @else
                            <li><a href="/users/register">Register</a></li>
                            <li><a href="/users/login">Login</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav nav">
            <li>
                <a href="/admin">Dashboard</a>
            </li>
            <li>
                <a href="/admin/store/products">Products</a>
            </li>
            <li>
                <a href="/admin/store/ingredients">Ingredients</a>
            </li>
            <li>
                <a href="/admin/store/recipes">Recipes</a>
            </li>
            <li>
                <a href="#">About</a>
            </li>
            <li>
                <a href="#">Services</a>
            </li>
            <li>
                <a href="#">Contact</a>
            </li>
        </ul>
    </div>
    <!-- /#sidebar-wrapper -->
</nav>