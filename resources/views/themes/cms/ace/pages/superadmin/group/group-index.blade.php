@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sadmin.group.title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.sadmin.group.title') }}</h4>
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
                    <div class="d-flex align-items-center no-block">
                        <div class="form-group mr-3 custom-search-icon">
                            <i class="ti ti-search"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="{{ trans('fotober.common.search') }}">
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

            var url_ajax = "{{ route('superadmin_listing_ajax_group') }}";

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            //filterBySelectBox('status', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = "{{ route('superadmin_listing_ajax_group') }}";
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
            var status = $('#status').val();
            var page_size = 10;

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
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
    </script>
@endsection
