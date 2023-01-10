<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th class="name">{{ trans('fotober.customer.fullname') }}</th>
            <th>{{ trans('fotober.customer.phone') }}</th>
            <th>{{ trans('fotober.customer.email') }}</th>
            <th>{{ trans('fotober.customer.email_paypal') }}</th>
            <th>{{ trans('fotober.common.col_update') }}</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
            <tr>
                <td class="no">{{ $offset + $key + 1 }}</td>
                <td class="name font-weight-500">
                    <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                        {{ $item->fullname }}
                    </a>
                </td>
                <td>
                    {{$item->phone}}
                </td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->email_paypal }}</td>
                <td>
                    {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}
                </td>
                <td style="min-width: 200px">
                    <select name="status_ajax" id="status_ajax" class="form-control" onchange="changeStatus({{ $item->id }}, $(this).val())">
                        @foreach($status as $status_key => $status_value)
                            <option value="{{ $status_key }}" {{ ($item->status == $status_key) ? 'selected' : '' }}>{{ $status_value }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="min-width: 100px;">
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                            <i class="fa fa-exclamation-circle"></i>
                        </a>
                    </div>
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
