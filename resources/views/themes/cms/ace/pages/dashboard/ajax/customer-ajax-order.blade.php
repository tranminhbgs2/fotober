<div class="table-responsive">
    <table id="simple-table" class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th class="text-center">{{ trans('fotober.common.no') }}</th>
            <th class="text-center">{{ trans('fotober.order.form_name') }}</th>
            <th class="text-center">File/URL</th>
            <th class="text-center">{{ trans('fotober.common.col_update') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->link }}</td>
            <td class="text-center">
                {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}
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
