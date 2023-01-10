@forelse($notifications as $key => $item)
    <a href="{{ generateOrderLinkByAccount($account_type, $item->order_id, $item->id) }}">
        <div class="mail-contnet" style="width: 100%; padding-left: 0;">
            <h5>{{ \Illuminate\Support\Str::limit($item->title_vi, 150) }}</h5>
            <span class="time">{{ date('d/m/Y H:i', strtotime($item->created_at)) }} </span>
        </div>
    </a>
@empty
<li>{{ trans('fotober.common.no_notification') }}</li>
@endforelse