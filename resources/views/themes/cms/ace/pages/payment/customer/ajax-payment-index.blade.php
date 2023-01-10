<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th >{{ trans('fotober.common.no') }}</th>
            <th >{{ trans('fotober.payment.name') }}</th>
            <th >{{ trans('fotober.payment.delivery') }}</th>
            <th >{{ trans('fotober.payment.cost') }}</th>
            <th >{{ trans('fotober.payment.method') }}</th>
            <th >{{ trans('fotober.payment.status') }}</th>
            <th >{{ trans('fotober.payment.paid_at') }}</th>
            <th >{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="no">{{ ($offset + $key+ 1) }}</td>
            <td class="name font-weight-500">
                <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                    {{ $item->order->name }}
                </a>
            </td>
            <td class="name">
                {{ ($item->order->delivered_at) ? date('d/m/Y H:i', strtotime($item->order->delivered_at)) : '' }}
            </td>
            <td>
                {{ number_format($item->order->cost, 2) }}
            </td>
            <td>{{ $item->method }}</td>
            <td>
                {{ getPaymentStatus($item->status) }}
            </td>
            <td class="name">
                @if ($item->link_payment && in_array($item->status, [\App\Helpers\Constants::PAYMENT_STATUS_PENDING]))
                    <a href="{{$item->link_payment}}" target="_blank" title="Click to pay">
                        {{ trans('fotober.payment.pay_now') }}&nbsp;<i class="ace-icon fa fa-paypal bigger-120"></i>
                    </a>
                @elseif($item->date_success && in_array($item->status, [\App\Helpers\Constants::PAYMENT_STATUS_SUCCESS]))
                    {{ ($item->date_success) ? date('d/m/Y H:i', strtotime($item->date_success)) : '' }}
                @endif
            </td>
            <td >
                <div class="text-center">
                    <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
