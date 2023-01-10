@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.detail_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles" style="display: none;">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.order.detail_title') }}</h4>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row" style="display: none;">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="block-info">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-uppercase font-weight-500" style="min-width: 150px;">{{ trans('fotober.order.invoice') }}</td>
                                        <td class="text-info" style="min-width: 150px;">{{isset($order->payment->paypal_id) ? $order->payment->paypal_id : trans('fotober.order.processing') }}</td>
                                        <td class="text-uppercase font-weight-500">{{ trans('fotober.order.form_service_type') }}</td>
                                        <td class="text-info" style="min-width: 150px;">{{ $order->service->name }}</td>
                                        <td class="text-uppercase font-weight-500">Input</td>
                                        <td class="text-info"><a href="javascript:void(0)" onclick="showInput({{ $order->id }})" title="Click to Show Input"> Show </a></td>
                                        <td class="text-uppercase font-weight-500" style="min-width: 150px;">{{ trans('fotober.order.cost') }}</td>
                                        <td class="text-info">{{isset($order->payment) ? $order->payment->amount.'$' : trans('fotober.order.processing')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-uppercase font-weight-500">{{ trans('fotober.order.form_name') }}</td>
                                        <td class="text-info">{{ $order->name }}</td>
                                        <td class="text-uppercase font-weight-500">{{ trans('fotober.order.form_deadline') }}</td>
                                        <td class="text-info" style="min-width: 170px;">{{date("d/m/Y H:i A",strtotime($order->deadline))}}</td>
                                        <td class="text-uppercase font-weight-500">{{ trans('fotober.common.col_status') }}</td>
                                        <td class="text-info">{{ getOrderStatus($order->status) }}</td>
                                        <td class="text-uppercase font-weight-500">Output</td>
                                        <td class="text-info">
                                            @if (isset($order->output) && in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
                                                <a href="javascript:void(0)" onclick="showOutput({{ $order->id }})" title="Click to Show Output">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                </a>
                                            @else
                                            {{ trans('fotober.order.processing') }}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 25px;">
        <div class="col-12">
            <div class="card m-b-0" style="background: transparent;">
                <!-- .chat-row -->
                <div class="chat-main-box">
                    <!-- .chat-left-panel -->
                    <div class="chat-left-aside">
                        <div class="chat-main-header">
                            @if ($name_sale)
                            <div class="header-avatar">
                                <div class="image">
                                    <img src="{{ $sale_avatar }}" alt="sale avatar" class="">
                                    <span class="badge-dot"></span>
                                </div>
                                <h4 class="box-title mb-0">
                                    <strong style="line-height: 32px;">{{$name_sale}}</strong>
                                    @if (isset($order->payment) && in_array($order->status,[\App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT]))
                                        <span style="float: right;">
                                            <a href="{{$order->payment->link_payment}}" title="Click to Show Invoice" target="_blank" class="btn btn-sm btn-primary">
                                                {{ trans('fotober.order.invoice_paypal') }}
                                            </a>
                                        </span>
                                    @endif
                                    <span class="stt">Active</span>
                                </h4>
                            </div>
                            @else
                                <h4 class="box-title mb-0"><strong>Chat Message</strong></h4>
                            @endif
                            <a href="javascript:void(0)" class="menu-chat d-md-none">
                                <i class="ti-menu"></i>
                            </a>
                            <a href="javascript:void(0)" class="nav-menu-chat" id="nav-menu-collapse">
                                View order detail
                                <i class="ti-angle-down"></i>
                            </a>
                            <div id="nav-content-collapse">
                                <table class="w-100">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="box-form mb-5">
                                                    <span class="title">Order Id</span>
                                                    <span class="text">{{ $order->code }}</span>
                                                </div>
                                                <div class="box-form">
                                                    <span class="title">Order Type</span>
                                                    <span class="text">{{ $order->service->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="box-form mb-5">
                                                    <span class="title">Delivery Time</span>
                                                    <span class="text">{{date("d/m/Y H:i A",strtotime($order->deadline))}}</span>
                                                </div>
                                                <div class="box-form">
                                                    <span class="title">Order Submitted</span>
                                                     <span class="text">{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</span>
                                                </div>
                                            </td>
                                            <!-- Status -->
                                            <td>
                                                <div class="box-form">
                                                    <span class="title">Status</span>
                                                    {!! getOrderStatusItem($order->status) !!}
                                                </div>
                                            </td>
                                            <td>

                                                <!-- Input -->
                                                <div class="box-form">
                                                    <span class="title">Input</span>
                                                    <div class="content">
                                                        <a href="javascript:void(0)" onclick="showInput({{ $order->id }})" title="Click to Show Input"> View </a>
                                                    </div>
                                                </div>

                                                {{-- Total --}}
                                                <div class="box-form">
                                                    <span class="title">Total</span>
                                                    <div class="content">
                                                        <span class="text" style="color: #017BCF;">
                                                            {{isset($order->payment) ? $order->payment->amount.'$' : trans('fotober.order.processing')}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>

                                                <!-- Output -->
                                                <div class="box-form">
                                                    <span class="title">Output</span>
                                                    <span class="text" style="color: #017BCF;">
                                                        @if (isset($order->output) && in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
                                                            <a href="javascript:void(0)" onclick="showOutput({{ $order->id }})" title="Click to Show Output">
                                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                            </a>
                                                        @else
                                                        {{ trans('fotober.order.processing') }}
                                                        @endif
                                                    </span>
                                                </div>

                                                {{-- Invoice --}}
                                                <div class="box-form">
                                                    <span class="title">Invoice</span>
                                                    <span class="text" style="color: #017BCF;"> {{isset($order->payment->paypal_id) ? $order->payment->paypal_id : trans('fotober.order.processing') }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="chat-rbox" id="chat-content">
                            <ul class="chat-list p-3" id="content-mess-{{$order->id}}">
                                @forelse($messages as $item)
                                    @if ($item['customer_id'])
                                        <li class="reverse">
                                            <div class="chat-content cus">
                                                {{-- <h5>{{ $name_cus }}</h5> --}}
                                                <div class="box bg-light-inverse">
                                                    @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_IMAGE)
                                                        <a href="{{ asset('storage/'.$item['content']) }}" data-lightbox="chat-lightbox-{{ $order->id }}" data-title="Preview">
                                                            <img class="img-chat" src="{{asset('storage/'.$item['content'])}}" style="max-width: 50px; max-height: 50px">
                                                        </a>
                                                    @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_FILE)
                                                        <a href="{{asset('storage/'.$item['content'])}}" target="_blank">{{$item['file_name']}}</a>
                                                    @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_LINK)
                                                        <a href="{{$item['content']}}}}" target="_blank">{{$item['content']}}</a>
                                                    @else
                                                        {{$item['content']}}
                                                    @endif
                                                </div>
                                                {{-- <div class="chat-time">{{ date("H:i a", strtotime($item['created_at'])) }}</div> --}}
                                            </div>
                                            <div class="chat-img">
                                                <img src="{{ $cus_avatar }}" alt="user">
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <div class="chat-img">
                                                <img class="avatar" src="{{ $sale_avatar }}" alt="...">
                                            </div>
                                            <div class="chat-content sale">
                                                {{-- <h5>{{ $name_sale }}</h5> --}}
                                                <div class="box bg-light-info">
                                                    @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_IMAGE)
                                                        <a href="{{ asset('storage/'.$item['content']) }}" data-lightbox="chat-lightbox-{{ $order->id }}" data-title="Preview">
                                                            <img class="img-chat sale" src="{{asset('storage/'.$item['content'])}}" style="max-width: 50px; max-height: 50px">
                                                        </a>
                                                    @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_FILE)
                                                        <a href="{{asset('storage/'.$item['content'])}}" target="_blank">{{$item['file_name']}}</a>
                                                    @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_LINK)
                                                        <a href="{{$item['content']}}}}" target="_blank">{{$item['content']}}</a>
                                                    @else
                                                        {{$item['content']}}
                                                    @endif
                                                </div>
                                                {{-- <div class="chat-time">{{ date("H:i a", strtotime($item['created_at'])) }}</div> --}}
                                            </div>
                                        </li>
                                    @endif
                                @empty
                                    <div class="media media-meta-day">{{ trans('fotober.order.no_message') }}</div>
                                @endforelse
                            </ul>
                        </div>
                        <div class="card-body border-top chat-control-abs">
                            <div class="row">
                                <div class="col-12 col-lg-3 text-right d-flex align-items-center justify-content-start">
                                    <div class="list-btn-chat">
                                        {{-- <span class="file-group">
                                            <i class="ti-face-smile"></i>
                                        </span> --}}
                                        <span class="d-inline-block">
                                            <button type="button" data-toggle="modal" class="btn btn-light" data-target="#showLink" id="on_show_link">
                                                {{-- <i class="ti-link"></i> --}}
                                                <img src="{{ asset('images/link.png') }}" style="max-width: 20px;" alt="">
                                            </button>
                                        </span>
                                        <span class="d-inline-block">
                                            <form id='send_doc' enctype="multipart/form-data">
                                                <input id="fileDoc" type="file" accept=".doc,.docx,.pdf" hidden />
                                                <button id="buttonDoc" type="button" class="btn btn-light">
                                                    {{-- <i class="ti-id-badge"></i> --}}
                                                    <img src="{{ asset('images/folder.png') }}" style="max-width: 20px;" alt="">
                                                </button>
                                                <input type='submit' value='Submit' hidden/>
                                            </form>
                                        </span>
                                        <span class="d-inline-block">
                                            <form id='send_image' name="send_image" enctype="multipart/form-data">
                                                <input id="fileCamera" name="fileCamera" type="file" accept="image/*" hidden />
                                                <button id="buttonCamera" class="btn btn-light" type="button">
                                                    {{-- <i class="ti-image"></i> --}}
                                                    <img src="{{ asset('images/image.png') }}" style="max-width: 20px;" alt="">
                                                </button>
                                                <input type='submit' id="img_submit" hidden/>
                                            </form>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-9">
                                    <form id="message_from" class="d-flex">
                                        <input class="publisher-input mr-3 p-2" type="text" placeholder="Write something" name="message" id="mess_input">
                                        <input type="hidden" name="type_send" id="type-send" value="customer">
                                        <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
                                        <button type="submit" class="btn btn-info btn-md" id="message_send" data-abc="true">Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .chat-right-panel -->
                    <div class="chat-right-aside">
                        <div class="open-panel"><i class="ti-angle-right"></i></div>
                        <div class="chat-right-inner">
                            <div id="accordian-3">
                                <div class="card card-image">
                                    <a class="card-header d-flex align-items-center" href="javascript:void(0)" id="heading11" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        <h5 class="mb-0 font-weight-500">Photo/Video</h5>
                                        <i class="ti-angle-down"></i>
                                    </a>
                                    <div id="collapse1" class="collapse show" aria-labelledby="heading11">
                                        <div class="card-body">
                                            <div class="row">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @forelse($file_messages as $item)
                                                @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_IMAGE)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="col-3">
                                                    <a href="{{asset('storage/'.$item['content'])}}" title="Photo Title" target="_blank" data-rel="colorbox" class="cboxElement">
                                                        <img class="img-media" alt="150x150" src="{{asset('storage/'.$item['content'])}}">
                                                    </a>
                                                </div>
                                                @endif
                                                @empty
                                                @endforelse
                                                @if ($i == 0)
                                                <div class="col-12">
                                                    <div class="text-center mb-3">{{ trans('fotober.order.no_media') }}</div>
                                                </div>
                                                @endif
                                                <div class="col-12">
                                                    <button class="btn btn-link">See all</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript" src="{{ asset('libs/lightbox2/js/lightbox-plus-jquery.min.js') }}">
                                        $(document).ready(function (){
                                            lightbox.option({
                                                'resizeDuration': 200,
                                                'wrapAround': true
                                            })
                                        })
                                    </script>
                                </div>
                                <div class="card card-files">
                                    <a class="card-header d-flex align-items-center" href="javascript:void(0)" id="heading22" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                        <h5 class="mb-0 font-weight-500">Files</h5>
                                        <i class="ti-angle-down"></i>
                                    </a>
                                    <div id="collapse2" class="collapse show" aria-labelledby="heading22">
                                        <div class="card-body">
                                            @php
                                                $i=0;
                                            @endphp
                                            @forelse($file_messages as $item)
                                                @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_FILE)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="box-file">
                                                    <div class="icon">
                                                        <i class="ti-file"></i>
                                                    </div>
                                                    <div class="content">
                                                        <h4 class="title">
                                                            <a class="user" href="{{asset('storage/'.$item['content'])}}" target="_blank">{{$item['file_name']}}</a>
                                                        </h4>
                                                        <span class="size">455.5KB</span>
                                                    </div>
                                                    <span class="day">{{date('d/m', strtotime($item['created_at']))}}</span>
                                                </div>
                                                @endif
                                            @empty
                                            @endforelse
                                            @if ($i == 0)
                                                <div class="text-center mb-3">{{ trans('fotober.order.no_file') }}</div>
                                            @endif

                                            <div class="d-block">
                                                <button class="btn btn-link">See all</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <a class="card-header d-flex align-items-center" href="javascript:void(0)" id="heading33" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                        <h5 class="mb-0 font-weight-500">Links</h5>
                                        <i class="ti-angle-down"></i>
                                    </a>
                                    <div id="collapse3" class="collapse show" aria-labelledby="heading33">
                                        <div class="card-body">
                                            @php
                                                $i=0;
                                            @endphp
                                            @forelse($file_messages as $item)
                                                @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_LINK)
                                                @php
                                                    $i++;
                                                @endphp
                                                <div class="box-file">
                                                    <div class="icon">
                                                        <i class="ti-link"></i>
                                                    </div>
                                                    <div class="content">
                                                        <h4 class="title">
                                                            <a class="user" href="{{asset('storage/'.$item['content'])}}" target="_blank" title="{{$item['content']}}">{{substr($item['content'], 0, 25)}}</a>
                                                        </h4>
                                                        <span class="size">
                                                            <a class="user" href="{{asset('storage/'.$item['content'])}}" target="_blank" title="{{$item['content']}}">{{substr($item['content'], 0, 25)}}</a>
                                                        </span>
                                                    </div>
                                                    <span class="day">{{date('d/m', strtotime($item['created_at']))}}</span>
                                                </div>
                                                @endif
                                                @empty
                                            @endforelse
                                            @if ($i == 0)
                                                <div class="text-center mb-3">{{ trans('fotober.order.no_link') }}</div>
                                            @endif

                                            <div class="d-block">
                                                <button class="btn btn-link">See all</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="showLink" tabindex="-1" role="dialog"        aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">{{ trans('fotober.order.link') }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                          <label for="exampleInputEmail1">{{ trans('fotober.order.link') }}</label>
                          <input type="text" class="form-control" id="link" aria-describedby="emailHelp" placeholder="{{ trans('fotober.order.enter_link') }}" required>
                        </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('fotober.common.close') }}</button>
                  <button type="button" id="submit_link" class="btn btn-primary">{{ trans('fotober.common.send') }}</button>
                </div>
              </div>
            </div>
        </div>
        <script src="{{ mix('js/chat.js') }}"></script>
    </div>

    <div class="col-lg-12">
        @include('themes.cms.ace.pages.common.modal-order-info', [
            'modal_id' => 'order_input_modal',
            'modal_title' => trans('fotober.order.title_input'),
            'ajax_div_id' => 'ajax_show_input',
        ])
    </div>

    <div class="col-lg-12">
        @include('themes.cms.ace.pages.common.modal-order-info', [
            'modal_id' => 'order_output_modal',
            'modal_title' => trans('fotober.order.title_output'),
            'ajax_div_id' => 'ajax_show_output',
        ])
    </div>
