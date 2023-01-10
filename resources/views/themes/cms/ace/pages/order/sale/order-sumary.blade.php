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
        <!-- Title page -->
        <!-- ============================================================== -->
        <div class="page-titles" style="display: block;">
            <div class="align-self-center">
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
                <div class="col-md-12 ">
                    <div class="card box-shadow border-r10">
                        <div class="card-body" id="web-order">

                            <div class="row d-flex align-items-center no-block">
                                <div class="col col-lg-3">
                                        <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                                </div>
                                <div class="col col-lg-3">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.delivery') }}</h6>
                                </div>
                                <div class="col col-lg-3">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_status') }}</h6>
                                </div>
                                <div class="col col-lg-3">
                                        <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">Input</h6>
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
                                        @if (isset($order->notes))
                                        <a href="javascript:void(0)" class="font-weight-bold p-12 mb-5 pt-5px" onclick="showMore()" title="Click to Show Input"><p class="">Show more</p></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="mobile-order">

                            <div class="row d-flex no-block">
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                                    <p class="font-weight-bold p-12 mb-2 pt-5px">{{$order->code}}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.order_type') }}</h6>
                                    <p class="font-weight-bold mb-2 p-12">{{ $order->service->name }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.delivery') }}</h6>
                                    <p class="font-weight-bold p-12 mb-2 pt-5px">{{ $order->turn_arround_time }} {{ trans('fotober.order.hours') }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.order.order_submited') }}</h6>
                                    <p class="font-weight-bold mb-2 p-12">{{ (($order->sent_sale_at) ? date('d/m/Y H:i', strtotime($order->sent_sale_at)) : '') }}</p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">Input</h6>
                                    <a href="javascript:void(0)" class="font-weight-bold p-12 mb-2 pt-5px" onclick="showInput({{ $order->id }})" title="Click to Show Input"><p class="">{{ trans('fotober.order.view') }}</p></a>
                                </div>
                                <div class="col-md-12 mb-3">
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
                                    @if (isset($order->notes))
                                    <a href="javascript:void(0)" class="font-weight-bold p-12 mb-5 pt-5px" onclick="showMore()" title="Click to Show Input"><p class="">Show more</p></a>
                                    @endif
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
        @if (isset($order->payment) && $order->payment->link_payment)
        <div class="col-md-12">
            <div class="card box-shadow border-r10">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="box-vertical">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="logo mb-2">
                                            <img src="{{asset(\App\Helpers\Constants::DEFAULT_LOGO_INVOICE)}}" alt="" class="img-fluid" srcset="" style="max-height: 40px;">
                                        </div>
                                        <p class="mb-1">{{\App\Helpers\Constants::FIRST_NAME}} {{\App\Helpers\Constants::LAST_NAME}}</p>
                                        <p class="mb-0">{{\App\Helpers\Constants::COMPANY_NAME}}</p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p class="mb-1" style="font-weight: 500;">Invoice: {{$order->payment->paypal_id}}</p> 
                                        <p>Issued: {{date('d/m/Y H:i', strtotime($order->payment->created_at))}}</p> 
                                    </div>
                                </div>

                                <div class="text-right mb-4">
                                    <p class="mb-1" style="font-size: 17px;font-weight: 600;">${{$order->total_payment}}</p>
                                    <p class="mb-0" style="font-size: 90%;font-weight: 600;">@if ($order->payment->status == \App\Helpers\Constants::PAYMENT_STATUS_FALIED)
                                        Invoice cancelled
                                    @else
                                         {{($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID) ? 'Paid' : 'Pay'}}
                                    @endif</p>
                                </div>

                                <div class="bg-gray" style="background: #f6f6f6; border-radius: 4px;">
                                    <p class="m-0 p-2 d-flex align-items-center">
                                        <i class="ti-email mr-2"></i>
                                        <span>{{\App\Helpers\Constants::EMAIL}}</span>
                                    </p>
                                </div>

                                <div class="mt-4 pb-3" style="border-bottom: 1px solid #818182;">
                                    <h4><b>Bill to</b></h4>
                                    <p class="mb-0 font-weight-bold">{{$order->payment->email_paypal}}</p>
                                </div>

                                <div class="mt-3 pb-3" id="item_pay" style="border-bottom: 1px solid #818182;">
                                    <h4>Items</h4>
                                    @forelse ( $payment_detail as $item)
                                        <p class="d-flex justify-content-between mb-2">
                                            <span class="m-0"><b>{{$item['order_name']}}</b></span>
                                            <span><b>${{$item['amount']}}</b></span>
                                        </p>
                                        <p class="mb-0">{{$item['quantity']}} X ${{$item['price']}}</p>
                                    @empty
                                        <p class="mb-0">{{ trans('fotober.common.no_data') }}</p>
                                    @endforelse
                                </div>

                                <div class="mt-3 pb-3" style="border-bottom: 1px solid #818182;">
                                    <div class="row d-flex justify-content-end">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>Subtotal</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="float-right">${{$order->cost}}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>Discount</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="float-right">{{($order->discount > 0) ? $order->discount.'%' : '$'.$order->discount_money}}</p>
                                                </div>
                                            </div>
                                            <div class="col md-12" style="border: 1px dashed #eee; margin-bottom: 10px;"></div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><b>Total</b></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="float-right"><b>${{$order->total_payment}}</b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3" id="item_pay">
                                    <h4>Note to customer</h4>
                                    <p class="mb-0">{{$order->payment->note_sale}}</p>
                                </div>
                            </div>
                                    
                                    {{-- <div class="row" style="border: 1px solid #cbd2d6"></div> --}}
                                    
                                    {{-- <div class="row" style="border: 1px solid #cbd2d6"></div> --}}
                                    
                                    {{-- <div class="row" style="border: 1px solid #cbd2d6"></div> --}}
                                    
                                    {{-- <div class="row" style="border: 1px solid #cbd2d6"></div> --}}
                                {{-- </div>
                            </div> --}}
                        </div>
                        <div class="col-md-4" >
                            <div class="card box-shadow border-r10 card-form-total h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between pb-3 mb-3" style="font-size: 18px; border-bottom: 1px solid #818182;">
                                        <span>Số dư đến hạn: </span>
                                        <span><b>${{$order->total_payment}}</b></span>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        @if ($order->payment->status == \App\Helpers\Constants::PAYMENT_STATUS_FALIED)
                                            <button class=" btn btn-danger px-4 py-2" style="border-radius: 25px;">Invoice cancelled</button>
                                        @else
                                            <button class="btn btn-info px-4 py-2" style="border-radius: 25px;">{{($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID) ? 'Paid' : 'Pay'}} ${{$order->total_payment}}</button>
                                        @endif
                                    </div>
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" class="img-fluid" alt="Paypal" srcset="">
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($order->status != \App\Helpers\Constants::ORDER_STATUS_PAID && $order->payment->status != \App\Helpers\Constants::PAYMENT_STATUS_FALIED)
                        <div class="text-right mt-3">
                            <a href="{{ route('sale_order_summary_update', ['id' => $order->id ]) }}" class="btn btn-info px-4 py-2" style="border-radius: 25px;">Update Invoice</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="col-md-12 form-custom-mobile">
            <div class="card box-shadow border-r10 mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="card box-shadow border-r10 mb-0">
                                <div class="card-body p-0">
                                    <div class="">
                                        <h4>Bill to <b>{{$customer->fullname}}</b></h4>
                                        <p class="mb-2">Phone: {{$customer->phone}}</p>
                                        <p>Address: {{$customer->address}}</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="email" name="email_paypal" id="email_paypal" placeholder="Email Paypal" value="{{$customer->email}}"    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray" id="item_pay">
                                        <h4>Items</h4>
                                        <div class="col-md-12 border-r10 py-3 mb-3" style="background: rgb(33 150 243 / 10%)">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="item name">Item Name</label>
                                                        <input type="text" name="item_name_1" id="item_name_1" placeholder="Item name" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="item name">Quantity</label>
                                                        <input type="number" min="1" name="quantity_1" placeholder="Quantity" id="quantity_1" onkeyup="calTotal()" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="item name">Price</label>
                                                        <input type="number" min="1" onkeyup="calTotal()" placeholder="Price" name="price_1" id="price_1" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-0">
                                                        <label for="item name">Description</label>
                                                        <input type="text" name="description_1" placeholder="Description (Optional)" id="description_1" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">+ Add items or service</a>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-right mb-0">Amount: <span style="font-size: 17px; font-weight: bold;" id="amount"></span></p>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="item name">Message to customer</label>
                                        <textarea name="note_sale" id="note_sale" rows="4" class="form-control" placeholder="Note to customer"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card box-shadow border-r10 card-form-total">
                                <div class="card-body">
                                    <div class="col-md-12 p-0">
                                        <div style="border: 1px solid #9b9b9b; padding: 0px 10px" class="border-r10">
                                            <sub>Subtotal</sub>
                                            <p class="mt-2 mb-1" style="font-size: 17px; font-weight: bold" id="subtotal"></p>
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
                                            <p class="mt-2 mb-1" style="font-size: 17px; font-weight: bold">{{date('d/m/Y')}}</p>
                                            <i class="ti-timer"
                                                style="
                                                    position: absolute;
                                                    top: 50%;
                                                    right: 15px;
                                                    transform: translateY(-50%);
                                                    font-size: 18px;"
                                            ></i>
                                        </div>
                                    </div>
                                    <div class="col md-12" style="border: 1px dashed #cbd2d6; margin: 20px 0px;"></div>
                                    <div class="d-flex flex-column dis">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span>Subtotal</span>
                                            <span id="subtotalv1"></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span>Discount</span>
                                            <div class="input-group w-auto">
                                                <input type="number" onkeyup="changeSelect()" name="discount" id="discount" min="0" class="form-control" value="0" style="max-width: 200px;">
                                                <select class="selectpicker form-control" onchange="changeSelect()" id="dvi" style="max-width: 50px;padding-left: 5px;padding-right: 5px;">
                                                    <option value="pre">%</option>
                                                    <option value="money">$</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col md-12" style="border: 1px soild #eee"></div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-bold" style="font-size: 17px"><b>Total</b></span>
                                            <span class="fw-bold" style="font-size: 17px"><span class="fas fa-dollar-sign"></span><b id="totaldone"></b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <button class="btn btn-info px-4 py-2" id="created" onclick="SendInvoice({{ $order->id }})" style="border-radius: 15px;">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
                                        
                                        <input type="file" id="avatar" name="avatar[]" class="form-control mb-3" multiple data-max_length="10">
                                        <input type="text" id="link" name="link" class="form-control mb-3" style="display: none" data-max_length="10">
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="control-label no-padding-right mb-2" for="link">
                                            {{ trans('fotober.output.link') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <br>
                                        <select class="form-control" id="type" name="type" style="" onchange="changeType(this)">
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
    
    <div class="modal fade modal_order_info" tabindex="-1" role="dialog" aria-labelledby="vcenter" id="show_more_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="{{ isset($style1) ? $style1 : '' }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('fotober.order.instruction') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                    <div class="modal-body" style="padding-bottom: 2rem;">
                        <p>{{ (($order->notes) ? $order->notes : '') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade modal_order_video" tabindex="-1" role="dialog" aria-labelledby="vcenter" id="output_update_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="{{ isset($style1) ? $style1 : '' }}">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('fotober.output.add') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                    <div class="modal-body" style="padding-bottom: 2rem;">
                        <div>
                                {{-- CSRF --}}
                            <div class="form-group m-0">
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id_video" id="order_id_video" value="" class="form-control">
                                <input type="hidden" name="customer_id_video" id="customer_id" value="" class="form-control">
                                <input type="hidden" name="output_id_video" id="output_id_video" value="" class="form-control">
                            </div>
                            <div class="row">
                                {{-- Tên yêu cầu --}}
                                <div class="col-lg-9">
                                    <label class="control-label no-padding-right mb-2" for="link">
                                        {{ trans('fotober.output.link') }}&nbsp;(<span class="form-required"></span>)
                                    </label>
                                    <br>
                                    <input type="text" id="link_video" name="link_video" class="form-control mb-3" >
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label no-padding-right mb-2" for="notes">{{ trans('fotober.common.col_action') }}</label>
                                    <br>
                                    <button type="button" class="btn btn-primary" onclick="replaceImg(null, 'video')" style="width: 100%">
                                        {{ trans('fotober.common.btn_create') }}
                                    </button>
                                </div>
                            </div>
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
                var totallink = document.getElementById('avatar').files;
                var link = document.getElementById('link').value;
                var type = $('#type').val();

                if (totallink) {
                    $('#btn-add-output').attr('disabled', true);
                    $('#spinner-update').show();

                    let data = new FormData();
                    if(type == 'IMAGE'){
                        for (let index = 0; index < totallink.length; index++) {
                            data.append('avatar['+index+']', document.getElementById('avatar').files[index]);
                            console.log('file', document.getElementById('avatar').files[index]);
                        }
                        data.append('link', null);
                    } else {
                        data.append('link', link);
                        data.append('avatar', null);
                    }
                    data.append('customer_id', customer_id);
                    data.append('order_id', order_id);
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('type', type);
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
                                $.toast({
                                    heading: 'output',
                                    text: result.message,
                                    position: 'bottom-right',
                                    stack: 5,
                                    hideAfter: 15000,
                                    bgColor: '#017bcf',
                                    loaderBg: '#017bcf',
                                });
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
                    
                    $.toast({
                        heading: 'Ouput',
                        text: 'Bạn vui lòng, nhập đầy đủ thông tin yêu cầu',
                        position: 'bottom-right',
                        stack: 5,
                        hideAfter: 15000,
                        bgColor: '#a34335',
                        loaderBg: '#a34335',
                    });
                }

            });

            calTotal();
            changeSelect();
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
         * Hàm show form output
         *
         * @param order_id
         */
         function showOutputUpdate(order_id, customer_id, output_id){
            $('#output_update_modal').modal();
            console.log('order_id: ', order_id)
            console.log('customer_id: ', customer_id)
            console.log('output_id: ', output_id)
            $('#order_id_video').val(order_id);
            $('#customer_id_video').val(customer_id);
            $('#output_id_video').val(output_id);
        }

        function showMore(){
            $('#show_more_modal').modal();
        }

        function changeType(type){
            let typeval = type.value;
            if(typeval == 'IMAGE'){
                document.getElementById('link').style.display = "none";
                document.getElementById('avatar').style.display = "block";
            } else{
                document.getElementById('link').style.display = "block";
                document.getElementById('avatar').style.display = "none";
            }
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
                    console.log('a');
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
            $('#avatar').val('');
            $('#link').val('');
        }
        var row = 1;
        var row_arr = [1];

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
                <div class="col-md-12 border-r10 py-3 mb-3" style="background: rgb(33 150 243 / 10%)" id="row_`+row+`">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item name">Item Name</label>
                                <input type="text" name="item_name_`+row+`" placeholder="Item name" id="item_name_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item name">Quantity</label>
                                <input type="number" min="1" onkeyup="calTotal()" placeholder="Quantity" name="quantity_`+row+`" id="quantity_`+row+`" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item name">Price</label>
                                <input type="number" min="1" onkeyup="calTotal()" placeholder="Price" name="price_`+row+`" id="price_`+row+`" class="form-control">
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
                }
                total = total.toFixed(2);
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
            document.getElementById("totaldone").innerHTML = '$'+total;
        }

        /**@argument
         * Gọi tạo hóa đơn thanh toán
         * 
        */
        function SendInvoice(order_id) {
            document.getElementById("created").disabled = true;
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
            console.log(typeof(details));
            let data_all = {
                "_token": '{{ csrf_token() }}',
                "order_id": order_id,
                "dvi": $('#dvi').val(),
                "discount": $('#discount').val(),
                "note_sale": $('#note_sale').val(),
                "email_paypal": $('#email_paypal').val(),
                "details": details
            };
            console.log(data_all);
            $.ajax({
                url: '{{ route('sale_order_create_invoice') }}',
                type: 'POST',
                dataType: 'json',
                data: data_all,
                success: function(result) {
                    document.getElementById("created").disabled = false;
                    if(result.code == 200){
                        location.reload();
                    } else{
                        alert(result.message);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    
                    console.log(errorMessage)
                }
            });
        }

        /**
         * Sale đổi trạng thái hóa đơn thành đã thanh toán
         * @param order_id
         * @param sale_id
         */
         function changeStatus(order_id, status, mess = null){
            var con_firm = confirm(mess);
            if(con_firm == true){
                $.ajax({
                    url: '{{ route('sale_change_status_ajax') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: order_id,
                        status: status,
                    },
                    success: function(result) {
                        $('#data-loading').hide();
                        console.log(result);
                        if(result.code != 200){
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                    }
                });
            }
        }


        function openUpload(output_id){
            $('#file_replace_'+output_id).click();
        }

        /**
         * Tải lại ảnh output
         * 
        */
        function replaceImg(output_id, type_show){
            $('#data-loading').show();
            if(type_show == 'video'){
                var order_id = $('#order_id_video').val();
                var customer_id = $('#customer_id_video').val();
                var output_id_video = $('#output_id_video').val();
                var totallink = null;
                var link =  $('#link_video').val();
                var type = 'VIDEO';
            } else {
                var order_id = $('#order_id').val();
                var customer_id = $('#customer_id').val();
                var totallink = document.getElementById('file_replace_'+output_id).files[0];
                var type = $('#type').val();
            }

            if ((totallink && type_show == 'image' ) || (link && type_show == 'video')) {
                let data = new FormData();
                if(type_show == 'video'){
                    data.append('file_ouput', totallink);
                    data.append('link', link);
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('output_id', output_id_video);
                    data.append('type_update', 'reload');
                    data.append('type', type);
                } else{
                    data.append('file_ouput', totallink);
                    data.append('_token', '{{ csrf_token() }}');
                    data.append('output_id', output_id);
                    data.append('type_update', 'reload');
                    data.append('type', type);
                }
                console.log(data);
                $.ajax({
                    url: '{{ route('sale_order_output_update') }}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if(type_show == 'image'){
                            $('#file_replace_'+output_id).val('');
                        }
                        if (result.code == 200) {
                            /* Cập nhật lại ds item */
                            getDataByAjax(result.data.order_id, result.data.customer_id);
                            if(type_show == 'video'){
                                $('#output_update_modal').modal('hide');
                                $('#order_id_video').val() = '';
                                $('#customer_id_video').val() = '';
                                $('#output_id_video').val() = '';
                                $('#link_video').val() = '';
                                    
                                $.toast({
                                        heading: 'output',
                                        text: 'Tải lại video output thành công',
                                        position: 'bottom-right',
                                        stack: 5,
                                        hideAfter: 15000,
                                        bgColor: '#017bcf',
                                        loaderBg: '#017bcf',
                                    });
                            } else {
                                $.toast({
                                        heading: 'output',
                                        text: 'Tải lại ảnh output thành công',
                                        position: 'bottom-right',
                                        stack: 5,
                                        hideAfter: 15000,
                                        bgColor: '#017bcf',
                                        loaderBg: '#017bcf',
                                    });
                            }
                        } else{
                            
                            $.toast({
                                heading: 'Ouput',
                                text: '{{ trans('fotober.login.please_try_again_later') }}',
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#a34335',
                                loaderBg: '#a34335',
                            });
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        // $('#data-loading').hide();
                        // $('#spinner-update').hide();
                        // $('#btn-add-output').attr('disabled', false);
                    }
                });
            } else {
                
                $.toast({
                    heading: 'Ouput',
                    text: 'Bạn vui lòng, nhập đầy đủ thông tin yêu cầu',
                    position: 'bottom-right',
                    stack: 5,
                    hideAfter: 15000,
                    bgColor: '#a34335',
                    loaderBg: '#a34335',
                });
            }
        }

        //Xóa ảnh output
        function deleteOutput(id, order_id, customer_id){
            if (window.confirm("Bạn có chắc muốn xóa Output này không?")) {
                $.ajax({
                    url: '{{ route('sale_order_output_delete') }}',
                    type: 'POST',
                    dataType: 'HTML',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        order_id: order_id,
                        customer_id: customer_id
                    },
                    success: function(result) {
                        var result = JSON.parse(result);
                        if(result.code == 200){
                            getDataByAjax(result.data.order_id, result.data.customer_id);
                                $.toast({
                                    heading: 'output',
                                    text: result.message,
                                    position: 'bottom-right',
                                    stack: 5,
                                    hideAfter: 15000,
                                    bgColor: '#017bcf',
                                    loaderBg: '#017bcf',
                                });
                        } else{
                            $.toast({
                                heading: 'Ouput',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#a34335',
                                loaderBg: '#a34335',
                            });
                            $.toast({
                                heading: 'Ouput',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#a34335',
                                loaderBg: '#a34335',
                            });
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {}
                });
            }
            
        }
        function showItem(id){
            var video_show = document.getElementById('video_show');
            var image_show = document.getElementById('image_show');
            var btn_image = document.getElementById('btn-image');
            var btn_video = document.getElementById('btn-video');
            if(id == 'video_show'){
                video_show.style.display = 'block';
                image_show.style.display = 'none';
                btn_image.style.border = '1px solid #017bcf';

                btn_video.classList.remove('btn-light');
                btn_video.classList.remove('color-blue');
                btn_video.classList.add('btn-primary');

                btn_image.classList.add('btn-light');
                btn_image.classList.add('color-blue');
                btn_image.classList.remove('btn-primary');
            }

            if(id == 'image_show'){
                image_show.style.display = 'block';
                video_show.style.display = 'none';
                // video_show.style.border = '1px solid #017bcf';

                btn_image.classList.remove('btn-light');
                btn_image.classList.remove('color-blue');
                btn_image.classList.add('btn-primary');

                btn_video.classList.add('btn-light');
                btn_video.classList.add('color-blue');
                btn_video.classList.remove('btn-primary');
            }
        }
    </script>
@endsection
