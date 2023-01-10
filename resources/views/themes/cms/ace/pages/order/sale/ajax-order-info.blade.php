<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-custome table-bordered table-small mb-0">
            <tbody>
                <tr>
                    {{-- Order name --}}
                    <td class="name font-weight-500">{{ trans('fotober.order.form_name') }}</td>
                    <td>{{ $order->name }}</td>
                </tr>
                <tr>
                    {{-- Order code --}}
                    <td class="name font-weight-500">{{ trans('fotober.order.table.order_id') }}</td>
                    <td>{{ $order->code }}</td>
                </tr>
                <tr>
                    {{-- services --}}
                    <td class="name font-weight-500">{{ trans('fotober.order.order_type') }}</td>
                    <td>{{ $order->service->name }}</td>
                </tr>
                <tr>
                    {{-- Share link/Upload file --}}
                    <td class="name font-weight-500">Input</td>
                    <td>
                        @if(count($inputs) > 0)
                            @php
                                $i = 0;
                            @endphp
                            @forelse ($inputs as $item)
                                @if ($item->type == 'LINK')
                                <p>Link:&nbsp;
                                    <a href="{{ $item->link }}" title="Click to Show Link" target="_blank">
                                    <i class="ace-icon fa fa-link bigger-120"></i>
                                    </a>
                                </p>
                                @else
                                @php
                                    $i++;
                                @endphp
                                    <p>File:&nbsp;
                                        <a href="{{asset('storage/'.$item->file)}}" title="Click to Show Picture" target="_blank">
                                        {{$item->name}}
                                        </a>
                                    </p>
                                @endif
                            @empty
                                {{ trans('fotober.common.no_data') }}
                            @endforelse
                            @if ($i > 0)

                            <a href="{{ route('download_zip', ['order_id' => $order->id, 'user_id' => $order->customer_id ]) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Dowload All</a>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    {{-- Send Request --}}
                    <td class="name font-weight-500">{{ trans('fotober.common.order_submited') }}</td>
                    <td>{{ (($order->sent_sale_at) ? date('d/m/Y H:i', strtotime($order->sent_sale_at )) : '') }}</td>
                </tr>
                <tr>
                    {{-- Turn Around Time --}}
                    <td class="name font-weight-500">{{ trans('fotober.payment.turnaround') }}</td>
                    <td>{{ ($order->turn_arround_time > 0) ? getTurnArroundTime($order->turn_arround_time) : '' }}</td>
                </tr>
                <tr>
                    {{-- Deadline --}}
                    <td class="name font-weight-500">{{ trans('fotober.order.delivery_at') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->sent_sale_at. ' + '.$order->turn_arround_time.' hours' )) }}</td>
                </tr>
                <tr>
                    {{-- Last Update --}}
                    <td class="name font-weight-500">{{ trans('fotober.common.col_update') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->updated_at )) }}</td>
                </tr>
                <tr>
                    {{-- Status --}}
                    <td class="name font-weight-500">{{ trans('fotober.common.col_status') }}</td>
                    <td>{{ getOrderStatus($order->status) }}</td>
                </tr>
                <tr>
                    {{-- Notes(if any) --}}
                    <td class="name font-weight-500">{{ trans('fotober.order.instruction') }}</td>
                    <td>{{ $order->notes }}</td>
                </tr>
                @if (in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_EDITED,\App\Helpers\Constants::ORDER_STATUS_CHECKED,\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
                <tr>
                        {{-- output --}}
                        <td class="name font-weight-500">{{ trans('fotober.output.title') }}</td>
                        <td>
                            @if(count($outputs) > 0)
                                @forelse ($outputs as $item)
                                    <a href="{{$item->link}}" target="_blank" title="Click show output">{{$item->link}}</a>
                                @empty
                                    {{ trans('fotober.common.no_data') }}
                                @endforelse
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    @if ($order->status != \App\Helpers\Constants::ORDER_STATUS_DRAFT)
    <a href="{{ route('sale_order_detail', ['id' => $order->id]) }}" class="btn btn-info waves-effect">
        {{ trans('fotober.order.btn_detail') }}
        <i class="ti-angle-right" style="font-size: 12px;"></i>
    </a>
    @endif
</div>
