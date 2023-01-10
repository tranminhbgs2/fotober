@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sidebar.dashboard') }}
@endsection
@section('content')
    {{-- Summary --}}
    <div class="row" style="margin-top: 25px">
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
                            <i class="ace-icon fa fa-repeat"></i>
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
                            <i class="ace-icon fa fa-flash"></i>
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
                <!-- edited-order-number: 4 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-blue2" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-image"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Edited</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="edited-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- checking-order-number: 5 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-red" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-check"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Checking</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="checking-order-number">
                                @include('themes.cms.ace.layouts.common.loading-no-id')
                            </span>
                        </div>
                    </div>
                </div>
                <!-- checked-order-number: 6 -->
                <div class="col-lg-4 col-md-4 col-sm-12 mobile-width-100">
                    <div class="infobox infobox-orange2" style="width: 100%">
                        <div class="infobox-icon">
                            <i class="ace-icon fa fa-refresh"></i>
                        </div>
                        <div class="infobox-data" style="min-width: auto">
                            <span class="infobox-data-number">Today</span>
                            <div class="infobox-content">Re-do</div>
                        </div>
                        <div class="infobox-data" style="float: right; min-width: auto">
                            <span class="infobox-data-number dashboard-summary-number" id="checked-order-number">
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
    </div>
    {{-- Order list --}}
    <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12 text-center mb-1" style="position: absolute; top: -50px; right: 0px">
            @include('themes.cms.ace.layouts.common.loading-by-id')
        </div>
    </div>
    <!-- <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12 mobile-width-100">
            <h4 style="line-height: 18px">
                {{ trans('fotober.dashboard.title_new_list') }}
                <span>
                    <a href="{{ route('sale_order') }}" style="float: right; font-size: 13px">
                        {{ trans('fotober.common.show_all') }}
                    </a>
                </span>
            </h4>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="ajax_list_left" style="max-height: 500px; overflow-y: scroll"></div>
    </div>-->
    <div class="row" style="margin-top: 25px">
        <div class="col-lg-12 col-md-12 col-sm-12 mobile-width-100">
            <h4 style="line-height: 18px">
                {{ trans('fotober.dashboard.title_deadline_list') }}
                <span>
                    <a href="{{ route('superadmin_listing_order') }}" style="float: right; font-size: 13px">
                        {{ trans('fotober.common.show_all') }}
                    </a>
                </span>
            </h4>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" id="ajax_list_right"  style="max-height: 500px; overflow-y: scroll"></div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function(){
            getSummaryByAjax();
            getOrderByAjax('', 1);
        });

        function getSummaryByAjax() {
            var data = {
                _token: '{{ csrf_token() }}',
                group: 'SUPER_ADMIN'
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
                        $('#edited-order-number').html(stt_4);
                        $('#checking-order-number').html(stt_5);
                        $('#checked-order-number').html(stt_6);
                        $('#completed-order-number').html(stt_8);
                        $('#re-do-order-number').html(stt_9);

                        $('#total-order-number').html(total);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {}
            });
        }

        function getOrderByAjax(url_ajax, page_index) {
            $('#data-loading').show();

            var data = {
                _token: '{{ csrf_token() }}',
                group: 'SUPER_ADMIN'
            };

            $.ajax({
                url: '{{ route('dashboard_new_order_ajax') }}',
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
                url: '{{ route('dashboard_deadline_order_ajax') }}',
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
