<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('fotober.forgot.title_page') }}</title>
    <link rel="shortcut icon" href="{{ asset('themes/cms/fotober/images/faviconx32.png') }}" sizes="24x24" type="image/png" />
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('themes/cms/fotober/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/cms/fotober/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/cms/fotober/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/cms/fotober/assets/css/page.css') }}">
    <!-- script -->
    <script src="{{ asset('themes/cms/fotober/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('themes/cms/fotober/assets/js/bootstrap.min.js') }}"></script>
</head>
<body>
<div class="containt-fluid bg-sign-in" style="padding-bottom: 200px">
    <!-- content -->
    <div class="row m-0">
        <div class="container">
            <div class="row mt-100">
                <div class="col-lg-3"></div>
                <div class="col-lg-6 sign-in">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('themes/cms/fotober/images/logo-new.png') }}" width="210px" alt="Logo" class="img-fluid" srcset="">
                            </a>
                            <h1 class="sign-in-title">{{ trans('fotober.forgot.header') }}</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                        </div>
                        <div class="col-lg-12">
                            <form method="post" action="{{ route('forgot_password') }}">
                                <!-- CSRF -->
                                <div class="form-group row">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="client_ip" id="client_ip" value="">
                                </div>
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">
                                        {{ trans('fotober.forgot.notes') }}
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="">
                                    <small id="" class="require-input">
                                        @if ($errors->has('email'))
                                            <span class="validate-error">{{ $errors->first('email') }}</span>
                                        @endif
                                    </small>
                                </div>
                                <!-- Submit -->
                                <div class="form-group">
                                    <button class="btn btn-primary btn-submit" style="">{{ trans('fotober.forgot.title') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeLanguage(locale){
        window.location.href = window.location.origin + '/login-change-language/' + locale;
    }

    $.getJSON('https://jsonip.com/?callback=?', function(data) {
        var ip = 'unknow';
        if (data !== null) {
            ip = data.ip;
        }
        $('#client_ip').val(ip);
    });
</script>
</body>
</html>
