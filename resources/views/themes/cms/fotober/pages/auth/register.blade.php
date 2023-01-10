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
                                <h1 class="sign-in-title">{{ trans('fotober.register.header') }}</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                            </div>
                            <div class="col-lg-12">
                                <form method="post" action="{{ route('register_form') }}">
                                    <!-- CSRF -->
                                    <div class="form-group row">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="client_ip" id="client_ip" value="">
                                    </div>
                                    <!-- Họ và tên -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">
                                            {{ trans('fotober.register.fullname') }}&nbsp;(<span class="require-input">*</span>)
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname') }}" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('fullname'))
                                                    <span class="validate-error">{{ $errors->first('fullname') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Quốc gia/Vùng lãnh thổ -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">
                                            {{ trans('fotober.register.country') }}&nbsp;(<span class="require-input">*</span>)
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="country_code" name="country_code" style="padding: 0px 0.8rem; height: 46px">
                                                <option value="">---</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <small id="" class="require-input">
                                                @if ($errors->has('country_code'))
                                                    <span class="validate-error">{{ $errors->first('country_code') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Website -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">
                                            {{ trans('fotober.register.website') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="email" name="email" value="{{ old('website') }}" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('website'))
                                                    <span class="validate-error">{{ $errors->first('website') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Số di động -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">
                                            {{ trans('fotober.register.phone') }}
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="email" name="email" value="{{ old('phone') }}" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('phone'))
                                                    <span class="validate-error">{{ $errors->first('phone') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Email -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="email">
                                            Email&nbsp;(<span class="require-input">*</span>)
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('email'))
                                                    <span class="validate-error">{{ $errors->first('email') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Password -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="password" style="width: 100%">
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
                                    <!-- password_confirmation -->
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="password" style="width: 100%">
                                            {{ trans('fotober.register.password_confirmation') }}&nbsp;(<span class="require-input">*</span>)
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="">
                                            <small id="" class="require-input">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="validate-error">{{ $errors->first('password_confirmation') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <!-- Submit -->
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-8">
                                            <button class="btn btn-primary btn-submit" style="">{{ trans('fotober.register.title') }}</button>
                                            <p class="text-center mt-3">{{ trans('fotober.register.login_link_new') }}&nbsp;<a href="{{ route('login_form') }}">{{ trans('fotober.login.sign_in') }}</a></p>

                                            <!-- Sign-in -->
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
