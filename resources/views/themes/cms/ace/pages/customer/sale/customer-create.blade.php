@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.customer.create_title') }}
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('sale_customer_store') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group">
                            {{ csrf_field() }}
                        </div>
                        {{-- Họ và tên --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="fullname">
                                {{ trans('fotober.customer.fullname') }}&nbsp;(<span class="form-required"></span>)
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}" class="form-control">
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
                        {{-- phone --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="phone">
                                {{ trans('fotober.customer.phone') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control">
                                @if ($errors->has('phone'))<span class="validate-error">{{ $errors->first('phone') }}</span>@endif
                            </div>
                        </div>
                        {{-- Birthday --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="birthday">
                                {{ trans('fotober.customer.birthday') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <div class="input-group">
                                    <input id="birthday" name="birthday" type="date" value="{{ old('birthday') }}" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        {{-- website --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="website">Website</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="website" id="website" value="{{ old('website') }}" class="form-control">
                                @if ($errors->has('website'))<span class="validate-error">{{ $errors->first('website') }}</span>@endif
                            </div>
                        </div>
                        {{-- email --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="email">
                                {{ trans('fotober.customer.email') }}&nbsp;(<span class="form-required"></span>)
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                                @if ($errors->has('email'))<span class="validate-error">{{ $errors->first('email') }}</span>@endif
                            </div>
                        </div>
                        {{-- password --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="password">
                                {{ trans('fotober.customer.password') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control">
                                @if ($errors->has('password'))<span class="validate-error">{{ $errors->first('password') }}</span>@endif
                            </div>
                        </div>
                        {{-- password_confirmation --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="password_confirmation">
                                {{ trans('fotober.customer.password_confirmation') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control">
                                @if ($errors->has('password_confirmation'))<span class="validate-error">{{ $errors->first('password_confirmation') }}</span>@endif
                            </div>
                        </div>
                        {{-- email_paypal --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="email_paylpal">
                                {{ trans('fotober.customer.email_paypal') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="email_paypal" id="email_paypal" value="{{ old('email_paypal') }}" class="form-control">
                                @if ($errors->has('email_paypal'))<span class="validate-error">{{ $errors->first('email_paypal') }}</span>@endif
                            </div>
                        </div>
                        {{-- Địa chỉ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="address">{{ trans('fotober.customer.address') }}</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="address" id="address" rows="5" class="form-control" style="resize: none">{{ old('address') }}</textarea>
                            </div>
                        </div>
                        {{-- Ghi chú nếu có --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="notes">{{ trans('fotober.order.form_note') }}</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="notes" id="notes" rows="5" class="form-control" style="resize: none">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('fotober.common.btn_create') }}</button>
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
                format: 'DD/MM/YYYY HH:mm:ss',
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
