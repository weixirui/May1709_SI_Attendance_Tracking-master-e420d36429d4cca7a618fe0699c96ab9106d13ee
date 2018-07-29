<!Doctype html>

<?php
    define('URL', 'http://attend.local/'); // For the live site -> http://siattendance.ece.iastate.edu/
    define('AtrackURL', 'https://atrack.its.iastate.edu/api/');
	define('PyPath','/usr/local/bin/python');
?>


<html>

    <head>
        @include('layout_partials.header')
    </head>

    <body>
        <div id="app" class='col-xs-12'>
            <!-- Nav Bar -->
            @include('layout_partials.nav')

            <!-- Page Content -->
            @yield('content')
        </div>

        <!-- Footer -->
        @include('layout_partials.footer')
        <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
        {!! Toastr::message() !!}
    </body>

</html>


