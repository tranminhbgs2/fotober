@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.detail_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row order-sumary">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles" style="display: block;">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor title">{{ trans('fotober.order.order_summary') }}</h4>
            </div>
        </div>
        <div class="col-md-12">
            <div class="d-block">
                @include('themes.cms.ace.layouts.common.alert-message')
            </div>
        </div>
        <!-- Column -->
        <div class="col-12">
            <div class="row d-flex align-items-center no-block">
                <div class="col-md-12">
                    <div class="card box-shadow border-r10">
                        <div class="card-body" id="web-order">

                            <div class="row d-flex align-items-center no-block">
                                <div class="col">
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.delivery') }}</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_status') }}</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">Input</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex no-block">
                                <div class="col">
                                    <div class="col-md-12 p-0">
                                        <p class="font-weight-bold p-12 mb-5 pt-5px">{{$order->code}}</p>
                                        <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.order_type') }}</h6>
                                        <p class="font-weight-bold p-12">{{ $order->service->name }}</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12 p-0">
                                        <p class="font-weight-bold p-12 mb-48 pt-5px">{{ $order->turn_arround_time }} {{ trans('fotober.order.hours') }}</p>
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.order.order_submited') }}</h6>
                                        <p class="font-weight-bold p-12">{{ (($order->sent_sale_at) ? date('d/m/Y H:i', strtotime($order->sent_sale_at)) : '') }}</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12 p-0">
                                        <div class="timeline">
                                            <div class="tl-item active">
                                                <div class="tl-dot b-primary"></div>
                                                <div class="tl-content">
                                                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_DRAFT)
                                                        <div class="color-blue">{{ trans('fotober.order.status_0') }}</div>
                                                    @else
                                                        <div class="color-blue">{{ trans('fotober.order.status_1') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="tl-item {{ ($order->status > \App\Helpers\Constants::ORDER_STATUS_NEW) ? 'active' : ''}}">
                                                <div class="tl-dot b-primary"></div>
                                                <div class="tl-content">
                                                    <div class="">{{ trans('fotober.order.status_2') }}</div>
                                                </div>
                                            </div>
                                            <div class="tl-item {{ ($order->status > \App\Helpers\Constants::ORDER_STATUS_PENDING) ? 'active' : ''}}">
                                                <div class="tl-dot b-primary"></div>
                                                <div class="tl-content">
                                                    <div class="">{{ trans('fotober.order.status_3') }}</div>
                                                </div>
                                            </div>
                                            <div class="tl-item {{ ($order->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED) ? 'active' : ''}}">
                                                <div class="tl-dot b-primary"></div>
                                                <div class="tl-content">
                                                    <div class="">{{ trans('fotober.order.status_8') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="col-md-12 p-0">
                                        <a href="javascript:void(0)" class="font-weight-bold p-12 mb-5 pt-5px" onclick="showInput({{ $order->id }})" title="Click to Show Input"><p class="">{{ trans('fotober.order.view') }}</p></a>
                                        <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.instruction') }}</h6>
                                        <p class="font-weight-bold p-12">{{ (($order->notes) ? $order->notes : '') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="mobile-order">

                            <div class="row d-flex no-block">
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                                    <p class="font-weight-bold p-12 mb-2 pt-5px">{{$order->code}}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.order_type') }}</h6>
                                    <p class="font-weight-bold mb-2 p-12">{{ $order->service->name }}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.delivery') }}</h6>
                                    <p class="font-weight-bold p-12 mb-2 pt-5px">{{ $order->turn_arround_time }} {{ trans('fotober.order.hours') }}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.order.order_submited') }}</h6>
                                    <p class="font-weight-bold mb-2 p-12">{{ (($order->sent_sale_at) ? date('d/m/Y H:i', strtotime($order->sent_sale_at)) : '') }}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">Input</h6>
                                    <a href="javascript:void(0)" class="font-weight-bold p-12 mb-2 pt-5px" onclick="showInput({{ $order->id }})" title="Click to Show Input"><p class="">{{ trans('fotober.order.view') }}</p></a>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_status') }}</h6>
                                    
                                    <div class="timeline mb-2">
                                        <div class="tl-item active">
                                            <div class="tl-dot b-primary"></div>
                                            <div class="tl-content">
                                                @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_DRAFT)
                                                    <div class="color-blue">{{ trans('fotober.order.status_0') }}</div>
                                                @else
                                                    <div class="color-blue">{{ trans('fotober.order.status_1') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tl-item {{ ($order->status > \App\Helpers\Constants::ORDER_STATUS_NEW) ? 'active' : ''}}">
                                            <div class="tl-dot b-primary"></div>
                                            <div class="tl-content">
                                                <div class="">{{ trans('fotober.order.status_2') }}</div>
                                            </div>
                                        </div>
                                        <div class="tl-item {{ ($order->status > \App\Helpers\Constants::ORDER_STATUS_PENDING) ? 'active' : ''}}">
                                            <div class="tl-dot b-primary"></div>
                                            <div class="tl-content">
                                                <div class="">{{ trans('fotober.order.status_3') }}</div>
                                            </div>
                                        </div>
                                        <div class="tl-item {{ ($order->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED) ? 'active' : ''}}">
                                            <div class="tl-dot b-primary"></div>
                                            <div class="tl-content">
                                                <div class="">{{ trans('fotober.order.status_8') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.instruction') }}</h6>
                                    <p class="font-weight-bold p-12">{{ (($order->notes) ? $order->notes : '') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="ajax_list"></div>
        {{-- <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
        <input type="hidden" name="customer_id" id="customer_id" value="{{ $order->customer->id }}"> --}}
        <div class="col-md-12 form-custom-mobile">
            <div class="card box-shadow border-r10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="box-vertical">
                                <div class="">
                                    <h4>Bill to <b>{{$customer->fullname}}</b></h4>
                                    <p class="mb-2">Phone: {{$customer->phone}}</p>
                                    <p>Address: {{$customer->address}}</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="email" name="email_paypal" id="email_paypal" placeholder="Email Paypal" class="form-control" value="{{ ($order->payment->email_paypal) ? $order->payment->email_paypal: $customer->email}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray" id="item_pay">
                                    <h4>Items</h4>
                                    @forelse ( $payment_detail as $key => $item)
                                    <div class="col-md-12 p-3 border-r10 mb-3" style="background: rgb(33 150 243 / 10%)">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="item name">Item Name</label>
                                                    <input type="text" name="item_name_{{$key+1}}" id="item_name_{{$key+1}}" value="{{$item['order_name']}}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="item name">Quantity</label>
                                                    <input type="number" min="1" name="quantity_{{$key+1}}" id="quantity_{{$key+1}}" onkeyup="calTotal()" value="{{$item['quantity']}}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="item name">Price</label>
                                                    <input type="number" min="1" onkeyup="calTotal()" name="price_{{$key+1}}" id="price_{{$key+1}}" value="{{$item['price']}}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="item name">Description</label>
                                                    <input type="text" name="description_{{$key+1}}" placeholder="Description (Optional)" id="description_{{$key+1}}" value="{{$item['description']}}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-md-12 p-3 border-r10 mb-3" style="background: #eee">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="item name">Item Name</label>
                                                    <input type="text" name="item_name_1" id="item_name_1" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="item name">Quantity</label>
                                                    <input type="number" min="1" name="quantity_1" id="quantity_1" onkeyup="calTotal()" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="item name">Price</label>
                                                    <input type="number" min="1" onkeyup="calTotal()" name="price_1" id="price_1" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="item name">Description</label>
                                                    <input type="text" name="description_1" id="description_1" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">+ Add items or service</a>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-right mb-0">Amount: <span style="font-size: 17px; font-weight: bold;" id="amount">${{$order->cost}}</span></p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label for="item name">Message to customer</label>
                                            <textarea name="note_sale" rows="4" id="note_sale" class="form-control" placeholder="Note to customer">{{$order->payment->note_sale}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="card box-shadow border-r10 card-form-total">
                                <div class="card-body">
                                    <div class="col-md-12 p-0">
                                        <div style="border: 1px solid #9b9b9b; padding: 0px 10px" class="border-r10">
                                            <p class="m-0">Subtotal</p>
                                            <p class="m-0" style="font-size: 17px; font-weight: bold" id="subtotal">${{$order->cost}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-0 mt-2">
                                        <div style="
                                            border: 1px solid #9b9b9b;
                                            padding: 0px 50px 0 10px;
                                            display: inline-block;
                                            position: relative;"
                                        class="border-r10">
                                            <sub>Invoice date</sub>
                                            <p class="mt-2 mb-1" style="font-size: 17px; font-weight: bold">
                                                {{date('d/m/Y H:i', strtotime($order->payment->created_at))}}
                                                <i class="ti-timer"
                                                style="
                                                    position: absolute;
                                                    top: 50%;
                                                    right: 15px;
                                                    transform: translateY(-50%);
                                                    font-size: 18px;"
                                            ></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col md-12" style="border: 1px dashed #cbd2d6; margin: 20px 0px;"></div>
                                    <div class="col-md-12">
                                        <div class="d-flex flex-column dis">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Subtotal</span>
                                                <span id="subtotalv1">${{$order->cost}}</span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Discount</span>
                                                <div class="input-group mb-3 pl-2">
                                                    <input type="number" onkeyup="changeSelect()" name="discount" id="discount" min="0" class="form-control" value="{{($order->discount_money > 0) ? $order->discount_money : $order->discount}}" style="max-width: 200px;">
                                                        <select class="selectpicker" onchange="changeSelect()" id="dvi" style="max-width: 50px;padding-left: 5px;padding-right: 5px;">
                                                            <option value="pre" {{($order->discount > 0) ? 'selected' : ''}}>%</option>
                                                            <option value="money" {{($order->discount_money > 0) ? 'selected' : ''}}>$</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="col md-12" style="border: 1px soild #eee"></div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="text-bold" style="font-size: 17px"><b>Total</b></span>
                                                <span class="fw-bold" style="font-size: 17px"><span class="fas fa-dollar-sign"></span><b id="totaldone">${{$order->total_payment}}</b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-3">
                        <button  class="btn btn-info px-4 py-2" style="border-radius: 25px;" id="update" onclick="SendInvoice({{ $order->id }})">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        @include('themes.cms.ace.pages.common.modal-order-info', [
            'modal_id' => 'order_input_modal',
            'modal_title' => trans('fotober.order.title_input'),
            'ajax_div_id' => 'ajax_show_input',
        ])
    </div>
    <div class="modal fade modal_order_info" tabindex="-1" role="dialog" aria-labelledby="vcenter" id="output_add_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="{{ isset($style1) ? $style1 : '' }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('fotober.output.add') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                    <div class="modal-body" style="padding-bottom: 2rem;">
                        <div>
                            <form class="form-horizontal" role="form" id="form-output"  enctype="multipart/form-data">
                                {{-- CSRF --}}
                                <div class="form-group m-0">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="order_id" id="order_id" value="{{  $order->id }}" class="form-control">
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$order->customer->id }}" class="form-control">
                                </div>
                                <div class="row">
                                    {{-- Tên yêu cầu --}}
                                    <div class="col-lg-6">
                                        <label class="control-label no-padding-right mb-2" for="link">
                                            {{ trans('fotober.output.link') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <br>
                                        
                                        <input type="file" id="avatar" name="avatar" class="form-control mb-3">
                                        @if ($errors->has('link'))<span class="validate-error">{{ $errors->first('link') }}</span>@endif
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="control-label no-padding-right mb-2" for="link">
                                            {{ trans('fotober.output.link') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <br>
                                        <select class="form-control" id="type" name="type" style="">
                                                <option value="IMAGE">IMAGE</option>
                                                <option value="VIDEO">VIDEO</option>
                                        </select>
                                        @if ($errors->has('type'))<span class="validate-error">{{ $errors->first('type') }}</span>@endif
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="control-label no-padding-right mb-2" for="notes">{{ trans('fotober.common.col_action') }}</label>
                                        <br>
                                        <button type="button" class="btn btn-primary" id="btn-add-output" style="width: 100%">
                                            {{ trans('fotober.common.btn_create') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('asset-bottom')
    <script type="text/javascript">
    
        $(document).ready(function (){
            
            $('#data-loading').hide();

            var url_ajax = '{{ route('sale_order_listing_ajax') }}';

            var oldTimeout = '';
            var order_id = $('#order_id').val();
            var customer_id = $('#customer_id').val();
            getDataByAjax(order_id, customer_id);
            
            /* Hàm thực hiện thêm item */
            $('#btn-add-output').click(function (){
                $('#data-loading').show();

                var order_id = $('#order_id').val();
                var customer_id = $('#customer_id').val();
                var link = document.getElementById('avatar').files[0];
                var type = $('#type').val();

                if (link) {
                    $('#btn-add-output').attr('disabled', true);
                    $('#spinner-update').show();

                    let data = new FormData();
                    data.append('avatar', link);
                    data.append('customer_id', customer_id);
                    data.append('order_id', order_id);
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('type', type);
                    console.log(link);
                    $.ajax({
                        url: '{{ route('sale_order_output_add') }}',
                        type: 'POST',
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(result) {
                            resetForm();

                            $('#data-loading').hide();
                            $('#spinner-update').hide();
                            $('#btn-add-output').attr('disabled', false);

                            if (result.code == 200) {
                                /* Cập nhật lại ds item */
                                getDataByAjax(result.data.order_id, result.data.customer_id);
                                $('#output_add_modal').modal('hide')
                            }
                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                            $('#data-loading').hide();
                            $('#spinner-update').hide();
                            $('#btn-add-output').attr('disabled', false);
                        }
                    });
                } else {
                    alert('Bạn vui lòng, nhập đầy đủ thông tin yêu cầu')
                }

            });

            // calTotal();
            // changeSelect();
        });

        /**
         * Show input
         * 
        */
        function showInput(order_id){
            $('#data-loading').show();
            $('#ajax_show_input').html('');

            $.ajax({
                url: '{{ route('sale_order_input_ajax') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    user_id: window.user.user_id,
                    account_type: window.user.account_type,
                },
                success: function(result) {
                    $('#data-loading').hide();
                    //
                    $('#ajax_show_input').html(result);
                    $('#order_input_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        /**
         * Hàm show form output
         *
         * @param order_id
         */
         function showOutput(order_id, customer_id){
                    $('#output_add_modal').modal();
        }
        
        /**
         * Show danh sách output
         * 
        */
        function getDataByAjax(order_id, customer_id) {
            $.ajax({
                url: '{{ route('sale_order_output_list_sumary') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    customer_id: customer_id
                },
                success: function(result) {
                    $('#ajax_list').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });
        }

        /**
         * Reset form
         * 
        */
        function resetForm() {
            $('#link').val('');
        }
        var row = '{{count($payment_detail)}}';
        row = parseInt(row);
        var row_arr = [1];
        for (let index = 1; index < row; index++) {
            row_arr.push(++index);
            
        }
        console.log(row_arr);
        /**
         * Thêm items
         * 
        */
        function addRow () {
            row = row + 1;
            row_arr.push(row);
            document.querySelector('#item_pay').insertAdjacentHTML(
                'beforeend',
                `
                <div class="col-md-12 p-3 border-r10 mb-3" style="background: rgb(33 150 243 / 10%)" id="row_`+row+`">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item name">Item Name</label>
                                <input type="text" name="item_name_`+row+`" id="item_name_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item name">Quantity</label>
                                <input type="number" min="1" onkeyup="calTotal()" name="quantity_`+row+`" id="quantity_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item name">Price</label>
                                <input type="number" min="1" onkeyup="calTotal()" name="price_`+row+`" id="price_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="item name">Description</label>
                                <input type="text" name="description_`+row+`" placeholder="Description (Optional)" id="description_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <a href="javascript:void(0)" onclick="removeRow(`+row+`)" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>`      
            )
            console.log(row_arr)
        }

        /**
         * Xóa items
         * 
        */
        function removeRow (input) {
            $("#row_"+input).remove();
            row_arr = row_arr.filter(item => item !== input);
            console.log(row_arr)
        }

        /**
         * Tính lại giá tiền
         * 
        */
        function calTotal(){
            if(row_arr.length > 0){
                let total = 0;
                for (let i = 0; i < row_arr.length; i++) {
                    total += $('#price_'+row_arr[i]).val()*$('#quantity_'+row_arr[i]).val();
                    console.log('price: ', $('#price_'+row_arr[i]).val());
                    console.log('quantity_: ', $('#quantity_'+row_arr[i]).val());
                }
                total = total.toFixed(2);
                console.log(total);
                document.getElementById("amount").innerHTML = '$'+total;
                document.getElementById("subtotal").innerHTML = '$'+total;
                document.getElementById("subtotalv1").innerHTML = '$'+total;
                changeSelect();
            }
        }

        /**
         * Thay đổi loại discount
        */
        function changeSelect() {
            var dvi = document.getElementById("dvi").value;
            let valDiscount = document.getElementById("discount").value;
            let discount = 0;
            let total = 0;
            for (let i = 0; i < row_arr.length; i++) {
                total += $('#price_'+row_arr[i]).val()*$('#quantity_'+row_arr[i]).val();
            }
            if(dvi == 'pre'){
                document.getElementById("discount").max = "100";
                total  = total - total*valDiscount/100;
            } else{
                document.getElementById("discount").max = "10000000";
                total  = total - valDiscount;
            }
            total = total.toFixed(2);
            console.log(total);
            document.getElementById("totaldone").innerHTML = '$'+total;
        }

        /**@argument
         * Gọi tạo hóa đơn thanh toán
         * 
        */
        function SendInvoice(order_id) {
            document.getElementById("update").disabled = true;
            let details = [];
            for (let index = 0; index < row_arr.length; index++) {
                let i = row_arr[index];
                let item = {
                    "description": $('#description_'+i).val(),
                    "quantity": $('#quantity_'+i).val(),
                    "item_name": $('#item_name_'+i).val(),
                    "price": $('#price_'+i).val()
                }
                details.push(item);
            }
            // console.log(typeof(details));
            let data_all = {
                "_token": '{{ csrf_token() }}',
                "order_id": order_id,
                "dvi": $('#dvi').val(),
                "discount": $('#discount').val(),
                "note_sale": $('#note_sale').val(),
                "email_paypal": $('#email_paypal').val(),
                "details": details
            };
            // console.log(data_all);
            $.ajax({
                url: '{{ route('sale_order_edit_invoice') }}',
                type: 'POST',
                dataType: 'json',
                data: data_all,
                success: function(result) {
                    document.getElementById("update").disabled = false;
                    if(result.code == 200){
                        location.href = '{{ route('sale_order_summary', ['id' => $order->id ]) }}';
                    } else{
                        alert(result.message);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    
                    console.log(errorMessage)
                }
            });
        }
    </script>
@endsection
