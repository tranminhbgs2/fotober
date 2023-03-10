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
                @if (in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_EDITED,\App\Helpers\Constants::ORDER_STATUS_CHECKED,\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
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
{{-- <div class="profile-user-info profile-user-info-striped">
    <!-- T??n order -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.order.form_name') }}</div>
        <div class="profile-info-value">{{ $order->name }}</div>
    </div>
    <!-- Lo???i d???ch v??? -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.order.form_service_type') }}</div>
        <div class="profile-info-value">{{ $order->service->name }}</div>
    </div>
    <!-- File ho???c link ????nh k??m -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.order.form_link_upload') }}</div>
        <div class="profile-info-value" style="max-height: 150px; overflow: scroll">
            <div style="height: 150px;">
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
            </div>
        </div>
    </div>
    <!-- Th???i gian th???c hi???n - turn arround -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.order.form_turn_around') }}</div>
        <div class="profile-info-value">
            {{ ($order->turn_arround_time > 0) ? getTurnArroundTime($order->turn_arround_time) : '' }}
        </div>
    </div>
    <!-- Ng??y ph???i ho??n th??nh - deadline -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.common.col_deadline') }}</div>
        <div class="profile-info-value">{{ date('d/m/Y H:i', strtotime($order->deadline )) }}</div>
    </div>
    <!-- C???p nh???t l???n cu???i -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.common.col_update') }}</div>
        <div class="profile-info-value">{{ date('d/m/Y H:i', strtotime($order->updated_at )) }}</div>
    </div>
    <!-- Tr???ng th??i -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.common.col_status') }}</div>
        <div class="profile-info-value">{{ getOrderStatus($order->status) }}</div>
    </div>
    <!-- Ghi ch?? -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.order.form_note') }}</div>
        <div class="profile-info-value">{{ $order->notes }}</div>
    </div>
    @if (in_array($order->status, [\App\Helpers\Constants::ORDER_STATUS_EDITED,\App\Helpers\Constants::ORDER_STATUS_CHECKED,\App\Helpers\Constants::ORDER_STATUS_DELIVERING,\App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_PAID]))
    <!-- Danh s??ch output -->
    <div class="profile-info-row">
        <div class="profile-info-name">{{ trans('fotober.output.title') }}</div>
        <div class="profile-info-value">
            @forelse ($outputs as $item)
                <p><a href="{{$item->link}}" target="_blank" title="Click show output">{{$item->link}}</a></p>
            @empty
                {{ trans('fotober.common.no_data') }}
            @endforelse
        </div>
    </div>
    @endif
</div>  --}}
