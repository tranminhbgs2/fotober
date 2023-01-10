<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8"/>
    <title>Fotober | Page not found</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" sizes="24x24" type="image/png" />
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/bootstrap.min.css') }}"/>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center" style="margin-top: 50px">
            <img src="{{ asset('images/logo.png') }}">
        </div>
        <div class="col-lg-12 text-center" style="margin-top: 25px">
            <img src="{{ asset('images/page404.png') }}">
        </div>
        <div class="col-lg-12 text-center" style="margin-top: 25px">
            <a href="{{ route('dashboard_home') }}">{{ trans('fotober.common.back_to_home') }}</a>
        </div>
    </div>
</div>
</body>
</html>
