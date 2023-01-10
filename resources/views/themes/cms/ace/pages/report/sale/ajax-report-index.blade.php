<div class="table-responsive">
    <table id="simple-table" class="table table-bordered table-centered table-head-bg">
        <thead>
        <tr>
            <th rowspan="2">{{ trans('fotober.common.no') }}</th>
            <th rowspan="2">{{ trans('fotober.common.col_fullname') }}</th>
            <th colspan="10">{{ trans('fotober.order.amount_by_status') }}</th>
        </tr>
        <tr>
            <th>New</th>
            <th>Pending</th>
            <th>Editing</th>
            <th>Edited</th>
            {{-- <th>Checking</th> --}}
            <th>Checked</th>
            <th>Completed</th>
            <th>Re-do</th>
            <th>Awaiting Payment</th>
            <th>Paid</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <!-- STT -->
            <td>{{ $offset + $key + 1 }}</td>
            <td class="text-left">
                <b>{{ $item->fullname }}</b>
                <br>
                <span style="color: #adadad; font-size: 11px">{{ trans('fotober.common.email') }}:&nbsp;{{ $item->email }}</span>
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_NEW)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PENDING)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_EDITING)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_EDITED)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            {{-- <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_CHECKING)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td> --}}
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_CHECKED)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_REDO)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
            <td>
                @foreach($item->orders as $k => $order)
                    @if ($order->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                        {{ number_format($order->total_order, 0) }}
                    @endif
                @endforeach
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="12" class="text-center">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
