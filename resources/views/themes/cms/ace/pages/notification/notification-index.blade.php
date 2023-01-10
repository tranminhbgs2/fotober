@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.notification.list') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('themes.cms.ace.layouts.common.alert-message')
        </div>
        <div class="col-lg-12"><h4>{{ trans('fotober.notification.list') }}</h4></div>
        <div class="col-lg-12">
            @include('themes.cms.ace.layouts.common.flat-session-info')
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-3">
                    <input type="text" name="keyword" id="keyword" placeholder="{{ trans('fotober.common.search') }}" style="width: 100%">
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 text-center mb-1" style="position: absolute; top: -50px; right: 0px">
            @include('themes.cms.ace.layouts.common.loading-by-id')
        </div>
        <div class="col-lg-12" id="ajax_list" style="margin-top: 5px"></div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = '{{ route('notifications_listing_ajax') }}';

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            //filterBySelectBox('assigned_sale_id', url_ajax);
            //filterBySelectBox('status', url_ajax);

            changePage(1);
        });

        function changePage(page_index) {
            var url_ajax = '{{ route('notifications_listing_ajax') }}';
            getDataByAjax(url_ajax, page_index);
        }

        function filterBySelectBox(id, url_ajax){
            $('#'+id).change(function(){
                getDataByAjax(url_ajax, 1);
            });
        }

        function getDataByAjax(url_ajax, page_index, customer_id = -1, start_date=null, end_date=null) {
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
