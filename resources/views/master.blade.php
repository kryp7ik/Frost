<!DOCTYPE html>
<meta name="csrf_token" content="{{ csrf_token() }}">
<html lang="en">
    <head>
        <title> @yield('title') </title>
        @include('shared.css')

    </head>
    <body>
        @include('shared.navbar')
        <div id="wrapper">
            <div id="page-content-wrapper">
                @yield('content')
            </div>
        </div>
        @include('flash::message')
        @include('shared.scripts')
    <script type="text/javascript">
        $('div.alert').not('.alert-important').delay(3000).fadeOut(250);
    </script>
    </body>
</html>