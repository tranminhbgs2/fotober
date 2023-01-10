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
            <div class="row mt-100 justify-content-center">
                <div class="col-lg-7">
                    <div class="sign-in">
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{{ asset('themes/cms/fotober/images/logo-new.png') }}" width="210px" alt="Logo" class="img-fluid" srcset="">
                                <h1 class="sign-in-title">{{ trans('fotober.login.sub_title') }}</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                            </div>
                            <div class="col-lg-12">
                                <form method="post" action="{{ route('login') }}">
                                    <!-- CSRF -->
                                    <div class="form-group">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="client_ip" id="client_ip" value="">
                                    </div>
                                    <!-- Email -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">Email&nbsp;(<span class="require-input">*</span>)</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('email'))
                                                    <span class="validate-error">{{ $errors->first('email') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Password -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="password">
                                            {{ trans('fotober.login.password') }}&nbsp;(<span class="require-input">*</span>)
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('password'))
                                                    <span class="validate-error">{{ $errors->first('password') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row my-2">
                                        <label class="col-sm-4 col-form-label"></label>
                                        <div class="col-sm-8">
                                            <div class="text-right">
                                                <a href="{{ route('forgot_password_form') }}">{{ trans('fotober.login.forgot_pass') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Submit -->
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <button class="btn btn-primary btn-submit" style="width: 100%">{{ trans('fotober.login.title') }}</button>
                                            <!-- Sign-in -->
                                            <p class="text-center">{{ trans('fotober.login.register_link_new') }}&nbsp;<a href="{{ route('register_form') }}">{{ trans('fotober.login.sign_up') }}</a></p>
                                            <!--<div class="text-center">
                                                <a href="{{ route('login_change_language', ['locale' => 'vi']) }}">
                                                    <img src="{{ asset('images/languages/vi.png') }}">
                                                </a>&nbsp;&nbsp;
                                                <a href="{{ route('login_change_language', ['locale' => 'en']) }}">
                                                    <img src="{{ asset('images/languages/en.png') }}">
                                                </a>
                                            </div>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
