@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-5 col-md-5 align-self-center">
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
            <div class="d-block">
                @include('themes.cms.ace.layouts.common.alert-message')
            </div>
            <div class="row d-flex align-items-center no-block">
                <div class="col-md-10">
                    <div class="row" style="margin-left: initial; margin-right: initial;">
                        <div class="form-group mr-35 custom-search-icon">
                            <i class="ti ti-search"></i>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="{{ trans('fotober.common.search') }}">
                        </div>
                        {{-- <div class="form-group mr-3  align-self-center text-muted">
                            <span>{{ trans('fotober.order.order_status') }}</span>
                        </div> --}}
                        <div class="form-group mr-35 mb-0">
                            <select class="form-control bg-white" name="status" id="status" tabindex="1">
                                <option value="-1">{{ trans('fotober.common._status_') }}</option>
                                @foreach($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group mr-3  align-self-center text-muted">
                            <span>{{ trans('fotober.order.short_by') }}</span>
                        </div> --}}
                        <div class="form-group mb-0">
                            <select class="form-control bg-white" name="sort_by_time" id="sort_by_time" tabindex="1">
                                <option value="newest">{{ trans('fotober.order.newest_first') }}</option>
                                <option value="oldest">{{ trans('fotober.order.lastest_first') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 form-group align-self-center text-right">
                    <a href="{{ route('customer_order_create') }}" class="btn btn-info d-none d-lg-inline-block ml-auto mr-1">
                        <i class="ti-plus" style="font-size: 12px;"></i> {{ trans('fotober.order.create_title') }}
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
    <div class="row">
        @include('themes.cms.ace.pages.common.modal-order-info', [
            'modal_id' => 'order_info_modal',
            'modal_title' => trans('fotober.order.info_title'),
            'ajax_div_id' => 'ajax_show_info',
        ])
    </div>
    <div class="row">
        @include('themes.cms.ace.pages.common.modal-order-info', [
            'modal_id' => 'order_output_modal',
            'modal_title' => trans('fotober.order.title_output'),
            'ajax_div_id' => 'ajax_show_output',
        ])
    </div>
    {{-- chat --}}
    <div class="row">
        @include('themes.cms.ace.pages.common.modal-customer-chat', [
            'modal_id' => 'chat_modal',
            'modal_title' => trans('fotober.order.add_note'),
            'ajax_div_id' => 'ajax_show_chat',
        ])
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = window.ajax_url.order_list;

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('status', url_ajax);
            filterBySelectBox('sort_by_time', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = window.ajax_url.order_list;
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
            var sort_by_time = $('#sort_by_time').val();
            var page_size = 5;

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
                start_date: start_date,
                end_date: end_date,
                status: status,
                sort_by_time: sort_by_time,
            };
            console.log(data);
            $.ajax({
                url: url_ajax,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#data-loading').hide();
                    const element = document.getElementById("page-wrapper");
                    element.scrollIntoView();
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
                url: '{{ route('customer_order_info_ajax') }}',
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
    </script>
@endsection
