<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-custome table-bordered table-small">
            <thead>
            <tr>
                <th class="text-center">{{ trans('fotober.common.no') }}</th>
                <th class="text-center">{{ trans('fotober.common.type_input') }}</th>
                <th class="text-center">{{ trans('fotober.output.link') }}</th>
                <th class="text-center">{{ trans('fotober.common.col_create') }}</th>
                <th class="text-center">{{ trans('fotober.common.col_action') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($outputs as $key => $item)
                <tr>
                    <td class="text-center">{{ ($key+ 1) }}</td>
                    <td class="text-center">{{ $item->type }}</td>
                    <td class="text-left">{{ $item->link }}</td>
                    <td class="text-center">{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                    <td class="text-center">
                        <a href="javascript:void(0)" class="color-red" onclick="deleteItem({{ $item->order_id }}, {{ $item->customer_id }}, {{ $item->id }})" title="Click to Delete">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">{{ trans('fotober.common.no_data') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <a
  href="https://www.dropbox.com/s/u0bdwmkjmqld9l2/dbx-supporting-distributed-work.gif?dl=0"
  class="dropbox-embed"
  data-height="300px"
  data-width="600px"
></a>
    <div>
        <form class="form-horizontal" role="form" id="form-output"  enctype="multipart/form-data">
            {{-- CSRF --}}
            <div class="form-group m-0">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" id="order_id" value="{{ $order_id }}" class="form-control">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer_id }}" class="form-control">
            </div>
            <div class="row">
                {{-- Tên yêu cầu --}}
                <div class="col-lg-6">
                    <label class="control-label no-padding-right mb-2" for="link">
                        {{ trans('fotober.output.link') }}&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <input type="text" name="link" id="link" value="{{ old('link') }}" class="form-control">
                    @if ($errors->has('link'))<span class="validate-error">{{ $errors->first('link') }}</span>@endif
                </div>
                <div class="col-lg-4">
                    <label class="control-label no-padding-right mb-2" for="link">
                        Kiểu Output&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <select class="form-control" id="type" name="type" style="">
                            <option value="IMAGE">IMAGE</option>
                            <option value="VIDEO">VIDEO</option>
                    </select>
                    @if ($errors->has('type'))<span class="validate-error">{{ $errors->first('type') }}</span>@endif
                </div>
                <div class="col-lg-2">
                    <label class="control-label no-padding-right mb-2" for="notes">{{ trans('fotober.common.col_action') }}</label>
                    <br>
                    <button type="button" class="btn btn-primary" id="btn-add-output" style="width: 100%">
                        {{ trans('fotober.common.btn_create') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
        /* Hàm thực hiện thêm item */
        $('#btn-add-output').click(function (){
            $('#data-loading').show();

            var order_id = $('#order_id').val();
            var customer_id = $('#customer_id').val();
            var link = $('#link').val();
            var type = $('#type').val();

            if (link) {
                $('#btn-add-output').attr('disabled', true);
                $('#spinner-update').show();

                var data = {
                    _token: '{{ csrf_token() }}',
                    response_type: 'JSON',
                    order_id: order_id,
                    customer_id: customer_id,
                    link: link,
                    type: type
                }

                $.ajax({
                    url: '{{ route('sale_order_output_add') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: data,
                    success: function(result) {
                        resetForm();

                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-add-output').attr('disabled', false);

                        if (result.code == 200) {
                            /* Cập nhật lại ds item */
                            reloadOutputList(result.data.order_id, result.data.customer_id);
                            /* Cập nhật lại ds order */
                            changePage(1);
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-add-output').attr('disabled', false);
                    }
                });
            } else {
                alert('Bạn vui lòng, nhập đầy đủ thông tin yêu cầu')
            }

        });
    });

    /* Hàm xóa item chi tiết */
    function deleteItem(order_id, customer_id, id){
        var con_firm = confirm('{{ trans('fotober.common.confirm_delete') }}');
        if (con_firm) {
            var data = {
                _token: '{{ csrf_token() }}',
                response_type: 'JSON',
                id: id,
                order_id: order_id,
                customer_id: customer_id
            }

            $.ajax({
                url: '{{ route('sale_order_output_delete') }}',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(result) {
                    if (result.code == 200) {
                        /* Cập nhật lại ds item */
                        reloadOutputList(result.data.order_id, result.data.customer_id);
                        /* Cập nhật lại ds order */
                        changePage(1);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        } else {
            return false;
        }
    }

    /* Hàm tải lại ds item */
    function reloadOutputList(order_id, customer_id){
        $.ajax({
            url: '{{ route('sale_order_output_list') }}',
            type: 'POST',
            dataType: 'HTML',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                customer_id: customer_id
            },
            success: function(result) {
                $('#ajax_output').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {}
        });
    }

    function resetForm() {
        $('#link').val('');
    }



</script>
