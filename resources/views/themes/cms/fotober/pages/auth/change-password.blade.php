<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('fotober.login.title_page') }}</title>
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
<div class="containt-fluid bg-sign-in">
    <!-- content -->
    <div class="row m-0">
        <div class="container">
            <div class="row mt-100">
                <div class="col-lg-4"></div>
                <div class="col-lg-4 sign-in">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="{{ asset('themes/cms/fotober/images/logo-new.png') }}" width="210px" alt="Logo" class="img-fluid" srcset="">
                            <h1 class="sign-in-title">{{ trans('fotober.login.sub_title') }}</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="{{ route('change_password') }}">
                                <!-- CSRF -->
                                <div class="form-group">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="client_ip" id="client_ip" value="">
                                    <input type="hidden" name="token" id="token" value="{{ request()->get('token') }}">
                                </div>
                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password" style="width: 100%">
                                        {{ trans('fotober.login.password') }}&nbsp;(<span class="require-input">*</span>)
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="">
                                    <small id="" class="require-input">
                                        @if ($errors->has('password'))
                                            <span class="validate-error">{{ $errors->first('password') }}</span>
                                        @endif
                                    </small>
                                </div>
                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password_confirmation" style="width: 100%">
                                        {{ trans('fotober.account.confirm_new_pass') }}&nbsp;(<span class="require-input">*</span>)
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="">
                                    <small id="" class="require-input">
                                        @if ($errors->has('password_confirmation'))
                                            <span class="validate-error">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </small>
                                </div>
                                <!-- Submit -->
                                <div class="form-group">
                                    <button class="btn btn-primary btn-submit" style="width: 100%">{{ trans('fotober.forgot.send') }}</button>
                                </div>
                                <!-- Sign-in -->
                                <div class="form-group">
                                    <p class="text-center">{{ trans('fotober.register.login_link_new') }}&nbsp;<a href="{{ route('register_form') }}">{{ trans('fotober.login.sign_in') }}</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
    </div>
</div>
<script>
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
