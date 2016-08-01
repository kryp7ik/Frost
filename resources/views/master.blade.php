<!DOCTYPE html>
<meta name="csrf_token" content="{{ csrf_token() }}">
<html lang="en">
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