@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.account.change_pass_title_page') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Change Password</h4>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (isset($user) && $user)
                        {{-- alert --}}
                        <div class="d-block">
                            @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                        </div>
                        
                        <form class="form-horizontal" role="form" method="post" action="{{ route('account_process_change_password', ['id' => $user->id]) }}" enctype="multipart/form-data">
                            {{-- CSRF --}}
                            <div class="form-group">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{ $user->id }}" class="form-control">
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-3 col-form-label">{{ trans('fotober.account.old_pass') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-3 col-lg-4 input-group" id="show_hide_old_password">
                                    <input type="password" name="old_password" id="old_password" value="{{ old('old_password') }}" class="form-control">
                                    <div class="input-group-append">
                                        <a href="" class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                      </div>
                                      @if ($errors->has('old_password'))<span class="validate-error col-12 p-0">{{ $errors->first('old_password') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-3 col-form-label">{{ trans('fotober.account.new_pass') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-3 col-lg-4 input-group" id="show_hide_password">
                                    <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control">
                                    <div class="input-group-append">
                                        <a href="" class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                    </div>
                                    @if ($errors->has('password'))<span class="validate-error col-12 p-0">{{ $errors->first('password') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-3 col-form-label">{{ trans('fotober.account.confirm_new_pass') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-3 col-lg-4 input-group" id="show_hide_password_confirmation">
                                    <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
                                    <div class="input-group-append">
                                        <a href="" class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                    </div>
                                    @if ($errors->has('password_confirmation'))<span class="validate-error col-12 p-0">{{ $errors->first('password_confirmation') }}</span>@endif
                                </div>
                            </div>
                            <div class=" col-lg-7 text-right">
                                <button type="submit" class="btn btn-info ml-auto mr-1"> {{ trans('fotober.common.btn_edit') }} </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#show_hide_old_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_old_password input').attr("type") == "text"){
                    $('#show_hide_old_password input').attr('type', 'password');
                    $('#show_hide_old_password i').addClass( "fa-eye-slash" );
                    $('#show_hide_old_password i').removeClass( "fa-eye" );
                }else if($('#show_hide_old_password input').attr("type") == "password"){
                    $('#show_hide_old_password input').attr('type', 'text');
                    $('#show_hide_old_password i').removeClass( "fa-eye-slash" );
                    $('#show_hide_old_password i').addClass( "fa-eye" );
                }
            });
            
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password input').attr("type") == "text"){
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass( "fa-eye-slash" );
                    $('#show_hide_password i').removeClass( "fa-eye" );
                }else if($('#show_hide_password input').attr("type") == "password"){
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass( "fa-eye-slash" );
                    $('#show_hide_password i').addClass( "fa-eye" );
                }
            });
            
            $("#show_hide_password_confirmation a").on('click', function(event) {
                event.preventDefault();
                if($('#show_hide_password_confirmation input').attr("type") == "text"){
                    $('#show_hide_password_confirmation input').attr('type', 'password');
                    $('#show_hide_password_confirmation i').addClass( "fa-eye-slash" );
                    $('#show_hide_password_confirmation i').removeClass( "fa-eye" );
                }else if($('#show_hide_password_confirmation input').attr("type") == "password"){
                    $('#show_hide_password_confirmation input').attr('type', 'text');
                    $('#show_hide_password_confirmation i').removeClass( "fa-eye-slash" );
                    $('#show_hide_password_confirmation i').addClass( "fa-eye" );
                }
            });
        });
    </script>
@endsection
