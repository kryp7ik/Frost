<html>
    <head>
        <title> @yield('title') </title>
        @include('shared.css')
        @include('shared.scripts')
    </head>
    <body>
        @include('shared.navbar')
        <div id="wrapper">
            <div id="page-content-wrapper">
                @yield('content')
            </div>
        </div>
    </body>
</html>