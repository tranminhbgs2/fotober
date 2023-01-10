<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-custome table-centered table-bordered table-small">
            <thead>
            <tr>
                <th>{{ trans('fotober.common.no') }}</th>
                <th>{{ trans('fotober.requirement.name') }}</th>
                <th>{{ trans('fotober.requirement.description') }}</th>
                <th>{{ trans('fotober.common.col_status') }}</th>
                <th>{{ trans('fotober.common.col_create') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($requirements as $key => $item)
                <tr>
                    <td>{{ ($key+ 1) }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        <select class="form-control custom-select" name="status_" id="status_" onchange="changeStatusRequire({{ $item->id }}, $(this).val())" {{($order->status == \App\Helpers\Constants::ORDER_STATUS_EDITING || $order->status == \App\Helpers\Constants::ORDER_STATUS_REDO) ? '' : 'disabled'}}>
                            @foreach($status as $status_key => $status_item)
                                <option value="{{ $status_key }}" {{ ($status_key == $item->status) ? 'selected' : '' }}>
                                    {{ getRequirementStatus($status_key) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
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
<script type="text/javascript">
    $(document).ready(function (){

    });

    /**
     * Admin giao order cho Editor member
     * @param order_id
     * @param editor_id
     */
        function changeStatusRequire(id, status_id){
        $('#data-loading').show();

        $.ajax({
            url: '{{ route('editor_requirement_change_status_ajax') }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                status_id: status_id,
            },
            success: function(result) {
                $('#data-loading').hide();
                changePage(1);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                $('#data-loading').hide();
            }
        });
    }
</script>
