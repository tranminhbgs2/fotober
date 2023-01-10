<div class="table-responsive">
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('fotober.common.no') }}</th>
            <th class="text-center">Ảnh đại diện</th>
            <th class="text-center">Tên dịch vụ</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-center">{{ trans('fotober.common.col_status') }}</th>
            <th class="text-center">Xử lý</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="text-center">{{ $offset + $key + 1 }}</td>
            <td class="text-center">
                <img src="{{ asset($item->image) }}" style="max-width: 150px">
            </td>
            <td>
                {{ $item->name }}
                <br>
                <span style="color: #adadad; font-size: 11px">Mã:&nbsp;{{ $item->code }}</span>
            </td>
            <td class="text-center">
                {{ ($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '' }}
            </td>
            <td>
                <select name="status" id="status" class="form-control" onchange="changeStatus({{ $item->id }}, $(this).val())">
                    @foreach($status as $status_key => $val)
                    <option value="{{ $status_key }}" {{ ($item->status == $status_key) ? 'selected' : ''}}>{{ $val }}</option>
                    @endforeach
                </select>
            </td>
            <td class="text-center">
                <a href="{{ route('superadmin_edit_service', ['id' => $item->id ]) }}" title="Click to edit">
                    <button class="btn btn-xs btn-default">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
