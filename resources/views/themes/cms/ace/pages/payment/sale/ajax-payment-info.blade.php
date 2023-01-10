<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table class="table table-custome table-bordered table-centered table-small mb-0">
            <tbody>
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_name') }}</td>
                    <td class="text-left">{{ $payment->order->name }}</td>
                </tr>
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_code') }}</td>
                    <td class="text-left">{{ $payment->order->code }}</td>
                </tr>
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.cost') }}</td>
                    <td class="text-left">{{ number_format($payment->order->cost, 2) }}</td>
                </tr>
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.common.col_status') }}</td>
                    <td class="text-left">{{ getOrderStatus($payment->order->status) }}</td>
                </tr>
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.payment.detail_title') }}</td>
                    <td>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background: #f5f5f5;">{{ trans('fotober.common.no') }}</th>
                                        <th style="background: #f5f5f5;">{{ trans('fotober.payment.description') }}</th>
                                        <th style="background: #f5f5f5;">{{ trans('fotober.payment.quantity') }}</th>
                                        <th style="background: #f5f5f5;">{{ trans('fotober.payment.price') }}</th>
                                        <th style="background: #f5f5f5;">{{ trans('fotober.payment.amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; $total = 0; @endphp
                                    @forelse($payment->details as $key => $item)
                                        @php $subtotal += $item->amount; @endphp
                                        <tr>
                                            <td>{{ ($key+ 1) }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ number_format($item->quantity, 2) }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">{{ trans('fotober.common.no_data') }}</td>
                                        </tr>
                                    @endforelse
                                    @if ($payment->details)
                                        <tr>
                                            <td class="text-right font-weight-500" colspan="4">Subtotal</td>
                                            <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right font-weight-500" colspan="4">Discount</td>
                                            <td class="text-right">{{ number_format(0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right font-weight-500" colspan="4">Total</td>
                                            <td class="text-right">{{ number_format($subtotal, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>    
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
