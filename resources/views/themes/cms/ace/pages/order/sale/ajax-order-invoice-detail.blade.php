<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.common.no') }}</th>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.payment.description') }}</th>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.payment.quantity') }}</th>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.payment.price') }}</th>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.payment.amount') }}</th>
                <th class="text-center" style="background: #f8f9fa; font-weight: 600;">{{ trans('fotober.common.col_action') }}</th>
            </tr>
            </thead>
            <tbody>
            @if($payment && isset($payment->details))
                @php $total = 0; @endphp
                @forelse($payment->details as $key => $item)
                    @php $total += $item->amount; @endphp
                    <tr>
                        <td class="text-center">{{ ($key+ 1) }}</td>
                        <td class="text-left">{{ $item->description }}</td>
                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                        <td class="text-center">
                            @if (in_array($payment->order->status, [\App\Helpers\Constants::ORDER_STATUS_COMPLETED]))
                            <a href="javascript:void(0)" onclick="deleteItem({{ $item->order_id }}, {{ $item->id }})" title="Click to Delete">
                                <button class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </button>
                            </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">{{ trans('fotober.common.no_data') }}</td>
                    </tr>
                @endforelse
                @if ($payment->details)
                    <tr>
                        <td class="text-right font-weight-500" colspan="5">Total</td>
                        <td class="text-right">{{ number_format($total, 2) }}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <td colspan="10">{{ trans('fotober.common.no_data') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    @if (in_array($payment->order->status, [\App\Helpers\Constants::ORDER_STATUS_COMPLETED]))

    <div>
        <form class="form-horizontal" role="form" id="form-update-invoice-item"  enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12">
                    {{-- CSRF --}}
                    <div class="form-group mb-0">
                        {{ csrf_field() }}
                        <input type="hidden" name="payment_id" id="payment_id" value="{{ $payment->id }}" class="form-control">
                        <input type="hidden" name="order_id" id="order_id" value="{{ $payment->order_id }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    {{-- Tên hạng mục bóc tách hóa đơn --}}
                    <label class="control-label no-padding-right mb-2" for="description">
                        {{ trans('fotober.payment.description') }}&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control">
                    @if ($errors->has('description'))<span class="validate-error">{{ $errors->first('description') }}</span>@endif
                </div>
                <div class="col-lg-2">
                    <label class="control-label no-padding-right mb-2" for="quantity">
                        {{ trans('fotober.payment.quantity') }}&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <input type="number" name="quantity" id="quantity" min="1" max="1000000" value="{{ old('quantity') }}" class="form-control">
                    @if ($errors->has('quantity'))<span class="validate-error">{{ $errors->first('quantity') }}</span>@endif
                </div>
                <div class="col-lg-2">
                    <label class="control-label no-padding-right mb-2" for="price">
                        {{ trans('fotober.payment.price') }}&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <input type="number" name="price" id="price" min="0" max="10000" step="0.01" value="{{ old('price') }}" class="form-control">
                    @if ($errors->has('price'))<span class="validate-error">{{ $errors->first('price') }}</span>@endif
                </div>
                <div class="col-lg-2">
                    <label class="control-label no-padding-right mb-2" for="amount">
                        {{ trans('fotober.payment.amount') }}&nbsp;(<span class="form-required"></span>)
                    </label>
                    <br>
                    <input type="text" name="amount" id="amount" value="{{ old('amount') }}" readonly class="form-control">
                    @if ($errors->has('amount'))<span class="validate-error">{{ $errors->first('amount') }}</span>@endif
                </div>
                <div class="col-lg-2">
                    <label class="control-label no-padding-right mb-2" for="notes">{{ trans('fotober.common.col_action') }}</label>
                    <br>
                    <button type="button" class="btn btn-primary w-100" id="btn-update-invoice-item">
                        <i class="ace-icon fa fa-spinner fa-spin white bigger-125" id="spinner-update" style="display: none"></i>&nbsp;
                        {{ trans('fotober.common.btn_add_item') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
<script type="text/javascript">
    $(document).ready(function (){
        $('#quantity').blur(function (){
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var amount = 0;
            if (quantity && price) {
                amount = parseFloat(quantity)*parseFloat(price);
            }
            $('#amount').val(amount);
        });

        $('#price').blur(function (){
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var amount = 0;
            if (quantity && price) {
                amount = parseFloat(quantity)*parseFloat(price);
            }
            $('#amount').val(amount);
        });

        /* Hàm thực hiện thêm item */
        $('#btn-update-invoice-item').click(function (){
            $('#data-loading').show();

            var order_id = $('#order_id').val();
            var payment_id = $('#payment_id').val();
            var description = $('#description').val();
            var quantity = $('#quantity').val();
            var price = $('#price').val();
            var amount = $('#amount').val();

            if (description && quantity > 0 && price >= 0) {
                $('#btn-update-invoice-item').attr('disabled', true);
                $('#spinner-update').show();

                var data = {
                    _token: '{{ csrf_token() }}',
                    response_type: 'JSON',
                    order_id: order_id,
                    payment_id: payment_id,
                    description: description,
                    quantity: quantity,
                    price: price,
                    amount: amount,
                }

                $.ajax({
                    url: '{{ route('sale_order_update_payment_detail') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: data,
                    success: function(result) {
                        resetForm();

                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-update-invoice-item').attr('disabled', false);

                        if (result.code == 200) {
                            /* Cập nhật lại ds item */
                            reloadPaymentDetail(result.data.order_id);
                            /* Cập nhật lại ds order */
                            changePage(1);
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#data-loading').hide();
                        $('#spinner-update').hide();
                        $('#btn-update-invoice-item').attr('disabled', false);
                    }
                });
            } else {
                alert('Bạn vui lòng, nhập đầy đủ thông tin')
            }

        });
    });

    /* Hàm xóa item chi tiết */
    function deleteItem(order_id, payment_detail_id){
        var con_firm = confirm('{{ trans('fotober.common.confirm_delete') }}');
        if (con_firm) {
            var data = {
                _token: '{{ csrf_token() }}',
                response_type: 'JSON',
                order_id: order_id,
                payment_detail_id: payment_detail_id,
            }

            $.ajax({
                url: '{{ route('sale_order_delete_payment_detail') }}',
                type: 'POST',
                dataType: 'JSON',
                data: data,
                success: function(result) {
                    if (result.code == 200) {
                        /* Cập nhật lại ds item */
                        reloadPaymentDetail(result.data.order_id);
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
    function reloadPaymentDetail(order_id){
        $.ajax({
            url: '{{ route('sale_order_show_invoice_detail') }}',
            type: 'POST',
            dataType: 'HTML',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: order_id
            },
            success: function(result) {
                $('#ajax_invoice_detail').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {}
        });
    }

    function resetForm() {
        $('#description').val('');
        $('#quantity').val('');
        $('#price').val('');
        $('#amount').val('');
    }



</script>
