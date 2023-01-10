<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table class="table table-custome table-bordered table-small mb-0">
            <tbody>
                {{-- fullname --}}
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.fullname') }}</td>
                    <td>{{ $customer->fullname }}</td>
                </tr>
                <!-- phone -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.phone') }}</td>
                    <td>{{ $customer->phone }}</td>
                </tr>
                <!-- email -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.email') }}</td>
                    <td>{{ $customer->email }}</td>
                </tr>
                <!-- email_paypal -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.email_paypal') }}</td>
                    <td>{{ $customer->email_paypal }}</td>
                </tr>
                <!-- Ngày sinh nhat -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.birthday') }}</td>
                    <td>
                        @if ($customer->birthday)
                            {{ date('d/m/Y', strtotime($customer->birthday )) }}
                        @endif
                    </td>
                </tr>
                <!-- Gioi tinh -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.gender') }}</td>
                    <td>
                        @if ($customer->gender == 1)
                            {{ trans('fotober.common.male') }}
                        @else
                            {{ trans('fotober.common.female') }}
                        @endif
                    </td>
                </tr>
                <!-- Dia chi -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.address') }}</td>
                    <td>{{ $customer->address }}</td>
                </tr>
                <!-- Tong don hang -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.total_order') }}</td>
                    <td>{{ $customer->total_order }}</td>
                </tr>
                <!-- Cập nhật lần cuối -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.common.col_update') }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($customer->updated_at )) }}</td>
                </tr>
                <!-- Trạng thái -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.common.col_status') }}</td>
                    <td>{{ getCustomerStatus($customer->status) }}</td>
                </tr>
                <!-- Ghi chú -->
                <tr>
                    <td class="name font-weight-500 text-center">{{ trans('fotober.customer.notes') }}</td>
                    <td>{{ $customer->notes }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
