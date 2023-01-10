@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.service.title') }}
@endsection
@section('asset-header')
    <link rel="stylesheet" href="{{ asset('css/service.css?version=' . \App\Helpers\Constants::ASSET_VERSION) }}') }}" class="app-stylesheet" id="app-style"/>
    <link rel="stylesheet" href="{{ asset('libs/beerslider/BeerSlider.css') }}"/>
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles d-none">
        <div class="col-5 col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.service.title') }}</h4>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row mt-4">
        <!-- Column -->
        <div class="col-12">
            <div class="card card-services">
                <div class="card-body">
                    <h4 class="card-title pl-0">Our services</h4>
                    <div class="card-subtitle">
                        Go get started, click of our services
                    </div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab nav-services mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ml-0" data-toggle="tab" href="javascript:void(0)" role="tab" onclick="changePage(1, 0)">All Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" onclick="changePage(1, 1)">Photo Editing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" onclick="changePage(1, 2)">Virtual Staging</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" onclick="changePage(1, 3)">Video Editing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="javascript:void(0)" role="tab" onclick="changePage(1, 4)">Architecture Planning</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_all" role="tabpanel">
                            <div class="row" id="ajax_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('asset-bottom')
    <script src="{{ asset('libs/beerslider/BeerSlider.js') }}"></script>
    <script>
        $('.nav-services a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            jQuery(".beer-slider").BeerSlider({ start: 35 });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data-loading').hide();

            var url_ajax = "{{ route('customer_listing_ajax_service') }}";

            var oldTimeout = '';
            $('#keyword').keyup(function(){
                $('#data-loading').show();

                clearTimeout(oldTimeout);
                oldTimeout = setTimeout(function(){
                    getDataByAjax(url_ajax, 1);
                }, 250);
            });

            filterBySelectBox('status', url_ajax);
            filterBySelectBox('page_size', url_ajax);

            changePage(1, 0);
        
            
        
        });

        function changePage(page_index, group_code) {
            var url_ajax = "{{ route('customer_listing_ajax_service') }}";
            getDataByAjax(url_ajax, page_index, group_code);
        }

        function filterBySelectBox(id, url_ajax){
            $('#'+id).change(function(){
                getDataByAjax(url_ajax, 1);
            });
        }

        function getDataByAjax(url_ajax, page_index, group_code=0) {
            $('#data-loading').show();

            var data = {
                _token: '{{ csrf_token() }}',
                page_index: page_index,
                page_size: 50,
                keyword: '',
                status: 1,
                group_code: group_code
            };

            $.ajax({
                url: url_ajax,
                type: 'POST',
                dataType: 'HTML',
                data: data,
                success: function(result) {
                    $('#data-loading').hide();
                    $('#ajax_list').html(result);

                    jQuery.fn.BeerSlider = function (options) {
                        options = options || {};
                        return this.each(function () {
                            new BeerSlider(this, options);
                        });
                    };
                    jQuery(".beer-slider").BeerSlider({ start: 35 });
                    window.onload = function() {
                    let frameElement = document.getElementById("load-video");
                    let doc = frameElement.contentDocument;
                    doc.body.innerHTML = doc.body.innerHTML + '<style> #player {min-height: 224px;max-height: 224px;}</style>';
                    }
                    $("#load-video").on("load", function() {
                    let head = $("#load-video").contents().find("head");
                    let css = '<style> #player {min-height: 224px;max-height: 224px;}</style>';
                    $(head).append(css);
                    });
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
            // $( document ).ready(function() {
                // add size to iframe //
                // $('#ajax_list').each(function(){
                //     var iframe = $('#ajax_list .hp-service-block iframe');
                //     var height = $('#ajax_list .hp-service-block img').height();

                //     debugger
                //     iframe.css('height', height);
                // });

                // window.addEventListener('resize', function(event){
                //     // add size to iframe //
                //     $('#ajax_list').each(function(){
                //         var iframe = $('#ajax_list .hp-service-block iframe');
                //         var height = $('#ajax_list .hp-service-block img').height();
                //         iframe.css('height', height);
                //     });
                // });
            // });
        }
    </script>
@endsection
