<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th class="name">Họ và tên</th>
            <th>Số điện thoại</th>
            <th>Email tài khoản</th>
            <th>Loại tài khoản</th>
            <th>Ngày tạo</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="no">{{ $offset + $key + 1 }}</td>
            <td class="name">
                <b>{{ $item->fullname }}</b>
                <br>
                <span style="color: #adadad; font-size: 11px">Ngày sinh:&nbsp;{{ ($item->birthday) ? date('d/m/Y', strtotime($item->birthday)) : '' }}</span>
                <br>
                <span style="color: #adadad; font-size: 11px">Giới tính:&nbsp;{{ ($item->gender == 1) ? 'Nam' : 'Nữ' }}</span>
            </td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->email }}</td>
            <td>
                {{ $item->account_type }}
                <br>
                <span style="color: #adadad; font-size: 11px">{{ ($item->is_admin == 1) ? 'Quản lý' : 'Nhân viên' }}</span>
            </td>
            <td>
                {{ ($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '' }}
            </td>
            <td>
                @if ($item->account_type != \App\Helpers\Constants::ACCOUNT_TYPE_SUPER_ADMIN)
                <select name="status" id="status" class="form-control" onchange="changeStatus({{ $item->id }}, $(this).val())">
                    @foreach($status as $status_key => $val)
                    <option value="{{ $status_key }}" {{ ($item->status == $status_key) ? 'selected' : ''}}>{{ $val }}</option>
                    @endforeach
                </select>
                @endif
            </td>
            <td style="min-width: 100px;">
                <a href="{{ route('superadmin_edit_user', ['id' => $item->id ]) }}" title="Click to edit">
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
