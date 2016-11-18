<!DOCTYPE html>
<meta name="csrf_token" content="{{ csrf_token() }}">
<html lang="en">
    <head>
        <title> @yield('title') </title>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
        <link href="/css/all.css" rel="stylesheet" />
        <link href="/css/app.css" rel="stylesheet" />
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />
        @stack('css')

    </head>
    <body>
        @include('shared.navbar')
        <div id="wrapper">
            <div id="page-content-wrapper">
                @yield('content')
            </div>
        </div>
        @foreach ($errors->all() as $error)
            <p class="alert alert-danger">{{ $error }}</p>
        @endforeach
        @include('flash::message')
        <script src="/js/all.js"></script>
        @stack('scripts')
    </body>
</html>