@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.account.profile_title_update') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.account.profile_title_update') }}</h4>
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
                    <div class="d-block">
                        @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                    </div>
                    @if (isset($user) && $user)
                        <form class="form-horizontal" role="form" method="post" action="{{ route('account_update_profile') }}" enctype="multipart/form-data">
                            {{-- CSRF --}}
                            <div class="form-group m-0">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{ $user->id }}" class="form-control">
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.username') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <input class="form-control" type="text" value="{{ $user->username }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.fullname') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <input type="text" name="fullname" id="fullname" value="{{ isset($user->fullname ) ? $user->fullname : old('fullname') }}" class="form-control">
                                    @if ($errors->has('fullname'))<span class="validate-error">{{ $errors->first('fullname') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.phone') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <input type="text" name="phone" id="phone" value="{{ isset($user->phone ) ? $user->phone : old('phone') }}" class="form-control">
                                    @if ($errors->has('phone'))<span class="validate-error">{{ $errors->first('phone') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.email_paypal') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <input type="text" name="email_paypal" id="email_paypal" value="{{ isset($user->email_paypal ) ? $user->email_paypal : old('email_paypal') }}" class="form-control">
                                    @if ($errors->has('email_paypal'))<span class="validate-error">{{ $errors->first('email_paypal') }}</span>@endif
                                </div>
                            </div>
                            <div id="option-upload">
                                <div class="form-group row">
                                    <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                        {{ trans('fotober.account.avatar') }}
                                    </label>
                                    <div class="col-12 col-md-9 col-lg-4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="file" id="avatar" name="avatar" class="form-control mb-3">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <img src="{{ $user->avatar }}" style="max-width: 100px; max-height: 100px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.birthday') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <div class="input-group">
                                        <input id="birthday" name="birthday" type="text" class="form-control" readonly
                                            value="{{ ($user->birthday ) ? date('d/m/Y', strtotime($user->birthday )) : old('birthday') }}" />
                                        <span class="input-group-append">
                                            <button class="btn btn-info" type="button"><i class="ti-timer"></i></button>
                                        </span>
                                    </div>
                                    @if ($errors->has('birthday'))<span class="validate-error">{{ $errors->first('birthday') }}</span>@endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.account.address') }}<span class="text-danger ml-1">*</span></label>
                                <div class="col-12 col-md-9 col-lg-4">
                                    <textarea name="address" id="address" rows="5" class="form-control" style="resize: none">{{ ($user->address ) ? $user->address  : old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="text-left">
                                <button type="submit" class="btn btn-info ml-auto">{{ trans('fotober.common.btn_edit') }}</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            // $('#avatar').ace_file_input({
            //     no_file:'{{ trans('fotober.common.no_file') }}',
            //     btn_choose:'{{ trans('fotober.common.btn_choose') }}',
            //     btn_change:'{{ trans('fotober.common.btn_change') }}',
            //     droppable:false,
            //     onchange:null,
            //     thumbnail:false //| true | large
            //     // whitelist:'gif|png|jpg|jpeg'
            //     // blacklist:'exe|php'
            //     //onchange:''
            //     //
            // });

            $('#birthday').datetimepicker({
                format: 'DD/MM/YYYY'
                // minDate: moment().add(2, 'years').format('DD/MM/YYYY HH:mm:ss')
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

            // processOption();

        });

    </script>
@endsection
