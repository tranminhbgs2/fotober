<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="refresh" content="1800" />
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('fotober.register.title_page') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" sizes="24x24" type="image/png" />
    <meta name="description" content="User login page"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/font-awesome/4.2.0/css/font-awesome.min.css') }}"/>
    <style>
        .validate-error { color: red !important; }
    </style>
    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/ace.min.css') }}"/>
    <script src="{{ asset('themes/cms/ace/assets/js/jquery.2.1.1.min.js') }}"></script>
</head>
<body class="login-layout" style="font-family: Arial !important;">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="space-12"></div>
                    <div class="center"></div>
                    <div class="col-lg-12">
                        @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                    </div>
                    <div class="space-6"></div>
                    <div class="">
                        @php $locale = \Illuminate\Support\Facades\App::getLocale(); @endphp
                        <select name="language" id="language" class="form-control" onchange="changeLanguage($(this).val())" style="width: 35%; float: right">
                            <option value="vi" {{ ($locale == 'vi') ? 'selected' : '' }}>Tiếng Việt</option>
                            <option value="en" {{ ($locale == 'en') ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="position-relative" style="clear: both">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <form class="widget-main" method="post" action="{{ route('register_form') }}">
                                    <h4 class="header blue lighter bigger" style="text-align: center">{{ trans('fotober.register.header') }}</h4>
                                    <div class="text-center">
                                        <img src="{{ asset('images/logo.png')}}" style="height: 45px">
                                    </div>
                                    <div class="space-6"></div>
                                    <fieldset>
                                        <!-- CSRF -->
                                        <div>
                                            <input type="hidden" name="client_ip" id="client_ip" value="">
                                            {{ csrf_field() }}
                                        </div>
                                        <!-- Họ và tên -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" name="fullname" value="{{ old('fullname') }}"
                                                       class="form-control" placeholder="{{ trans('fotober.register.fullname') }}" autofocus>
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                            @if ($errors->has('fullname'))
                                                <span class="validate-error">{{ $errors->first('fullname') }}</span>
                                            @endif
                                        </label>
                                        <!-- Website -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" name="website" value="{{ old('website') }}"
                                                       class="form-control" placeholder="{{ trans('fotober.register.website') }}">
                                                <i class="ace-icon fa fa-globe"></i>
                                            </span>
                                            @if ($errors->has('website'))
                                                <span class="validate-error">{{ $errors->first('website') }}</span>
                                            @endif
                                        </label>
                                        <!-- Số di động -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" name="phone" value="{{ old('phone') }}"
                                                       class="form-control" placeholder="{{ trans('fotober.register.phone') }}">
                                                <i class="ace-icon fa fa-mobile"></i>
                                            </span>
                                            @if ($errors->has('phone'))
                                                <span class="validate-error">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </label>
                                        <!-- Email tài khoản Paypal -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="email" name="email_paypal" value="{{ old('email_paypal') }}"
                                                       class="form-control" placeholder="{{ trans('fotober.register.email_paypal') }}">
                                                <i class="ace-icon fa fa-envelope"></i>
                                            </span>
                                            @if ($errors->has('email_paypal'))
                                                <span class="validate-error">{{ $errors->first('email_paypal') }}</span>
                                            @endif
                                        </label>
                                        <!-- Email -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                       class="form-control" placeholder="{{ trans('fotober.register.email') }}" style="border-color: red">
                                                <i class="ace-icon fa fa-envelope"></i>
                                            </span>
                                            @if ($errors->has('email'))
                                                <span class="validate-error">{{ $errors->first('email') }}</span>
                                            @endif
                                        </label>
                                        <!-- Mật khẩu -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" name="password"
                                                       class="form-control" placeholder="{{ trans('fotober.register.password') }}" style="border-color: red">
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                            @if ($errors->has('password'))
                                                <span class="validate-error">{{ $errors->first('password') }}</span>
                                            @endif
                                        </label>
                                        <!-- Xác nhận mật khẩu -->
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" name="password_confirmation"
                                                       class="form-control" placeholder="{{ trans('fotober.register.password_confirmation') }}" style="border-color: red">
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="validate-error">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </label>
                                        <div class="space" style="clear: both"></div>
                                        <!-- Submit -->
                                        <div class="clearfix" style="text-align: center;">
                                            <button type="submit" name="btnLogin" class="width-100 pull-right btn btn-sm btn-primary">{{ trans('fotober.register.title') }}</button>
                                        </div>
                                        <div class="space-12"></div>
                                        <!-- Link đăng nhập -->
                                        <div class="col-lg-12 col-md-12 col col-sm-12 text-center">
                                            <a href="{{ route('login_form') }}">{{ trans('fotober.register.login_link') }}</a>
                                        </div>
                                    </fieldset>
                                </form>
                                <!-- /.widget-main -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <div style="text-align: center"></div>
                        <!-- /.login-box -->
                    </div>
                    <!-- /.position-relative -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.main-content -->
</div>
<!-- /.main-container -->
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
