<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th class="name">Tên nhóm</th>
            <th>Mã nhóm</th>
            <th>Mô tả</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="no">{{ $key + 1 }}</td>
            <td class="name font-weight-500">{{ $item->name }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->description }}</td>
            <td>
                <select name="status" id="status" class="form-control" disabled>
                    @foreach($status as $status_key => $val)
                    <option value="{{ $status_key }}" {{ ($item->status == $status_key) ? 'selected' : ''}}>{{ $val }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
