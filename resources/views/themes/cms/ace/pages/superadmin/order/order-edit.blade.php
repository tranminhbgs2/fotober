@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.edit_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.order.edit_title') }}</h4>
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
                    <form class="form-horizontal" role="form" method="post" action="{{ route('superadmin_order_update') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group mb-0">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="{{ $order->id }}" class="form-control">
                        </div>
                        {{-- Tên khác hàng --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">
                                {{ trans('fotober.order.form_customer') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input class="form-control" type="text" value="{{ $order->customer->fullname }}&nbsp;({{ $order->customer->email }})" disabled>
                            </div>
                        </div>
                        {{-- Tên đơn hàng --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_name') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="name" id="name" value="{{ isset($order->name ) ? $order->name : old('name') }}" class="form-control">
                                @if ($errors->has('name'))<span class="validate-error">{{ $errors->first('name') }}</span>@endif
                            </div>
                        </div>

                        {{-- Loại dịch vụ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_service_type') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select class="form-control custom-select" name="service_id" id="service_id">
                                    <option value="">{{ trans('fotober.common._select_') }}</option>
                                    @foreach($services as $key => $service)
                                        <option value="{{ $service->id }}" {{ ($order->service_id  == $service->id) ? 'selected' : '' }}>{{ $service->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('service_id'))<span class="validate-error">{{ $errors->first('service_id') }}</span>@endif
                            </div>
                        </div>

                        {{-- turn arround time --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_turn_around') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select name="turn_arround_time" id="turn_arround_time" class="form-control custom-select">
                                    <option value="0">{{ trans('fotober.common.no_change') }}</option>
                                    @foreach($turn_arround_times as $time_key => $time_value)
                                        <option value="{{ $time_key }}" {{ ($order->turn_arround_time == $time_key) ? 'selected' : '' }}>{{ $time_value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('turn_arround_time'))<span class="validate-error">{{ $errors->first('turn_arround_time') }}</span>@endif
                            </div>
                        </div>

                        {{-- Ghi chú nếu có --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_note') }}</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="notes" id="notes" rows="5" class="form-control" style="resize: none">{{ ($order->notes ) ? $order->notes  : old('notes') }}</textarea>
                            </div>
                        </div>

                        @if ($is_admin && $account_type == 'SALE')
                        {{-- Giao việc cho sale thực hiện --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_sale') }}</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select name="sale_id" id="sale_id" class="form-control custom-select">
                                    <option value="">{{ trans('fotober.common._select_sale_') }}</option>
                                    @foreach($sales as $item)
                                        <option value="{{ $item->id }}" {{ (($order->assigned_sale_id > 0 && $item->id == $order->assigned_sale_id) ? 'selected' : '') }}>{{ $item->email }}&nbsp;({{ $item->fullname }})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('sale_id'))<span class="validate-error">{{ $errors->first('sale_id') }}</span>@endif
                            </div>
                        </div>
                        @endif

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

            $('#deadline').datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                minDate: moment().add(2, 'hours').format('DD/MM/YYYY HH:mm:ss')
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
