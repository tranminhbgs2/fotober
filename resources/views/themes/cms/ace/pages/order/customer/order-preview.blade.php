@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.detail_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="page-titles" style="display: block; padding: 0; margin: 25px 0 10px 0;background: transparent;box-shadow: none;">
        <div class="align-self-center">
            <h4 class="text-themecolor title" style="font-size: 25px;color: #017bcf;font-weight: 600;">{{ trans('fotober.order.ready_for_review') }}</h4>
        </div>
    </div>
    <div class="row order-sumary customer">

        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        {{-- <div class="row page-titles" style="display: block;">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor color-blue">{{ trans('fotober.order.ready_for_review') }}</h4>
            </div>
        </div> --}}

        <div class="col-md-12">
            <div class="d-block">
                @include('themes.cms.ace.layouts.common.alert-message')
            </div>
        </div>
        <!-- Column -->
        <div class="col-12 filter-style">
            <div class="row d-flex align-items-center no-block mb-3">

                <div class="col-auto col-md-auto" style="padding-right: 10px;">
                    <a href="javascript:void(0)" onclick="showItem('image_show')" class="btn btn-info d-lg-inline-block ml-auto mr-1 fs-12" id="btn-image" style="width:100%; border-radius: 20px">
                        {{ trans('fotober.order.image') }} ({{$output_image}})
                    </a>
                </div>

                <div class="col-auto col-md-auto" style="padding-left: 10px;">
                    <a href="javascript:void(0)" id="btn-video" onclick="showItem('video_show')" class="btn btn-light d-lg-inline-block ml-auto mr-1 fs-12 color-blue" style="border-radius: 20px;">
                        {{ trans('fotober.order.property_video') }} ({{$output_video}})
                    </a>
                </div>
                <div class="col-auto col-md-auto ml-md-auto">
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID )
                        @if ($output_image > 0)
                            <a href="{{ route('download_output_zip', ['order_id' => $order->id]) }}" class="btn btn-info d-lg-inline-block ml-auto mr-1 fs-12 float-right" target="_blank" id="download">{{ trans('fotober.order.down') }}</a>
                        @else
                        
                        <a href="javascript:void(0)" target="_blank" id="download"></a>
                        @endif
                    @else
                        @if(isset($order->payment))
                        <a href="{{$order->payment->link_payment}}" class="btn btn-info d-lg-inline-block ml-auto mr-1 float-right" target="_blank">
                            {{ trans('fotober.order.pay_now_and_down') }}
                        </a>
                        @endif
                    @endif
                </div>
                
            </div>
        </div>
        <div class="col-12" id="image_show">
            @if ($output_image > 0)
                @php
                    $i = 0;
                @endphp
                @foreach ($outputs as $key => $item)
                @if ($item->type == "IMAGE")
                @php
                    $i++;
                @endphp
                <input type="hidden" name="count_img[]" value="{{$item->id}}">
                <div class="col-md-12 p-0">
                    <div class="card box-shadow border-r10 card-custom-request">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <h6 class="show-mobile">Photo {{$i}} of {{$output_image}}</h6>
                                    <div class="image-container" onclick="showFull('{{($item->link) ? $item->link: asset('storage/'.$item->file)}}')" id="img-container_{{$item->id}}" style="postion: relative;">
                                        @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                                            <img class="rounded float-left img-fluid" oncontextmenu="return true;" src="{{($item->link) ? $item->link: asset('storage/'.$item->file)}}" alt="" srcset="">
                                        @else
                                            <img class="rounded float-left img-fluid img-done" oncontextmenu="return false;" src="{{($item->link) ? $item->link: asset('storage/'.$item->file)}}" alt="" srcset="">
                                        @endif
                                        <div style="width: 100%; height: 100%; position: absolute; top: 0; left: 0"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-auto">
                                    <h6 class="hide-mobile mb-3">Photo {{$i}} of {{$output_image}}</h6>
                                    <div class="meta-control ml-5" style="position: static;">
                                        <a href="javascript:void(0)" id="btn-accept-{{$item->id}}"onclick="sendAccept({{$item->id}}, 'image')" class="btn btn-info {{($item->is_accepted == 1 || $item->fix_request == 1) ? 'disabled' : ''}} fs-12 d-lg-inline-block ml-auto mr-1" style=" border-radius: 5px">
                                            @if ($item->is_accepted == 1)
                                            <i class="ti-check"></i>{{ trans('fotober.order.accepted_image') }}
                                            @else
                                            <i class="ti-check"></i>{{ trans('fotober.order.accepted_image') }}
                                            @endif
                                        </a>
                                        
                                        <a href="javascript:void(0)" class="btn btn-light fs-12 d-lg-inline-block ml-2 mr-1 color-blue {{($item->is_accepted == 1 || $item->fix_request == 1) ? 'disabled' : ''}}" id="btn-request-{{$item->id}}" onclick="showRequest({{$item->id}}, {{$item->is_accepted}})" style=" border: 1px solid #017bcf; border-radius: 5px">
                                            <i class="ti-reload"></i> {{ trans('fotober.order.request_revision') }}
                                        </a>
                                        <div id="req_revi_{{$item->id}}" class="form-request form-request--message">
                                            @if ($item->request_revision && $item->fix_request == 1)
                                                <div class="text-request mt-2">
                                                    {{ trans('fotober.order.your_request_revision') }}: {{$item->request_revision}}
                                                </div> 
                                            @endif
                                        </div>
                                        <div id="request-web-{{$item->id}}" style="display: none" class="form-request form-request-mb mt-2 form-request--form">
                                            <div class="rows">
                                                <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" placeholder="Type comment here" rows="3"></textarea>
                                                <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                            </div>
                                            <div class="rows">
                                                <button class="btn btn-primary mr-2" onclick="sendRequest({{$item->id}}, {{$item->is_accepted}}, 'image')">Submit</button>
                                                <button class="btn btn-light" style="color: #017bcf;border-color: #017bcf;" onclick="cancelRequest({{$item->id}})">Cancel</button>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-12 pl4 show-mobile" style="display: none;">
                                            <div class="col-md-12 mt-2" id="req_revi_{{$item->id}}">
                                            @if ($item->request_revision && $item->fix_request == 1)
                                            {{ trans('fotober.order.your_request_revision') }}: {{$item->request_revision}}
                                            @endif
                                            </div>
                                            <div class="col-md-12 mt-2" id="request-mobi-{{$item->id}}" style="display: none">
                                                <div class="rows">
                                                    <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" placeholder="Type comment here" rows="3"></textarea>
                                                    <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                                </div>
                                                <div class="rows mt-2">
                                                    <button class="btn btn-primary mr-2" onclick="sendRequest({{$item->id}}, {{$item->is_accepted}}, 'image')">Submit</button>
                                                    <button class="btn btn-light" onclick="cancelRequest({{$item->id}})">cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <p class="text-center">{{ trans('fotober.common.no_data') }}</p>
            @endif
        </div>
        <div class="col-12"  id="video_show" style="display: none">
            @if ($output_video > 0)
                @php
                    $i = 0;
                @endphp
                @foreach ($outputs as $key => $item)
                @if ($item->type == "VIDEO")
                @php
                    $i++;
                @endphp
                <div class="col-md-12 p-0">
                    <div class="card box-shadow border-r10 card-custom-request">
                        <div class="card-body" style="padding: 0">
                            <div class="row p-2">
                                <h6 class="show-mobile" style="padding-left: 20px; padding-right:20px">Video {{$i}} of {{$output_video}}</h6>
                                <div class="col-12 col-md-5">
                                    <div class="video-container">
                                        @if (strpos($item->link, 'www.dropbox.com') !== false)
                                        <?php
                                            $arr_link_dr = str_replace("dl=0","raw=1",$item->link);
                                        ?>
                                        @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                                        <video width="100%" muted autoplay loop playsinline controls>
                                            <source src="{{$arr_link_dr}}"/>
                                        </video>
                                        @else
                                        <video width="100%" muted autoplay loop playsinline controls controlsList="nodownload">
                                            <source src="{{$arr_link_dr}}"/>
                                        </video>
                                        @endif
                                        @else
                                        <?php
                                            $arr_link = explode("/",$item->link);
                                        ?>
                                            @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                                            <iframe src="https://player.vimeo.com/video/{{$arr_link[3]}}" width="100%" height="auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><br>
                                            <a href="{{$item->link}}" class="btn btn-info d-lg-inline-block ml-auto mr-1 fs-12" target="_blank">{{ trans('fotober.order.down') }}</a>
                                            @else
                                            <iframe src="https://player.vimeo.com/video/{{$arr_link[3]}}" width="100%" height="auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-auto">
                                    <h6 class="hide-mobile mb-3">Video {{$i}} of {{$output_video}}</h6>
                                    <div class="meta-control ml-5" style="position: static;">
                                        <a href="javascript:void(0)" id="btn-accept-{{$item->id}}" onclick="sendAccept({{$item->id}}, 'video')" class="btn {{($item->is_accepted == 1 || $item->fix_request == 1) ? 'disabled' : ''}} btn-info d-lg-inline-block ml-auto mr-1 fs-12" style=" border-radius: 5px">
                                            @if ($item->is_accepted == 1)
                                            <i class="ti-check"></i>{{ trans('fotober.order.accepted_video') }}
                                            @else
                                            <i class="ti-check"></i>{{ trans('fotober.order.accept_video') }}
                                            @endif
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-light fs-12 d-lg-inline-block ml-auto mr-1 color-blue {{($item->is_accepted == 1 || $item->fix_request == 1) ? 'disabled' : ''}}" id="btn-request-{{$item->id}}" onclick="showRequest({{$item->id}}, {{$item->is_accepted}})" style=" border: 1px solid #017bcf; border-radius: 5px">
                                            <i class="ti-reload"></i> {{ trans('fotober.order.request_revision') }}
                                        </a>
                                        <div id="req_revi_{{$item->id}}" class="form-request form-request--message">
                                            @if ($item->request_revision && $item->fix_request == 1)
                                                <div class="video-request mt-2">
                                                    {{ trans('fotober.order.your_request_revision') }}: {{$item->request_revision}}
                                                </div> 
                                            @endif
                                        </div>
                                        
                                        <div id="request-web-{{$item->id}}" style="display: none" class="form-request form-request-mb mt-2 form-request--form-video">
                                            <div class="rows">
                                                <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" placeholder="Type comment here" rows="3"></textarea>
                                                <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                            </div>
                                            <div class="rows">
                                                <button class="btn btn-primary mr-2" onclick="sendRequest({{$item->id}}, {{$item->is_accepted}}, 'video')">Submit</button>
                                                <button class="btn btn-light" style="color: #017bcf;border-color: #017bcf;" onclick="cancelRequest({{$item->id}})">Cancel</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12 pl4 hide-mobile">
                                            <div class="col-md-12 mt-2" id="request-web-{{$item->id}}" style="display: none">
                                                <div class="row">
                                                    <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" required placeholder="Type comment here" rows="3"></textarea>
                                                    <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                                </div>
                                                <div class="col-md-12 mt-2" id="request-web-{{$item->id}}" style="display: none">
                                                    <div class="row">
                                                        <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" required placeholder="Type comment here" rows="3"></textarea>
                                                        <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <button class="btn btn-primary mr-2" onclick="sendRequest({{$item->id}}, {{$item->is_accepted}}, 'video')">Submit</button>
                                                        <button class="btn btn-light" onclick="cancelRequest({{$item->id}}, {{$item->is_accepted}})">cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8 col-12 pl4 show-mobile" style="display: none;">
                                            <div class="col-md-12 mt-2" id="req_revi_{{$item->id}}">
                                            @if ($item->request_revision)
                                            {{ trans('fotober.order.your_request_revision') }}: {{$item->request_revision}}
                                            @endif
                                            </div>
                                            <div class="col-md-12 mt-2" id="request-mobi-{{$item->id}}" style="display: none">
                                                <div class="row">
                                                    <textarea name="in-request-{{$item->id}}" id="in-request-{{$item->id}}" class="form-control" required placeholder="Type comment here" rows="3"></textarea>
                                                    <span class="text-danger" style="display: none" id="error-{{$item->id}}">Vui lòng nhập</span>
                                                </div>
                                                <div class="row mt-2">
                                                    <button class="btn btn-primary mr-2" onclick="sendRequest({{$item->id}}, {{$item->is_accepted}})">Submit</button>
                                                    <button class="btn btn-light" onclick="cancelRequest({{$item->id}}, {{$item->is_accepted}})">cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <p class="text-center">{{ trans('fotober.common.no_data') }}</p>
            @endif
        </div>
    </div>

    <div class="row order-sumary customer">
        <!-- Column -->
        <div class="col-12">
            <h4 class="mb-2">{{ trans('fotober.order.rate_end_review') }}</h4>

            <div class="col-md-12 p-0">
                <div class="card box-shadow border-r10 card-review">
                    <div class="card-body" style="padding: 0">
                        <div class="row p-4  d-flex align-items-center">
                            <div class="col-md-12">
                                <p class="text-center mb-0" style="width:100%"> Add your rating</p>
                                <input id="valrating" hidden value="{{($order->rating > 0) ? $order->rating : 0 }}" >
                                <div class="rating">
                                    <input type="radio" name="rating" value="5" id="5" checked>
                                    <label for="5">☆</label>
                                    <input type="radio" name="rating" value="4" id="4">
                                    <label for="4">☆</label>
                                    <input type="radio" name="rating" value="3" id="3">
                                    <label for="3">☆</label>
                                    <input type="radio" name="rating" value="2" id="2">
                                    <label for="2">☆</label>
                                    <input type="radio" name="rating" value="1" id="1">
                                    <label for="1">☆</label>
                                    <span class="text-danger" style="display: none" id="error-rating">Please choose</span>
                                </div>
                            </div>
                        </div>
                        @if ($order->review == NULL && $order->rating <= 0)
                        <div class="row" id="select-review">
                            <div class="col-md-12 pr-5 pl-5">
                                <p>Thanks the designer to let them know they did a great job!</p>
                                <textarea name="review" id="review" class="form-control" required placeholder="Type a thank you message" rows="4" style="background: #f6f6f6;border-color: #f6f6f6;"></textarea>
                                <span class="text-danger" style="display: none" id="error-review">Please Enter</span>
                                <div class="form-check">
                                    <input class="form-check-input" name="check-rule" type="checkbox" value="" id="check-rule" checked>
                                    <label class="form-check-label" for="flexCheckChecked">
                                    I allow Fotober to display my comment as a testimonial for their marketing purpose
                                    </label>
                                    <span class="text-danger" style="display: none" id="error-check">Please choose</span>
                                </div>
                                <button class="btn btn-primary mt-2 mb-5" onclick="sendReview({{$order->id}})">Submit review</button>
                            </div>

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal01" class="w3-modal" onclick="this.style.display='none'" style="background-color: rgba(0,0,0,0.8);">
        <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
        <div class="w3-modal-content w3-animate-zoom">
            <img id="img01" style="width:100%">
            <div style="width: 100%; height: 100%; position: absolute; top: 0; left: 0"></div>
        </div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $( document ).ready(function() {
            var id_video = document.getElementsByName('id_video[]')
            console.log(id_video);
            for (var i = 0; i < id_video.length; i++) {
                var id = id_video[i].value;
                var video = document.getElementById("video-"+id);
                console.log(video.currentTime); // output 0
                video.currentTime = 1;
            }
        });
        
        function showFull(srcurl) {
        document.getElementById("img01").src = srcurl;
        document.getElementById("modal01").style.display = "block";
        }
        var count_img = $('input[name="count_img[]"]').map(function () {
            return this.value; // $(this).val()
        }).get();
        // if(count_img.length > 0){
        //     for (let index = 0; index <= count_img.length; index++) {
        //         // If the width and height of the image are not known or to adjust the image to the container of it
        //         var options2 = {
        //             fillContainer: true,
        //             zoomWidth: 750,
        //             zoomHeight: 650,
        //             offset: {vertical: 0, horizontal: 10}
        //         };
        //         new ImageZoom(document.getElementById("img-container_"+count_img[index]), options2);
        //     }
        // }
        $( document ).ready(function() {
            var  valrating = document.getElementById('valrating').value;
            if(valrating > 0){
                var  rating = document.getElementById(valrating);
                rating.checked = true;
                $('input[name="rating"]').attr('disabled', 'disabled');
            }
        });
        function showItem(id){
            var video_show = document.getElementById('video_show');
            var image_show = document.getElementById('image_show');
            var btn_image = document.getElementById('btn-image');
            var btn_video = document.getElementById('btn-video');
            if(id == 'video_show'){
                video_show.style.display = 'block';
                image_show.style.display = 'none';
                $('#download').attr("style", "display: none !important");
                btn_image.style.border = '1px solid #017bcf';

                btn_video.classList.remove('btn-light');
                btn_video.classList.remove('color-blue');
                btn_video.classList.add('btn-primary');

                btn_image.classList.add('btn-light');
                btn_image.classList.add('color-blue');
                btn_image.classList.remove('btn-primary');
            }

            if(id == 'image_show'){
                document.getElementById('download').style.display = 'block';
                image_show.style.display = 'block';
                video_show.style.display = 'none';
                // video_show.style.border = '1px solid #017bcf';

                btn_image.classList.remove('btn-light');
                btn_image.classList.remove('color-blue');
                btn_image.classList.add('btn-primary');

                btn_video.classList.add('btn-light');
                btn_video.classList.add('color-blue');
                btn_video.classList.remove('btn-primary');
            }
        }

        function showRequest(id, is_accept){
            var request_web = document.getElementById('request-web-'+id);
            var request_mobi = document.getElementById('request-mobi-'+id);
            var btn_req = document.getElementById('btn-request-'+id);
            var btn_accept = document.getElementById('btn-accept-'+id);
            request_web.style.display = 'block';
            request_mobi.style.display = 'block';
            btn_req.style.opacity = "50%";

            if(is_accept != 1){
                btn_accept.classList.add('disabled');
                btn_accept.style.opacity = "50%";
            }
            btn_req.classList.add('disabled');

            btn_req.setAttributeNS(null, "pointer-events", "none");

        }

        function cancelRequest(id, is_accept){
            var request_web = document.getElementById('request-web-'+id);
            var request_mobi = document.getElementById('request-mobi-'+id);
            var btn_req = document.getElementById('btn-request-'+id);
            var btn_accept = document.getElementById('btn-accept-'+id);
            request_web.style.display = 'none';
            request_mobi.style.display = 'none';
            btn_req.style.opacity = "100%";
            if(is_accept != 1){
                btn_accept.classList.remove('disabled');
                btn_accept.style.opacity = "100%";
            }

            btn_req.classList.remove('disabled');

            btn_req.setAttributeNS(null, "pointer-events", "auto");

        }

        function sendRequest(id, is_accept, type){
            $('#data-loading').show();
            $('#ajax_show_input').html('');
            var request_revision = document.getElementById('in-request-'+id);
            var req_revision = document.getElementById('req_revi_'+id);
            var btn_req = document.getElementById('btn-request-'+id);
            var error = document.getElementById('error-'+id);
            var request_web = document.getElementById('request-web-'+id);
            var request_mobi = document.getElementById('request-mobi-'+id);
            var btn_accept = document.getElementById('btn-accept-'+id);


            error.style.display = 'none';
            request_revision = request_revision.value;
            // console.log('aaa: ',request_revision)

            if(request_revision && request_revision !=  '' && request_revision != ' '){
                    $.ajax({
                    url: '{{ route('customer_order_request_output') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        user_id: window.user.user_id,
                        request_revision: request_revision,
                    },
                    success: function(result) {
                        if(result.code == 200){
                            request_revision.innerHTML  = '';
                            if(type == 'image'){
                                req_revision.innerHTML = '<div class="text-request mt-2">{{ trans('fotober.order.your_request_revision') }}: '+request_revision +'</div>';
                            } else {
                                req_revision.innerHTML = '<div class="video-request mt-2">{{ trans('fotober.order.your_request_revision') }}: '+request_revision +'</div>';
                            }
                            request_web.style.display = 'none';
                            request_mobi.style.display = 'none';
                            btn_req.classList.add('disabled');
                            btn_req.style.opacity = "50%";
                            btn_accept.classList.add('disabled');
                            btn_accept.style.opacity = "50%";
                            $.toast({
                                heading: 'Fotober',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#04AA6D',
                                loaderBg: '#a34335',
                            });
                        }
                        //alert(result.message);
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                    }
                });
            } else {
                error.style.display = 'block';
            }
        }

        
        function sendAccept(id, type){
            $('#data-loading').show();
            $('#ajax_show_input').html('');
            var request_revision = document.getElementById('in-request-'+id);
            var req_revision = document.getElementById('req_revi_'+id);
            var btn_req = document.getElementById('btn-request-'+id);
            var error = document.getElementById('error-'+id);
            var request_web = document.getElementById('request-web-'+id);
            var request_mobi = document.getElementById('request-mobi-'+id);
            var btn_accept = document.getElementById('btn-accept-'+id);


            error.style.display = 'none';
            // console.log('aaa: ',request_revision)
            if (confirm('{{ trans('fotober.common.confirm_accept') }}') == true) {
                $.ajax({
                    url: '{{ route('customer_order_accept_output')}}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        user_id: window.user.user_id,
                    },
                    success: function(result) {
                        if(result.code == 200){
                            if(type == 'video'){
                                btn_accept.innerHTML = '<i class="ti-check"></i>{{ trans('fotober.order.accepted_video') }}';
                            } else{
                                btn_accept.innerHTML = '<i class="ti-check"></i>{{ trans('fotober.order.accepted_image') }}';
                            }
                                                
                            request_web.style.display = 'none';
                            request_mobi.style.display = 'none';
                            btn_req.classList.add('disabled');
                            btn_req.style.opacity = "50%";
                            btn_accept.classList.add('disabled');
                            btn_accept.style.opacity = "50%";
                            $.toast({
                                heading: 'Fotober',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#04AA6D',
                                loaderBg: '#a34335',
                            });
                        } else {
                            $.toast({
                                heading: 'Fotober',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#a34335',
                                loaderBg: '#a34335',
                            });

                        }
                        //alert(result.message);
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                    }
                });
            }
        }


        function sendReview(id){
            var review = document.getElementById('review');
            var select_review = document.getElementById('select-review');
            var check_rule = document.querySelector('#check-rule').checked;
            var error_review = document.getElementById('error-review');
            var error_rating = document.getElementById('error-rating');
            var error_check = document.getElementById('error-check');
            var  rating = document.querySelector('input[name=rating]:checked').value;


            error_review.style.display = 'none';
            error_rating.style.display = 'none';
            error_check.style.display = 'none';
            review = review.value;
            if(!check_rule){
                error_check.style.display = 'block';
            } else if(rating <= 0){
                error_rating.style.display = 'block';
            }
            else if(review && review !=  '' && review != ' '){
                    $.ajax({
                    url: '{{ route('customer_order_preview_submit') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        rating: rating,
                        review: review,
                    },
                    success: function(result) {
                        if(result.code == 200){
                            review.innerHTML  = '';
                            select_review.style.display = 'none';
                            rating = rating;
                            $('input[name="rating"]').attr('disabled', 'disabled');
                            $.toast({
                                heading: 'Fotober',
                                text: result.message,
                                position: 'bottom-right',
                                stack: 5,
                                hideAfter: 15000,
                                bgColor: '#04AA6D',
                                loaderBg: '#a34335',
                            });
                        }
                        //alert(result.message);
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                    }
                });
            } else {
                error_review.style.display = 'block';
            }
        }
    </script>
@endsection
