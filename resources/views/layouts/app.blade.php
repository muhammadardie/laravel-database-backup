<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Database - Backup</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ asset('') }}">
    <link rel="icon" type="image/x-icon" href="db.ico">

    <!-- Fonts and icons -->
    <script src="assets/plugins/webfont/webfont.min.js" type="text/javascript"></script>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="assets/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="assets/plugins/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/plugins/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/plugins/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/plugins/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Bootstrap File Input -->
    <script src="assets/plugins/bootstrap-fileinput/js/fileinput.min.js"></script>

    <!-- JQuery BlockUI -->
    <script src="assets/plugins/jquery-blockui/jquery-blockui.js"></script>

    <!-- Select2 -->
    <script src="assets/plugins/select2/select2.min.js"></script>

    <!-- Alertify -->
    <script src="assets/plugins/alertify/alertify.min.js"></script>    

    <!-- JS Validation -->
    <script src="vendor/jsvalidation/js/jsvalidation.min.js" type="text/javascript"></script>
    
    <!-- Atlantis JS -->
    <script src="assets/js/atlantis.min.js"></script>

    <!-- Custom script -->
    <script src="assets/js/custom.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/atlantis.min.css">
    <!-- Bootstrap File Input -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-fileinput/css/fileinput.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="assets/plugins/select2/select2.min.css">
    <!-- Alertify -->
    <link rel="stylesheet" href="assets/plugins/alertify/css/alertify.css">
    <link rel="stylesheet" href="assets/plugins/alertify/css/themes/default.min.css">
    <!-- Custom -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body data-background-color="dark">
    <div class="wrapper">
        
        @include('layouts.header')
        
        @include('layouts.sidebar')

        <div class="main-panel">

            @yield('content')
            
            @include('layouts.footer')
        </div>
        
    </div>
</body>
</html>