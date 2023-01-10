<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-bordered table-centered table-order table-small">
            <thead>
            <tr>
                <th class="text-center" style="min-width: 80px;">{{ trans('fotober.common.no') }}</th>
                <th class="text-center">URL</th>
                <th class="text-center">{{ trans('fotober.common.col_create') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($outputs as $key => $item)
                <tr>
                    <td style="min-width: 80px;">{{ ($key+ 1) }}</td>
                    <td><a href="{{$item->link}}" target="_blank" title="Click show output">{{$item->link}}</a></p></td>
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