@endsection
@section('asset-header')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/lightbox2/css/lightbox.min.css') }}"/>
@endsection
@section('asset-bottom')
    <script type="text/javascript">

        document.getElementById('buttonCamera').addEventListener('click', function (){
            document.getElementById('fileCamera').click();
        });
        document.getElementById('buttonDoc').addEventListener('click', function (){
            document.getElementById('fileDoc').click();
        });

        function showInput(order_id){
            $('#data-loading').show();
            $('#ajax_show_input').html('');

            $.ajax({
                url: '{{ route('customer_order_input_ajax') }}',
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
                    $('#order_input_modal').modal('show');
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }
        function showOutput(order_id){
            $('#data-loading').show();
            $('#ajax_show_output').html('');

            $.ajax({
                url: '{{ route('customer_order_output_ajax') }}',
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
                    $('#ajax_show_output').html(result);
                    $('#order_output_modal').modal('show');
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        $(function() {

            "use strict";

            $('.chat-left-inner > .chatonline, .chat-rbox').perfectScrollbar();

            var cht = function() {
                var topOffset = 320;
                var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
                height = height - topOffset;
                $(".chat-list").css("height", (height) + "px");
            };
            $(window).ready(cht);
            $(window).on("resize", cht);

            // // this is for the left-aside-fix in content area with scroll
            // var chtin = function() {
            //     var topOffset = 270;
            //     var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
            //     height = height - topOffset;
            //     $(".chat-left-inner").css("height", (height) + "px");
            // };
            // $(window).ready(chtin);
            // $(window).on("resize", chtin);

            $(".open-panel").on("click", function() {
                $(".chat-left-aside").toggleClass("open-pnl");
                $(".open-panel i").toggleClass("ti-angle-left");
            });
        });
        $('.menu-chat').on('click', function(e) {
            $(this).toggleClass('active');
            $('.chat-right-aside').toggleClass("show"); //you can list several class names
            e.preventDefault();
        });
        $('#nav-menu-collapse').on('click', function(e) {
            $(this).toggleClass('active');
            $('#nav-content-collapse').toggleClass("show"); //you can list several class names
            e.preventDefault();
        });
    </script>
@endsection
