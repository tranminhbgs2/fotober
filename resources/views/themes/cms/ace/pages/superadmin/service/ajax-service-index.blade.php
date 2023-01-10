<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th>Ảnh đại diện</th>
            <th class="name">Tên dịch vụ</th>
            <th>Ngày tạo</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>Xử lý</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="no">{{ $offset + $key + 1 }}</td>
            <td>
                <img src="{{ asset($item->image) }}" style="max-width: 150px">
            </td>
            <td class="name">
                <span class="font-weight-500 d-block">{{ $item->name }}</span>
                <span style="color: #adadad; font-size: 11px">Mã:&nbsp;{{ $item->code }}</span>
            </td>
            <td>
                {{ ($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '' }}
            </td>
            <td>
                <select name="status" id="status" class="form-control" onchange="changeStatus({{ $item->id }}, $(this).val())">
                    @foreach($status as $status_key => $val)
                    <option value="{{ $status_key }}" {{ ($item->status == $status_key) ? 'selected' : ''}}>{{ $val }}</option>
                    @endforeach
                </select>
            </td>
            <td style="min-width: 100px;">
                <a href="{{ route('superadmin_edit_service', ['id' => $item->id ]) }}" title="Click to edit">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
