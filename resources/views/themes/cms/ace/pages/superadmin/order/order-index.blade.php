@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.order.title') }}</h4>
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
                    <div class="d-flex align-items-center no-block filter-nav-page">
                        <div class="form-group mr-3 custom-search-icon mb-w-100 mb-r-0">
                            <i class="ti ti-search"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="{{ trans('fotober.common.search') }}">
                        </div>
                        <div class="form-group mb-w-50 mb-r-5 mr-3">
                            <select class="form-control custom-select" name="assigned_sale_id" id="assigned_sale_id">
                                <option value="-1">{{ trans('fotober.common._select_sale_') }}</option>
                                @foreach($sales as $sale)
                                    <option value="{{ $sale->id }}">{{ $sale->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-w-50 mb-r-0 mr-3">
                            <select class="form-control custom-select" name="status" id="status" style="width: 100%">
                                <option value="-1">{{ trans('fotober.common._status_') }}</option>
                                @foreach($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            @include('themes.cms.ace.pages.common.selection-show', ['has_choose' => false, 'default_page_size' => 10])
                        </div>
                        <div class="ml-auto mb-25">
                            <a href="">
                                <button type="button" class="btn btn-info d-none d-lg-block ml-auto mr-1"><i class="ti-plus" style="font-size: 12px;"></i> Create New</button>
                            </a>
                        </div>
                    </div>
                    <div class="d-block text-center">
                        @include('themes.cms.ace.layouts.common.loading-by-id')
                    </div>
                    <!-- Table  -->
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
        <div class="col-lg-12">
            @include('themes.cms.ace.pages.common.modal-order-info', [
                'modal_id' => 'requirement_modal',
                'modal_title' => trans('fotober.requirement.title'),
                'ajax_div_id' => 'ajax_requirement',
            ])
        </div>
        <div class="col-lg-12">
            @include('themes.cms.ace.pages.common.modal-order-info', [
                'modal_id' => 'output_modal',
                'modal_title' => trans('fotober.output.title'),
                'ajax_div_id' => 'ajax_output',
            ])
        </div>
        <div class="col-lg-12">
            @include('themes.cms.ace.pages.common.modal-order-info', [
                'modal_id' => 'invoice_detail_modal',
                'modal_title' => trans('fotober.order.invoice_detail'),
                'ajax_div_id' => 'ajax_invoice_detail',
            ])
        </div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = '{{ route('superadmin_listing_ajax_order') }}';

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('assigned_sale_id', url_ajax);
            filterBySelectBox('status', url_ajax);
            filterBySelectBox('page_size', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = '{{ route('superadmin_listing_ajax_order') }}';
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
            var assigned_sale_id = $('#assigned_sale_id').val();
            var status = $('#status').val();
            var page_size = $('#page_size').val();

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
                start_date: start_date,
                end_date: end_date,
                assigned_sale_id: assigned_sale_id,
                status: status
            };

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
                url: '{{ route('superadmin_show_info_ajax_order') }}',
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

        /**
         * Hàm show chi tiết yêu cầu
         *
         * @param order_id
         * @param customer_id
         */
        function showRequirement(order_id, customer_id){
            $('#data-loading').show();
            $('#ajax_requirement').html('');

            $.ajax({
                url: '{{ route('superadmin_requirement_listing_order') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    customer_id: customer_id,
                    user_id: window.user.user_id,
                    account_type: window.user.account_type,
                },
                success: function(result) {
                    $('#data-loading').hide();
                    //
                    $('#ajax_requirement').html(result);
                    $('#requirement_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        /**
         * Hàm show chi tiết yêu cầu
         *
         * @param order_id
         */
         function showOutput(order_id, customer_id){
            $('#data-loading').show();
            $('#ajax_output').html('');

            $.ajax({
                url: '{{ route('sale_order_output_list') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    customer_id: customer_id,
                    user_id: window.user.user_id,
                    account_type: window.user.account_type,
                },
                success: function(result) {
                    $('#data-loading').hide();
                    //
                    $('#ajax_output').html(result);
                    $('#output_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        /**
         * Hàm show item thanh toán
         *
         * @param order_id
         * @param payment_id
         */
        function showInvoiceDetail(order_id, payment_id){
            $('#data-loading').show();
            $('#ajax_invoice_detail').html('');

            $.ajax({
                url: '{{ route('sale_order_show_invoice_detail') }}',
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
                    $('#ajax_invoice_detail').html(result);
                    $('#invoice_detail_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }
    </script>
@endsection
