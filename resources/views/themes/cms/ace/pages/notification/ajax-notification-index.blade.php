<div class="table-responsive">
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('fotober.common.no') }}</th>
            <th class="text-center">{{ trans('fotober.notification.title') }}</th>
            <th class="text-center">{{ trans('fotober.notification.received_at') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <!-- STT -->
            <td class="text-center">{{ $offset + $key + 1 }}</td>
            <td class="text-left">
                @if($item->is_read == 0)
                    <a href="{{ generateOrderLinkByAccount(\Illuminate\Support\Facades\Auth::user()->account_type, $item->order_id, $item->id) }}">
                        {{ $item->title_vi }}
                    </a>
                @else
                    <a href="{{ generateOrderLinkByAccount(\Illuminate\Support\Facades\Auth::user()->account_type, $item->order_id, null) }}" style="color: brown">
                        {{ $item->title_vi }}
                    </a>
                @endif
            </td>
            <td class="text-center">
                {{ date('d/m/Y H:i:s', strtotime($item->created_at)) }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="13">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
