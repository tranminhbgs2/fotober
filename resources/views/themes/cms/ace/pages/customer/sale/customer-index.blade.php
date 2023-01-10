@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sale.customer_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.sale.customer_title') }}</h4>
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
                    </div>
                    <div class="d-flex align-items-center no-block filter-nav-page @if (\Illuminate\Support\Facades\Auth::user()->is_admin) admin @endif">
                        <div class="form-group mr-3 custom-search-icon mb-w-100 mb-r-0">
                            <i class="ti ti-search"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="{{ trans('fotober.common.search') }}">
                        </div>
                        {{--<div class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                                <input class="form-control" type="text" name="date-range-picker" id="id-date-range-picker" />
                            </div>
                        </div>--}}
                        {{-- <div class="col-lg-2">
                            <select class="form-control" name="status" id="status" style="width: 100%">
                                <option value="-1">{{ trans('fotober.common._status_') }}</option>
                                @foreach($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select> --}}
                        {{-- </div> --}}
                        @if (\Illuminate\Support\Facades\Auth::user()->is_admin)
                            <div class="form-group mr-3 mb-w-100 mb-r-0">
                                <select class="form-control custom-select" name="manager_by" id="manager_by">
                                    <option value="-1">{{ trans('fotober.common._select_sale_') }}</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->id }}">{{ $sale->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group ml-auto mb-25">
                            <a href="{{ route('sale_customer_create') }}">
                                <button type="button" class="btn btn-info ml-auto mr-1"><i class="ti-plus" style="font-size: 12px;"></i> Add </button>
                            </a>
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
        @include('themes.cms.ace.pages.common.modal-customer-info', [
            'modal_id' => 'sale_customer_modal',
            'modal_title' => trans('fotober.sale.info_title'),
            'ajax_div_id' => 'ajax_show_info',
        ])
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = window.ajax_url.sale_customer_list;

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('manager_by', url_ajax);
            filterBySelectBox('status', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = window.ajax_url.sale_customer_list;
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
            var manager_by = $('#manager_by').val();
            var status = $('#status').val();
            var page_size = 10;

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
                manager_by: manager_by,
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
        function showInfo(customer_id){
            $('#data-loading').show();
            $('#ajax_show_info').html('');

            $.ajax({
                url: '{{ route('sale_customer_info_ajax') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: customer_id,
                },
                success: function(result) {
                    $('#data-loading').hide();
                    //
                    $('#ajax_show_info').html(result);
                    $('#sale_customer_modal').modal();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        /**
         * Sale admin giao order cho Sale member
         * @param order_id
         * @param sale_id
         */
        function updateAssignSale(customer_id,sale_id){
            $('#data-loading').show();

            $.ajax({
                url: '{{ route('sale_customer_update_assign_sale_ajax') }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    sale_id: sale_id,
                    customer_id: customer_id,
                },
                success: function(result) {
                    $('#data-loading').hide();
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }
    </script>
@endsection
