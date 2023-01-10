@if (count($outputs) > 0)
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
        </div>
    </div>
    <div class="col-md-12" id="image_show">
        @if ($output_image > 0)
            @foreach ($outputs as $key => $item)
            @if ($item->type == "IMAGE")
            <div class="card box-shadow border-r10 card-custom-request">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-12 col-md-5">
                            <?php
                                $arr_link = explode("/",$item->link);
                                $arr_link_dr = str_replace("dl=0","raw=1",$item->link);
                            ?>
                            @if ($item->type == 'VIDEO')
                                <div class="video-container">
                                    @if (strpos($item->link, 'www.dropbox.com') !== false)
                                        <?php
                                            $arr_link_dr = str_replace("dl=0","raw=1",$item->link);
                                        ?>
                                        <video width="100%" muted autoplay loop playsinline controls>
                                            <source src="{{$arr_link_dr}}"/>
                                        </video>
                                    @else
                                        <?php
                                            $arr_link = explode("/",$item->link);
                                        ?>
                                        <iframe src="https://player.vimeo.com/video/{{$arr_link[3]}}" width="100%" height="auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                    @endif
                                </div>
                            @else
                            <div class="image-container">
                                <img class="rounded float-left img-fluid" src="{{(!empty($item->link) && $item->link != 'null') ? $item->link: asset('storage/'.$item->file)}}" alt="" srcset="">
                            </div>
                            @endif
                        </div>
                        <div class="col-12 col-lg-auto ml-auto text-right">
                            @if ($item->order->status != \App\Helpers\Constants::ORDER_STATUS_PAID)
                            <form enctype="multipart/form-data" method="POST" id="forn_change_{{$item->id}}">
                            <input id="file_replace_{{$item->id}}" hidden type="file" onchange="replaceImg({{$item->id}}, 'image')" name="file_replace_{{$item->id}}">
                            </form>
                            <a href="javascript:void(0)" onclick="openUpload({{$item->id}})" class="btn btn-info mr-3 mt-3 pl-4 pr-4" style=" border-radius: 5px">
                                {{($item->fix_request == 2) ? 'Đã tải lại' : 'Tải lại'}}
                            </a>
                            <a href="javascript:void(0)" onclick="deleteOutput({{$item->id}}, {{$item->order_id}}, {{$item->customer_id}})" class="btn btn-danger mr-3 mt-3 pl-4 pr-4"
                            style="border-radius: 25px;background-color: #de0e0e;border-color: #de0e0e; color: #fff;">
                                {{ trans('fotober.common.btn_delete') }}
                            </a>
                            @endif
                            <a href="javascript:void(0)" class="btn mr-3 mt-3 {{($item->is_accepted == 1 || $item->request_revision != NULL) ? 'disabled' : ''}}" id="btn-request-{{$item->id}}"
                            style="border-radius: 25px;background-color: #017bcf;border-color: #017bcf; color: #fff;">
                                {{ trans('fotober.order.request_revision') }}
                                    <i class="ti-angle-down ml-1"></i>
                            </a>
                            @if (!empty($item->request_revision))
                            <div class="text-request show-web mr-3">
                                <div class="mt-2">
                                    {{ trans('fotober.order.customer_request_revision') }}: {{$item->request_revision}}
                                </div>
                            </div>
                            @endif

                            {{-- Mobile --}}
                            @if (!empty($item->request_revision))
                            <div class="col-md-8 col-12 show-mobile text-request">
                                <div class="col-md-12 p-0 mt-2 pl-2">
                                    {{ trans('fotober.order.customer_request_revision') }}: {{$item->request_revision}}
                                </div>
                            </div>
                            @endif
                            {{-- <div class="row float-right">
                                <div class="col-md-12 pr-3">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ trans('fotober.order.request_revision') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($item->request_revision)
                                            <span class="p-2">{{$item->request_revision}}</span>
                                            @else
                                            <span class="p-2">{{ trans('fotober.common.no_data') }}</span>
                                            @endif
                                        </div>
                                        </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <div class="d-flex justify-content-center mb-3">
                <a href="javascript:void(0)" onclick="showOutput({{ $order_id }}, {{ $customer_id }})" title="Click to show add Output" class="btn btn-info px-4 py-2" style="border-radius: 20px">
                    {{ trans('fotober.order.upload_more') }}
                </a>
            </div>
        @else
            <div class="card box-shadow border-r10">
                <div class="card-body p-4" style="padding: 0">
                    
                    <div class="col-md-12 d-flex justify-content-center">
                        <i class="fa fa-cloud-upload text-info" style="font-size: 80px" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <a href="javascript:void(0)" onclick="showOutput({{ $order_id }}, {{ $customer_id }})" title="Click to show add Output" class="btn btn-info pl-3 pr-3" style="border-radius: 20px">
                            {{ trans('fotober.order.upload_more') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-12"  id="video_show" style="display: none">
        @if ($output_video > 0)
            @foreach ($outputs as $key => $item)
                @if ($item->type == "VIDEO")
                <div class="col-md-12 p-0">
                    <div class="card box-shadow border-r10 card-custom-request">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <?php
                                        $arr_link = explode("/",$item->link);
                                        $arr_link_dr = str_replace("dl=0","raw=1",$item->link);
                                    ?>
                                    <div class="video-container">
                                        @if (strpos($item->link, 'www.dropbox.com') !== false)
                                            <?php
                                                $arr_link_dr = str_replace("dl=0","raw=1",$item->link);
                                            ?>
                                            <video width="100%" muted autoplay loop playsinline controls>
                                                <source src="{{$arr_link_dr}}"/>
                                            </video>
                                        @else
                                            <?php
                                                $arr_link = explode("/",$item->link);
                                            ?>
                                            <iframe src="https://player.vimeo.com/video/{{$arr_link[3]}}" width="100%" height="auto" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-lg-auto ml-auto text-right">
                                    @if ($item->order->status != \App\Helpers\Constants::ORDER_STATUS_PAID)
                                    <a href="javascript:void(0)" onclick="showOutputUpdate({{$item->order_id}}, {{$item->customer_id}}, {{$item->id}})" class="btn btn-info mr-3 mt-3 pl-4 pr-4" style=" border-radius: 5px">
                                        {{($item->fix_request == 2) ? 'Đã tải lại' : 'Tải lại'}}
                                    </a>
                                    <a href="javascript:void(0)" onclick="deleteOutput({{$item->id}}, {{$item->order_id}}, {{$item->customer_id}})" class="btn btn-danger mr-3 mt-3 pl-4 pr-4"
                                    style="border-radius: 25px;background-color: #de0e0e;border-color: #de0e0e; color: #fff;">
                                        {{ trans('fotober.common.btn_delete') }}
                                    </a>
                                    @endif
                                    <a href="javascript:void(0)" class="btn mr-3 mt-3 {{($item->is_accepted == 1 || $item->request_revision != NULL) ? 'disabled' : ''}}" id="btn-request-{{$item->id}}"
                                    style="border-radius: 25px;background-color: #017bcf;border-color: #017bcf; color: #fff;">
                                        {{ trans('fotober.order.request_revision') }}
                                            <i class="ti-angle-down ml-1"></i>
                                    </a>
                                    @if (!empty($item->request_revision))
                                    <div class="text-request show-web mr-3">
                                        <div class="mt-2">
                                            {{ trans('fotober.order.customer_request_revision') }}: {{$item->request_revision}}
                                        </div>
                                    </div>
                                    @endif
            
                                    {{-- Mobile --}}
                                    @if (!empty($item->request_revision))
                                    <div class="col-md-8 col-12 show-mobile text-request">
                                        <div class="col-md-12 p-0 mt-2 pl-2">
                                            {{ trans('fotober.order.customer_request_revision') }}: {{$item->request_revision}}
                                        </div>
                                    </div>
                                    @endif
                                    {{-- <div class="row float-right">
                                        <div class="col-md-12 pr-3">
                                            <div class="dropdown">
                                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ trans('fotober.order.request_revision') }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if ($item->request_revision)
                                                    <span class="p-2">{{$item->request_revision}}</span>
                                                    @else
                                                    <span class="p-2">{{ trans('fotober.common.no_data') }}</span>
                                                    @endif
                                                </div>
                                                </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
            <div class="d-flex justify-content-center mb-3">
                <a href="javascript:void(0)" onclick="showOutput({{ $order_id }}, {{ $customer_id }})" title="Click to show add Output" class="btn btn-info px-4 py-2" style="border-radius: 20px">
                    {{ trans('fotober.order.upload_more') }}
                </a>
            </div>
        @else
            <div class="card box-shadow border-r10">
                <div class="card-body p-4" style="padding: 0">
                    <div class="col-md-12 d-flex justify-content-center">
                        <i class="fa fa-cloud-upload text-info" style="font-size: 80px" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <a href="javascript:void(0)" onclick="showOutput({{ $order_id }}, {{ $customer_id }})" title="Click to show add Output" class="btn btn-info pl-3 pr-3" style="border-radius: 20px">
                            {{ trans('fotober.order.upload_more') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="col-12">
        <div class="card box-shadow border-r10">
            <div class="card-body p-4" style="padding: 0">
                
                <div class="col-md-12 d-flex justify-content-center">
                    <i class="fa fa-cloud-upload text-info" style="font-size: 80px" aria-hidden="true"></i>
                </div>
                <div class="col-md-12 d-flex justify-content-center">
                    <a href="javascript:void(0)" onclick="showOutput({{ $order_id }}, {{ $customer_id }})" title="Click to show add Output" class="btn btn-info pl-3 pr-3" style="border-radius: 20px">
                        {{ trans('fotober.order.upload_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

