<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-custome table-centered table-bordered table-small mb-0">
            <thead>
            <tr>
                <th class="no">{{ trans('fotober.common.no') }}</th>
                <th class="name">{{ trans('fotober.requirement.name') }}</th>
                <th>{{ trans('fotober.common.col_status') }}</th>
                <th>{{ trans('fotober.common.col_create') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requirements as $key => $item)
                <tr>
                    <td>{{ ($key+ 1) }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ getRequirementStatus($item->status) }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">{{ trans('fotober.common.no_data') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
