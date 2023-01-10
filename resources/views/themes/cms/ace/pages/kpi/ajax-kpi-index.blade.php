<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th>{{ trans('fotober.kpi.order_name') }}</th>
            <th>{{ trans('fotober.kpi.service') }}</th>
            <th>{{ trans('fotober.kpi.customer') }}</th>
            @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                <th>{{ trans('fotober.kpi.sale') }}</th>
            @endif
            <th>{{ trans('fotober.kpi.total_payment') }}</th>
            <th>{{ trans('fotober.kpi.commission') }}</th>
            <th>{{ trans('fotober.kpi.created_at') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <!-- STT -->
            <td class="no">{{ $offset + $key + 1 }}</td>
            <!-- Tên order -->
            <td class="name w-200">
                <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                    {{ $item->name_order }}
                </a>
            </td>
            <!-- Dịch vụ -->
            <td class="name w-200">{{ $item->service->name }}</td>
            <!-- Yêu cầu -->
            <td class="name w-200">{{ $item->customer->fullname }}</td>
            <!-- Gán sale member -->
            @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                <td>
                    {{ $item->sale->fullname }}
                </td>
            @endif
            <!-- Action chuyển tiếp: nếu đã gán cho admin và trạng thái >= 2 thì show thời gian gán -->
            <td>{{ $item->total_payment}}$</td>
            <!-- Trạng thái -->
            <td>{{ $item->commission}}$</td>
            <!-- Trạng thái thanh toán -->
            <td>
                {{ ($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
