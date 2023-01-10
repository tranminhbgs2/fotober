@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.kpi.title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.kpi.title') }}</h4>
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
                        @include('themes.cms.ace.layouts.common.alert-message')
                        @include('themes.cms.ace.layouts.common.flat-session-info')
                    </div>
                    <div class="d-flex align-items-center no-block filter-nav-page @if(\Illuminate\Support\Facades\Auth::user()->is_admin) admin @endif">
                        <div class="form-group mr-3 custom-search-icon mb-w-100">
                            <i class="ti ti-search"></i>
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="{{ trans('fotober.common.search') }}">
                        </div>
                        
                        @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                            <div class="form-group mr-3 mb-w-50">
                                <select class="form-control custom-select" name="sale_id" id="sale_id">
                                    <option value="-1">{{ trans('fotober.common._select_sale_') }}</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->id }}">{{ $sale->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group mr-3 mb-w-50 @if(\Illuminate\Support\Facades\Auth::user()->is_admin) mb-r-0 @endif">
                            <select class="form-control custom-select" name="customer_id" id="customer_id">
                                <option value="-1">{{ trans('fotober.common._select_customer_') }}</option>
                                @foreach($customers as $item)
                                    <option value="{{ $item->id }}">{{ $item->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-w-50">
                            <select class="form-control custom-select" name="service_id" id="service_id">
                                <option value="-1">{{ trans('fotober.common._select_service_') }}</option>
                                @foreach($services as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-block text-center">
                        @include('themes.cms.ace.layouts.common.loading-by-id')
                    </div>
                    <div id="ajax_list"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('themes.cms.ace.pages.common.modal-order-info', [
                'modal_id' => 'order_info_modal',
                'modal_title' => trans('fotober.order.info_title'),
                'ajax_div_id' => 'ajax_show_info',
            ])
        </div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = '{{ route('sale_kpi_listing_ajax') }}';

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });
            if(window.user.is_admin == 1){
            filterBySelectBox('sale_id', url_ajax);
            }
            filterBySelectBox('customer_id', url_ajax);
            filterBySelectBox('service_id', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = '{{ route('sale_kpi_listing_ajax') }}';
            getDataByAjax(url_ajax, page_index);
        }

        function filterBySelectBox(id, url_ajax){
            $('#'+id).change(function(){
                getDataByAjax(url_ajax, 1);
            });
        }

        function getDataByAjax(url_ajax, page_index, start_date=null, end_date=null) {
            $('#data-loading').show();

            var keyword = $('#keyword').val();

            if(window.user.is_admin == 1){
                var sale_id = $('#sale_id').val();
            }
            var customer_id = $('#customer_id').val();
            var service_id = $('#service_id').val();
            var page_size = 10;

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
                start_date: start_date,
                end_date: end_date,
                customer_id: customer_id,
                service_id: service_id,
            };
            if(window.user.is_admin == 1){
                data.sale_id = sale_id;
            }

            $.ajax({
                url: url_ajax,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#data-loading').hide();
                    $('#ajax_list').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        /* Hàm show thông tin nhanh qua popup/modal */
        function showInfo(order_id){
            $('#data-loading').show();
            $('#ajax_show_info').html('');

            $.ajax({
                url: '{{ route('sale_order_info_ajax') }}',
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
                    $('#ajax_show_info').html(result);
                    $('#order_info_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

    </script>
@endsection
