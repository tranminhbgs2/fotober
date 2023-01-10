<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th>{{ trans('fotober.common.col_create') }}</th>
            <th class="name text-left">{{ trans('fotober.order.form_name') }}</th>
            <th>{{ trans('fotober.order.form_customer') }}</th>
            <th>{{ trans('fotober.common.col_service') }}</th>
            <th>{{ trans('fotober.order.require') }}</th>
            <th>{{ trans('fotober.common.col_deadline') }}</th>
            <th>{{ trans('fotober.common.col_assign') }}</th>
            <th>{{ trans('fotober.common.col_forward') }}</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.order.total_payment') }}</th>
            <th style="min-width: 100px;">{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <!-- STT -->
            <td class="no">{{ $offset + $key + 1 }}</td>
            {{-- Create --}}
            <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
            <!-- Tên order -->
            <td class="name font-weight-500">
                <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                    {{ $item->name }}
                </a>
            </td>
            {{-- Customer --}}
            <td>{{ $item->customer->fullname }} ({{ $item->customer->email }})</td>
            {{-- Service --}}
            <td>{{ $item->service->name }}</td>
            <!-- Yêu cầu -->
            <td>
                @if(($item->assigned_sale_id > 0 && $item->status >= \App\Helpers\Constants::ORDER_STATUS_NEW))
                <a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="showRequirement({{ $item->id }}, {{ $item->customer->id }})" title="Click to show requirement list">
                    <i class="ti-align-center"></i>
                    {{-- <span class="badge badge-light">{{count($item->requirementDone)}}</span> --}}
                </a>
                @endif
            </td>
            {{-- deadline --}}
            <td>{{ date('d/m/Y H:i', strtotime($item->deadline)) }}</td>
            <!-- Gán sale member -->
            @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                <td>
                    @foreach($sales as $sale)
                        {{ ($sale->id == $item->assigned_sale_id) ? $sale->email : '' }}
                    @endforeach
                </td>
            @else
                <td>
                    {{ ($item->updated_at) ? date('d/m/Y H:i', strtotime($item->updated_at)) : '' }}
                </td>
            @endif
            <!-- Action chuyển tiếp: nếu đã gán cho admin và trạng thái >= 2 thì show thời gian gán -->
            <td>
                @if($item->assigned_admin_id > 0 && $item->status >= \App\Helpers\Constants::ORDER_STATUS_PENDING)
                    {{ ($item->sent_admin_at) ? date('d/m/Y H:i', strtotime($item->sent_admin_at)) : '' }}
                @else
                    {{-- Chuyển tiếp những order đang có trạng thái là 1 (NEW), chỉ có sale member mới có --}}
                    @if (\Illuminate\Support\Facades\Auth::user()->is_admin == 0)
                        @if($item->assigned_sale_id > 0 && $item->status == \App\Helpers\Constants::ORDER_STATUS_NEW)
                            <a href="{{ route('sale_order_forward_to_admin', ['id' => $item->id ]) }}"
                            onclick="return confirm('{{ trans('fotober.common.confirm_perform') }}');"
                            title="Click to forward to Admin" class="btn btn-xs btn-success">
                                <i class="ti-angle-double-right"></i>
                            </a>
                        @endif
                    @endif
                @endif
            </td>
            <!-- Trạng thái -->
            <td>
                {{ getOrderStatus($item->status) }}
            </td>
            <!-- Trạng thái thanh toán -->
            <td>{{ is_numeric($item->total_payment) ? number_format($item->total_payment, 2) : '' }}</td>
            <!-- Xử lý -->
            <td style="min-width: 100px;">
                <div class="btn-group">
                    <a class="mr-2" href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                        <i class="fa fa-exclamation-circle"></i>
                    </a>
                    <a href="{{ route('superadmin_order_detail', ['id' => $item->id ]) }}" title="Click to show detail">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                    @if ($item->status < 3)
                        <a class="ml-2" href="{{ route('superadmin_order_edit', ['id' => $item->id ]) }}" title="Click to add output">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center">{{ trans('fotober.common.no_data') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
