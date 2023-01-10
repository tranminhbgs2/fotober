
<div id="chat-content">
    <ul class="chat-list-order p-5" id="content-mess-sale-{{$order->id}}">
        @forelse($messages as $key => $item)
        @if ($item['customer_id'])
            <li style="margin-top: {{($key == 0) ? '0px' : '30px'}}">
                <div class="chat-img">
                    <img src="{{ $cus_avatar }}" alt="user">
                </div>
                <div class="chat-content cus">
                    {{-- <h5>{{ $name_cus }}</h5> --}}
                    <div class="box bg-light-info">
                        @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_IMAGE)
                            <a href="{{ asset('storage/'.$item['content']) }}" data-lightbox="chat-lightbox-{{ $order->id }}" target="_blank" data-title="Preview">
                                <img class="img-chat img-fluid" src="{{asset('storage/'.$item['content'])}}" >
                            </a>
                        @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_FILE)
                            <a href="{{asset('storage/'.$item['content'])}}" target="_blank">{{$item['file_name']}}</a>
                        @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_LINK)
                            <a href="{{$item['content']}}}}" target="_blank">{{$item['content']}}</a>
                        @else
                            {{$item['content']}}
                        @endif
                    </div>
                    {{-- <div class="chat-time">{{ date("H:i a", strtotime($item['created_at'])) }}</div> --}}
                </div>
            </li>
        @else
            <li class="reverse">
                <div class="chat-content sale">
                    {{-- <h5>{{ $name_sale }}</h5> --}}
                    <div class="box bg-light-inverse">
                        @if ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_IMAGE)
                            <a href="{{ asset('storage/'.$item['content']) }}" data-lightbox="chat-lightbox-{{ $order->id }}"  target="_blank" data-title="Preview">
                            <img class="img-chat sale img-fluid" src="{{asset('storage/'.$item['content'])}}">
                            </a>
                        @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_FILE)
                            <a href="{{asset('storage/'.$item['content'])}}" target="_blank">{{$item['file_name']}}</a>
                        @elseif ($item['type'] == \App\Helpers\Constants::MESSAGE_TYPE_LINK)
                            <a href="{{$item['content']}}" target="_blank">{{$item['content']}}</a>
                        @else
                            {{$item['content']}}
                        @endif
                    </div>
                    {{-- <div class="chat-time">{{ date("H:i a", strtotime($item['created_at'])) }}</div> --}}
                </div>
                <div class="chat-img">
                    <img class="avatar" src="{{ $sale_avatar }}" alt="...">
                </div>
            </li>
        @endif
        @empty
            <div class="media media-meta-day">{{ trans('fotober.order.no_message') }}</div>
        @endforelse
        <!--chat Row -->
    </ul>
</div>
<script src="{{ mix('js/chat.js') }}"></script>

@section('asset-header')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/lightbox2/css/lightbox.min.css') }}"/>
@endsection
