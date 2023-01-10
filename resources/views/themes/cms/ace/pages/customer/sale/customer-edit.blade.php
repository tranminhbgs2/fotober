@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.customer.edit_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.customer.edit_title') }}</h4>
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('sale_customer_update') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group m-0">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="{{ $customer->id }}" class="form-control">
                        </div>
                        {{-- Full Name --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.fullname') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="fullname" id="fullname" value="{{ isset($customer->fullname ) ? $customer->fullname : old('fullname') }}" class="form-control">
                                @if ($errors->has('fullname'))<span class="validate-error">{{ $errors->first('fullname') }}</span>@endif
                            </div>
                        </div>
                        {{-- Quốc gia/Vùng lãnh thổ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="fullname">
                                {{ trans('fotober.register.country') }}&nbsp;(<span class="form-required"></span>)
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select class="form-control" id="country_code" name="country_code" style="">
                                    <option value="">---</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->code }}" {{ ($country->code == $customer->country_code) ? 'selected = selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <small id="" class="require-input">
                                    @if ($errors->has('country_code'))
                                        <span class="validate-error">{{ $errors->first('country_code') }}</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        {{-- email --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.email') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="email" id="email" value="{{ isset($customer->email ) ? $customer->email : old('email') }}" class="form-control">
                                @if ($errors->has('email'))<span class="validate-error">{{ $errors->first('email') }}</span>@endif
                            </div>
                        </div>
                        {{-- phone --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.phone') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="phone" id="phone" value="{{ isset($customer->phone ) ? $customer->phone : old('phone') }}" class="form-control">
                                @if ($errors->has('phone'))<span class="validate-error">{{ $errors->first('phone') }}</span>@endif
                            </div>
                        </div>
                        {{-- Birthday --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.birthday') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input id="birthday" name="birthday" type="date" value="{{ isset($customer->birthday ) ? $customer->birthday : old('birthday') }}" class="form-control"/>
                                {{-- <span class="input-group-addon">
                                    <i class="fa fa-clock-o bigger-110"></i>
                                </span> --}}
                            </div>
                        </div>
                        {{-- website --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                website
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="website" id="website" value="{{ isset($customer->website ) ? $customer->website : old('website') }}" class="form-control">
                                @if ($errors->has('website'))<span class="validate-error">{{ $errors->first('website') }}</span>@endif
                            </div>
                        </div>
                        {{-- password --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.password') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control">
                                @if ($errors->has('password'))<span class="validate-error">{{ $errors->first('password') }}</span>@endif
                            </div>
                        </div>
                        {{-- password_confirm --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.password_confirmation')}}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
                                @if ($errors->has('password_confirmation'))<span class="validate-error">{{ $errors->first('password_confirmation') }}</span>@endif
                            </div>
                        </div>
                        {{-- email_paypal --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.email_paypal') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="email_paypal" id="email_paypal" value="{{ isset($customer->email_paypal ) ? $customer->email_paypal : old('email_paypal') }}" class="form-control">
                                @if ($errors->has('email_paypal'))<span class="validate-error">{{ $errors->first('email_paypal') }}</span>@endif
                            </div>
                        </div>
                        {{-- address --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.customer.address') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="address" id="address" rows="5" class="form-control" style="resize: none">{{ isset($customer->address ) ? $customer->address : old('address') }}</textarea>
                            </div>
                        </div>
                        {{-- note --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.order.form_note') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="notes" id="notes" rows="5" class="form-control" style="resize: none">{{ isset($customer->notes ) ? $customer->notes : old('notes') }}</textarea>
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
            // $('#upload_file').ace_file_input({
            //     no_file:'{{ trans('fotober.common.no_file') }}',
            //     btn_choose:'{{ trans('fotober.common.btn_choose') }}',
            //     btn_change:'{{ trans('fotober.common.btn_change') }}',
            //     droppable:false,
            //     onchange:null,
            //     thumbnail:false //| true | large
            //     //whitelist:'gif|png|jpg|jpeg'
            //     //blacklist:'exe|php'
            //     //onchange:''
            //     //
            // });

            $('#birthday').datetimepicker({
                format: 'DD/MM/YYYY',
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

            processOption();

        });

        function processOption(){
            if ($('input[name=options]').val() == 'LINK') {
                $('#option-link').css('display', 'block');
                $('#option-upload').css('display', 'none');
            }

            $('input[name=options]').click(function (){
                if ($(this).val() == 'LINK') {
                    $('#option-link').css('display', 'block');
                    $('#option-upload').css('display', 'none');
                } else {
                    $('#option-link').css('display', 'none');
                    $('#option-upload').css('display', 'block');
                }
            });
        }


    </script>
@endsection
