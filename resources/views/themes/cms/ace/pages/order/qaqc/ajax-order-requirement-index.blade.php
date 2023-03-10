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
                    @if ($order->status <= \App\Helpers\Constants::ORDER_STATUS_CHECKED || $order->status == \App\Helpers\Constants::ORDER_STATUS_REDO)
                    <td>
                        <select class="form-control custom-select" name="status_" id="status_" onchange="changeStatusRequire({{ $item->id }}, $(this).val())">
                            @foreach($status as $status_key => $status_item)
                                <option value="{{ $status_key }}" {{ ($status_key == $item->status) ? 'selected' : '' }}>
                                    {{ getRequirementStatus($status_key) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    @else
                    <td>
                        {{ getRequirementStatus($item->status) }}
                    </td>
                    @endif
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

    @if ($order->status <= \App\Helpers\Constants::ORDER_STATUS_CHECKED || $order->status == \App\Helpers\Constants::ORDER_STATUS_REDO)
    <div>
        <form class="form-horizontal" role="form" id="form-requirement"  enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12">
                    {{-- CSRF --}}
                    <div class="form-group m-0">
                        {{ csrf_field() }}
                        <input type="hidden" name="requirement_order_id" id="requirement_order_id" value="{{ $order_id }}" class="form-control">
                        <input type="hidden" name="requirement_customer_id" id="requirement_customer_id" value="{{ $customer_id }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                {{-- T??n y??u c???u --}}
                <div class="col-lg-5">
                    <div class="form-group mb-3">
                        <label class="control-label mb-2" for="requirement_name">
                            {{ trans('fotober.requirement.name') }}&nbsp;(<span class="form-required"></span>)
                        </label>
                        <br>
                        <input type="text" name="requirement_name" id="requirement_name" value="{{ old('requirement_name') }}" class="form-control">
                        @if ($errors->has('requirement_name'))<span class="validate-error">{{ $errors->first('requirement_name') }}</span>@endif
                    </div>
                    {{-- Tr???ng th??i --}}
                    <div class="form-group mb-3">
                        <label class="control-label mb-2" for="requirement_status">
                            {{ trans('fotober.common.col_status') }}&nbsp;(<span class="form-required"></span>)
                        </label>
                        <br>
                        <select class="form-control" name="requirement_status" id="requirement_status">
                            @foreach($status as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('requirement_status'))<span class="validate-error">{{ $errors->first('requirement_status') }}</span>@endif
                    </div>

                </div>
                {{-- M?? t??? --}}
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="control-label mb-2" for="requirement_description">
                            {{ trans('fotober.requirement.description') }}
                        </label>
                        <br>
                        <textarea class="form-control" name="requirement_dsc" id="requirement_dsc" rows="5">{{ old('requirement_dsc') }}</textarea>
                        @if ($errors->has('requirement_dsc'))<span class="validate-error">{{ $errors->first('requirement_dsc') }}</span>@endif
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="control-label mb-2" for="notes">{{ trans('fotober.common.col_action') }}</label>
                    <br>
                    <button type="button" class="btn btn-primary w-100" id="btn-add-requirement">
                        <i class="ace-icon fa fa-spinner fa-spin white bigger-125" id="spinner-update" style="display: none"></i>&nbsp;
                        {{ trans('fotober.common.btn_create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>
<script type="text/javascript">
    $(document).ready(function (){
        /* H??m th???c hi???n th??m item */
        $('#btn-add-requirement').click(function (){
            $('#data-loading').show();

            var order_id = $('#requirement_order_id').val();
            var customer_id = $('#requirement_customer_id').val();
            var name = $('#requirement_name').val();
            var dsc = $('#requirement_dsc').val();
            var status = $('#requirement_status').val();

            if (name && status >= 0) {
                $('#btn-add-requirement').attr('disabled', true);
                $('#spinner-update').show();

                var data = {
                    _token: '{{ csrf_token() }}',
                    response_type: 'JSON',
                    order_id: order_id,
                    customer_id: customer_id,
                    name: name,
                    dsc: dsc,
                    status: status,
                }

                $.ajax({
                    url: '{{ route('qaqc_order_requirement_add') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: data,
                    success: function(result) {
                        resetForm();

                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-add-requirement').attr('disabled', false);

                        if (result.code == 200) {
                            /* C???p nh???t l???i ds item */
                            reloadRequirementList(result.data.order_id, result.data.customer_id);
                            /* C???p nh???t l???i ds order */
                            changePage(1);
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-add-requirement').attr('disabled', false);
                    }
                });
            } else {
                alert('B???n vui l??ng, nh???p ?????y ????? th??ng tin y??u c???u')
            }

        });
    });

    /**
     * Admin giao order cho Editor member
     * @param order_id
     * @param editor_id
     */
     function changeStatusRequire(id, status_id){
        $('#data-loading').show();

        $.ajax({
            url: '{{ route('qaqc_requirement_change_status_ajax') }}',
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

    /* H??m t???i l???i ds item */
    function reloadRequirementList(order_id, customer_id){
        $.ajax({
            url: '{{ route('qaqc_order_requirement_list') }}',
            type: 'POST',
            dataType: 'HTML',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                customer_id: customer_id
            },
            success: function(result) {
                $('#ajax_requirement').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {}
        });
    }

    function resetForm() {
        $('#name').val('');
        $('#status').val(0);
    }
</script>
