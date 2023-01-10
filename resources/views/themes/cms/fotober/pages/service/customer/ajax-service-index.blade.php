@forelse($data as $key => $item)
<div class="col-12 col-md-6 col-lg-4 mb-3">
    <div class="hp-service-block">
        <div class="ser-embed">
            <div class="html-embed-103 bor-radius w-embed w-script">
                @if ($item->type == \App\Helpers\Constants::SERVICE_TYPE_BEFORE_AFTER)
                    <div class="beer-slider" data-beer-label="after">
                        <img src="{{ $item->after_photo }}" class="img-fluid" alt="VirtualStaging-After">
                        <div class="beer-reveal" data-beer-label="before">
                            <img src="{{ $item->before_photo }}" class="img-fluid" alt="VirtualStaging-Before">
                        </div>
                    </div>
                @elseif($item->type == \App\Helpers\Constants::SERVICE_TYPE_ONLY_IMAGE)
                    <div class="image-container">
                        <img src="{{ $item->image }}" class="img-fluid" alt="Fotober Image">
                    </div>
                @elseif($item->type == \App\Helpers\Constants::SERVICE_TYPE_ONLY_VIDEO)
                    <div class="video-container">
                        @if ($item->video_src == \App\Helpers\Constants::VIDEO_SRC_VIMEO)
                            <iframe width="100%" id="load-video" height="315" src="{{ $item->video_link }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
                        @elseif($item->video_src == \App\Helpers\Constants::VIDEO_SRC_YOUTUBE)
                            <iframe width="100%" id="load-video" height="315" src="{{ $item->video_link }}" frameborder="0" allow="autoplay; encrypted-media"></iframe>
                        @else
                            <video width="100%" height="315" controls>
                                <source src="{{ $item->video_link }}" type="video/mp4">
                                <source src="{{ $item->video_link }}" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="hp-ser-cur">
            <div class="ser-price-div">
                <div class="text-block-579">{{ $item->name }}</div>
                <div class="text-block-580">
                    Starting from
                    <br><span class="text-span-39">${{ $item->from_price }}</span>
                </div>
            </div>
            <div class="hp-lm-txt">{{ \Illuminate\Support\Str::limit($item->description, 75) }}</div>
            <div class="hp-lm0-txt">
                <a href="{{ route('customer_order_create', ['service_id' => $item->id]) }}">
                    <button type="button" class="btn btn-primary mr-3">Place order</button>
                </a>
                @if ( $item->read_more )
                    <a class="link" href="{{ $item->read_more }}" target="_blank">Learn More</a>
                @else
                    <a class="link" href="javascript:void(0)">Learn More</a>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
@endforelse
