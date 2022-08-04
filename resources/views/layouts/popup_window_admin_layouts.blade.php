<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>@yield('pageTitle') - {{WEBSITE_NAME}}</title>
  </head>
  <body class="animsition site-navbar-small">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience.</p>
    <![endif]-->

    <!-- Page -->
	@yield('pagecontent')
    <!-- End Page -->

  </body>
</html>
