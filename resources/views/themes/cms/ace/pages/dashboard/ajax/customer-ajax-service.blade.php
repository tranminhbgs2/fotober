<div class="row">
    @forelse($data as $key => $item)
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 mobile-width-100" style="cursor: pointer; margin-top: 10px">
            {{--<img class="img-lazy img-responsive" data-original="{{ asset($item->image) }}" src="{{ getImageLoadingBase64() }}">--}}
            {{--<img class="img-lazy img-responsive" data-original="{{ asset($item->image) }}">--}}
            <a href="{{ route('customer_order_create', ['service_id' => $item->id]) }}">
                <img class="img-lazy img-responsive" src="{{ asset($item->image) }}">
            </a>
            <p style="font-weight: bold">{{ $item->name }}</p>
        </div>
    @empty

    @endforelse
</div>
