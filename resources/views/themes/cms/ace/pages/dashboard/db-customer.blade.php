@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sidebar.dashboard') }}
@endsection
@section('asset-header')
    {{--<script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>--}}
@endsection
@section('content')
    {{-- Event --}}
    {{--<div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12"><h4>{{ trans('fotober.dashboard.title_event') }}</h4></div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <spn>Coming soon...</spn>
            <div class="events">
                <div><img src="{{ asset('uploads/sld1.png') }}"></div>
                <div><img src="{{ asset('uploads/sld1.png') }}"></div>
                <div><img src="{{ asset('uploads/sld1.png') }}"></div>
            </div>
        </div>
    </div>--}}
    {{-- Summary --}}
    {{-- <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12"><h4>{{ trans('fotober.dashboard.title_sum') }}</h4></div>
        <div class="col-lg-12 col-md-12 col-sm-12 infobox-container">
            <div class="row">
                <!-- new-order-number: 1 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-green" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">New Order</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="new-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- pending-order-number: 2 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-blue" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Pending</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="pending-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- editing-order-number: 3 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-pink" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Editing</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="editing-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 25px">
                <!-- completed-order-number: 7 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-red" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Completed</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="completed-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- re-do-order-number: 8 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-orange2" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Re-do</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="re-do-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- total-order-number -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-blue2" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-shopping-cart"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Total</span>
                            <div class="infobox-content">Orders</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="total-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- Service --}}
    {{-- <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: -10px"><h4>{{ trans('fotober.dashboard.title_service') }}</h4></div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="ajax_service_list"></div>
    </div> --}}
    {{-- Order list --}}
    {{-- <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12 text-center mb-1" style="position: absolute; top: -50px; right: 0px">
            @include('themes.cms.ace.layouts.common.loading-by-id')
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mobile-width-100">
                    <h4 style="line-height: 18px">
                        {{ trans('fotober.dashboard.title_draft_list') }}
                        <span>
                            <a href="{{ route('customer_order') }}" style="float: right; font-size: 13px">
                                {{ trans('fotober.common.show_all') }}
                            </a>
                        </span>
                    </h4>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" id="ajax_list_left" style="max-height: 500px; overflow-y: scroll"></div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mobile-width-100">
                    <h4 style="line-height: 18px">
                        {{ trans('fotober.dashboard.title_recent_list') }}
                        <span>
                            <a href="{{ route('customer_order') }}" style="float: right; font-size: 13px">
                                {{ trans('fotober.common.show_all') }}
                            </a>
                        </span>
                    </h4>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12" id="ajax_list_right"  style="max-height: 500px; overflow-y: scroll"></div>
            </div>
        </div>
    </div> --}}
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.events').slick({
                //rtl: true
                //infinite: true,
                //slidesToShow: 2,
                //slidesToScroll: 3
            });

            getSummaryByAjax();
            getServiceByAjax();
            getOrderByAjax('', 1);
        });

        function getSummaryByAjax() {
            var data = {
                _token: '{{ csrf_token() }}',
                group: 'CUSTOMER'
            };

            $.ajax({
                url: window.ajax_url.summary_order,
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(result) {
                    if (result) {
                        var stt_0 = stt_1 = stt_2 = stt_3 = stt_4 = stt_5 = stt_6 = stt_7 = stt_8 = stt_9 = 0;
                        var total = 0;
                        result.forEach((item, index) => {
                            total += item.total;
                            switch (item.status) {
                                case 0: stt_0 = item.total; break;
                                case 1: stt_1 = item.total; break;
                                case 2: stt_2 = item.total; break;
                                case 3: stt_3 = item.total; break;
                                case 4: stt_4 = item.total; break;
                                case 5: stt_5 = item.total; break;
                                case 6: stt_6 = item.total; break;
                                case 7: stt_7 = item.total; break;
                                case 8: stt_8 = item.total; break;
                                case 9: stt_9 = item.total; break;
                                //
                            }
                        })

                        $('#new-order-number').html(stt_1);
                        $('#pending-order-number').html(stt_2);
                        $('#editing-order-number').html(stt_3);
                        $('#completed-order-number').html(stt_8);
                        $('#re-do-order-number').html(stt_9);

                        $('#total-order-number').html(total);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });
        }

        function getServiceByAjax() {
            var data = {
                _token: '{{ csrf_token() }}',
                keyword: '',
                page_index: 1,
                page_size: 10,
                status: -1,
                group: 'CUSTOMER'
            };

            $.ajax({
                url: window.ajax_url.service_list,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#ajax_service_list').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });
        }

        function getOrderByAjax(url_ajax, page_index) {
            $('#data-loading').show();

            var data = {
                _token: '{{ csrf_token() }}',
                status: 0,
                group: 'CUSTOMER'
            };

            $.ajax({
                url: window.ajax_url.draft_order_list,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#data-loading').hide();
                    $('#ajax_list_left').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });

            $.ajax({
                url: window.ajax_url.recent_order_list,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#data-loading').hide();
                    $('#ajax_list_right').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });
        }
    </script>
@endsection
