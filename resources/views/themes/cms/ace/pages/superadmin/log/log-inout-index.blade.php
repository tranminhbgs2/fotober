@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sadmin.group.title') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('themes.cms.ace.layouts.common.alert-message')
        </div>
        <div class="col-lg-12"><h4>{{ trans('fotober.sadmin.group.title') }}</h4></div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-3">
                    <input type="text" name="keyword" id="keyword" placeholder="{{ trans('fotober.common.search') }}" style="width: 100%">
                </div>
                <div class="col-lg-1">
                    @include('themes.cms.ace.pages.common.selection-show', ['has_choose' => false, 'default_page_size' => 10])
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 text-center mb-1" style="position: absolute; top: -50px; right: 0px">
            @include('themes.cms.ace.layouts.common.loading-by-id')
        </div>
        <div class="col-lg-12" id="ajax_list" style="margin-top: 5px"></div>
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

            var url_ajax = "{{ route('superadmin_listing_ajax_in_out_log') }}";

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('page_size', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = "{{ route('superadmin_listing_ajax_in_out_log') }}";
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
            var page_size = $('#page_size').val();

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: page_size,
                keyword: keyword,
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
