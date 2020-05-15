<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<!--begin::Base Path (base relative path for assets of this page) -->
		<base href="{{ asset('') }}">

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>Duaz Backup</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		{{-- Favicon --}}
        <link rel="icon" href="{{ asset('db.ico') }}">
        {{-- css --}}
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="assets/css/login.css" rel="stylesheet" type="text/css" />
        {{-- script --}}
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="vendor/jsvalidation/js/jsvalidation.min.js" type="text/javascript"></script>
	</head>    
	<body>
		<div class="login">
			<h1>Duaz Backup</h1>
		    <form id="login-form" role="form" class="kt-form" action="{{ route('login') }}" method="post">
		        {{ csrf_field() }}
		    	<input type="email" placeholder="Email" name="email" required/>
		        <input type="password" placeholder="Password" name="password" required>
		        @if ($errors->any())
                    <span class="help-block" style="color:red">
                        <strong>@lang('auth.failed')</strong>
                    </span>
                @endif
		        <button type="submit" class="btn btn-primary btn-block btn-large">LOGIN</button>
		    </form>
		</div>
	</body>
</html>