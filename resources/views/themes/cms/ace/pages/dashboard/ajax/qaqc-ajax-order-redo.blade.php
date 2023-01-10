<div class="table-responsive">
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('fotober.common.no') }}</th>
            <th class="text-center">{{ trans('fotober.order.form_name') }}</th>
            <th class="text-center">{{ trans('fotober.order.form_customer') }}</th>
            <th class="text-center">{{ trans('fotober.common.col_deadline') }}</th>
            <th class="text-center">{{ trans('fotober.common.col_status') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->name }}</td>
            <td>
                {{ $item->customer->fullname }}
                <br>
                <span style="font-size: 12px; color: #adadad">{{ $item->customer->email }}</span>
            </td>
            <td class="text-center">
                {{ date('d/m/Y H:i', strtotime($item->deadline)) }}
            </td>
            <td class="text-left">
                {{ getOrderStatus($item->status) }}
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
