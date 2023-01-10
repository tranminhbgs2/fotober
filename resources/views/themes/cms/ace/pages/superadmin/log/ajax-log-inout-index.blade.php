<div class="table-responsive">
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('fotober.common.no') }}</th>
            <th class="text-center">Tài khoản đăng nhập</th>
            <th class="text-center">Loại hành động</th>
            <th class="text-center">Thời gian thực hiện</th>
            <th class="text-center">Kết quả</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="text-center">{{ $offset + $key + 1 }}</td>
            <td>{{ $item->account_input }}</td>
            <td>{{ $item->action_type }}</td>
            <td class="text-center">{{ ($item->logged_in_at) ? date('d/m/Y H:i', strtotime($item->logged_in_at)) : '' }}</td>
            <td>{{ $item->result }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
