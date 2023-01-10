@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sadmin.user.edit') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.sadmin.user.edit') }}</h4>
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('superadmin_update_user') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group mb-0">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="{{ $user->id }}" class="form-control">
                        </div>
                        {{-- Email tài khoản --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.email') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" value="{{ $user->email }}" class="form-control" disabled>
                            </div>
                        </div>
                        {{-- Nhóm nhân viên --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.group') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" value="{{ $user->account_type }}" class="form-control" disabled>
                            </div>
                        </div>
                        {{-- Có là nhân viên hay quản lý --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.is_admin') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" value="{{ ($user->is_admin == 1) ? 'Quản lý' : 'Nhân viên' }}" class="form-control" disabled>
                            </div>
                        </div>
                        {{-- Họ và tên --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.fullname') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="fullname" id="fullname" value="{{ isset($user->fullname ) ? $user->fullname : old('fullname') }}" class="form-control">
                            @if ($errors->has('fullname'))<span class="validate-error">{{ $errors->first('fullname') }}</span>@endif
                            </div>
                        </div>
                        {{-- Birthday --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.birthday') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <div class="input-group">
                                    <input id="birthday" name="birthday" type="text" class="form-control" readonly
                                    value="{{ (isset($user->birthday) && $user->birthday) ? date('d/m/Y', strtotime($user->birthday)) : old('birthday') }}" />
                                    <span class="input-group-append">
                                        <button class="btn btn-info" type="button"><i class="ti-timer"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{-- Giới tính --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.gender') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select name="gender" class="form-control custom-select" style="width: 100%">
                                    <option value="1" {{ ($user->gender == 1) ? 'selected' : '' }}>Nam</option>
                                    <option value="2" {{ ($user->gender == 2) ? 'selected' : '' }}>Nữ</option>
                                    <option value="3">Khác</option>
                                </select>
                                @if ($errors->has('gender'))<span class="validate-error">{{ $errors->first('gender') }}</span>@endif
                            </div>
                        </div>
                        {{-- phone --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.phone') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="phone" id="phone" value="{{ isset($user->phone ) ? $user->phone : old('phone') }}" class="form-control">
                                @if ($errors->has('phone'))<span class="validate-error">{{ $errors->first('phone') }}</span>@endif
                            </div>
                        </div>
                        {{-- Địa chỉ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.sadmin.user.address') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="address" id="address" rows="5" class="form-control" style="resize: none">{{ isset($user->address ) ? $user->address : old('address') }}</textarea>
                                @if ($errors->has('address'))<span class="validate-error">{{ $errors->first('address') }}</span>@endif
                            </div>
                        </div>
                        {{-- Submit --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('fotober.common.btn_edit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#birthday').datetimepicker({
                format: 'DD/MM/YYYY',
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

        });


    </script>
@endsection
