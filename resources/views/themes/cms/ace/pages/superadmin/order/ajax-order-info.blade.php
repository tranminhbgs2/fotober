<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table class="table table-custome table-bordered table-small mb-0">
            <tbody>
                {{-- Order name --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_name') }}</td>
                    <td>{{ $order->name }}</td>
                </tr>
                {{-- services --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_service_type') }}</td>
                    <td>{{ $order->service->name }}</td>
                </tr>
                {{-- Share link/Upload file --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_link_upload') }}</td>
                    <td>
                        <p>Link:&nbsp;{{ $order->link }}</p>
                        <p>File:&nbsp;{{ $order->upload_file }}</p>
                    </td>
                </tr>
                {{-- Turn Around Time --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_turn_around') }}</td>
                    <td>{{ ($order->turn_arround_time > 0) ? getTurnArroundTime($order->turn_arround_time) : '' }}</td>
                </tr>
                {{-- Deadline --}}
                <tr> 
                    <td class="name font-weight-500">{{ trans('fotober.common.col_deadline') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->deadline )) }}</td>
                </tr>
                {{-- Last Update --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.common.col_update') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->updated_at )) }}</td>
                </tr>
                {{-- Status --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.common.col_status') }}</td>
                    <td>{{ getOrderStatus($order->status) }}</td>
                </tr>
                {{-- Notes(if any) --}}
                <tr>
                    <td class="name font-weight-500">{{ trans('fotober.order.form_note') }}</td>
                    <td>{{ $order->notes }}</td>
                </tr>
                {{-- output --}}
                @if ($order->status >= \App\Helpers\Constants::ORDER_STATUS_EDITED)
                    <tr>
                        <td class="name font-weight-500">{{ trans('fotober.output.title') }}</td>
                        <td>
                            @forelse ($outputs as $item)
                            <p><a href="{{$item->link}}" target="_blank" title="Click show output">{{$item->link}}</a></p>
                            @empty
                                {{ trans('fotober.common.no_data') }}
                            @endforelse
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>               
</div>