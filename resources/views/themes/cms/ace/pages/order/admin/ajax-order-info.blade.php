<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table class="table table-custome table-bordered table-small mb-0">
            <tbody>
                <tr>
                    {{-- Order name --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_name') }}</td>
                    <td>{{ $order->name }}</td>
                </tr>
                <tr>
                    {{-- Order code --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_code') }}</td>
                    <td>{{ $order->code }}</td>
                </tr>
                <tr>
                    {{-- services --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_service_type') }}</td>
                    <td>{{ $order->service->name }}</td>
                </tr>
                <tr>
                    {{-- Share link/Upload file --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_link_upload') }}</td>
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
                    {{-- Turn Around Time --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_turn_around') }}</td>
                    <td>{{ ($order->turn_arround_time > 0) ? getTurnArroundTime($order->turn_arround_time) : '' }}</td>
                </tr>
                <tr>
                    {{-- Deadline --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.common.col_deadline') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->deadline )) }}</td>
                </tr>
                <tr>
                    {{-- Last Update --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.common.col_update') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->updated_at )) }}</td>
                </tr>
                <tr>
                    {{-- Status --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.common.col_status') }}</td>
                    <td>{{ getOrderStatus($order->status) }}</td>
                </tr>
                <tr>
                    {{-- Notes(if any) --}}
                    <td class="name font-weight-500 text-center">{{ trans('fotober.order.form_note') }}</td>
                    <td>{{ $order->notes }}</td>
                </tr>
                @if (in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
                <tr>
                        {{-- output --}}
                        <td class="name font-weight-500 text-center">{{ trans('fotober.output.title') }}</td>
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
