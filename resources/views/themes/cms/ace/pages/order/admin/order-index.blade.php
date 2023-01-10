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
                        <div class="form-group mr-3 custom-search-icon mb-w-100">
                            <i class="ti ti-search"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="{{ trans('fotober.common.search') }}">
                        </div>
                        <div class="form-group mr-3 mb-w-50">
                            <select class="form-control custom-select" name="assigned_editor_id" id="assigned_editor_id">
                                <option value="-1">{{ trans('fotober.common._select_editor_') }}</option>
                                @foreach($editors as $editor)
                                    <option value="{{ $editor->id }}">{{ $editor->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-w-50 mb-r-0">
                            <select class="form-control custom-select" name="status" id="status" tabindex="1">
                                <option value="-1">{{ trans('fotober.common._status_') }}</option>
                                @foreach($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
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
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = '{{ route('admin_order_listing_ajax') }}';

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('assigned_editor_id', url_ajax);
            filterBySelectBox('status', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = '{{ route('admin_order_listing_ajax') }}';
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
            var assigned_editor_id = $('#assigned_editor_id').val();
            var status = $('#status').val();
            var page_size = 10;

            var data = {
                _token: '{{ csrf_token() }}',
                url_id: {{ isset($url_id) ? $url_id : -1 }},
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
                start_date: start_date,
                end_date: end_date,
                assigned_editor_id: assigned_editor_id,
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

        function showInfo(order_id){
            $('#data-loading').show();
            $('#ajax_show_info').html('');

            $.ajax({
                url: '{{ route('admin_order_info_ajax') }}',
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
         * Admin giao order cho Editor member
         * @param order_id
         * @param editor_id
         */
         function updateAssignSale(order_id, editor_id){
            $('#data-loading').show();

            $.ajax({
                url: '{{ route('admin_order_assign_editor_ajax') }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    editor_id: editor_id,
                },
                success: function(result) {
                    changePage(1);
                    $('#data-loading').hide();
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
         function showRequirement(order_id, customer_id){
            $('#data-loading').show();
            $('#ajax_requirement').html('');

            $.ajax({
                url: '{{ route('admin_order_requirement_list') }}',
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


        function changeStatus(order_id, status){
            var con_firm = confirm('{{ trans('fotober.common.confirm_completed') }}');
            if(con_firm){
                $('#data-loading').show();
                $.ajax({
                    url: '{{ route('admin_order_change_status_ajax') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: order_id,
                        status: status,
                        user_id: window.user.user_id,
                        account_type: window.user.account_type,
                    },
                    success: function(result) {
                        $('#data-loading').hide();
                        if(result.code = 200){
                            changePage(1);
                        } else{
                            alert('Vui lòng thử lại sau!');
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                    }
                });
            } else{
                return false;
            }
        }
    </script>
@endsection
