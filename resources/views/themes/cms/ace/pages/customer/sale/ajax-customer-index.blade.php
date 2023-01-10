<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
            <tr>
                <th class="no">{{ trans('fotober.common.no') }}</th>
                <th class="name">{{ trans('fotober.customer.fullname') }}</th>
                <th >{{ trans('fotober.customer.phone') }}</th>
                <th >{{ trans('fotober.customer.email') }}</th>
                <th >{{ trans('fotober.customer.email_paypal') }}</th>
                @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                    <th >{{ trans('fotober.common.col_assign') }}</th>
                @else
                    <th >{{ trans('fotober.common.col_update') }}</th>
                @endif
                <th >{{ trans('fotober.common.col_status') }}</th>
                <th  style="min-width: 150px !important;">{{ trans('fotober.common.col_action') }}</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
            <tr>
                <td class="no">{{ $offset + $key + 1 }}</td>
                <td class="name w-200 font-weight-500">
                    <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                        {{ $item->fullname }}
                    </a>
                </td>
                <td>
                    {{$item->phone}}
                </td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->email_paypal }}</td>
                @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                    <td>
                        <select class="form-control" onchange="updateAssignSale({{ $item->id }}, $(this).val())" style="width: 150px">
                            <option value="-1">{{ trans('fotober.common._select_sale_') }}</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}" {{ ($sale->id == $item->manager_by) ? 'selected' : '' }}>{{ $sale->email}}</option>
                            @endforeach
                        </select>
                    </td>
                @else
                    <td>
                        {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}
                    </td>
                @endif
                <td>{{ getCustomerStatus($item->status) }}</td>
                <td>
                    <div class="hidden-sm hidden-xs btn-group">
                        <a class="mr-2" href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('sale_customer_edit', ['id' => $item->id ]) }}" title="Click to editing">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        @if (\Illuminate\Support\Facades\Auth::user()->is_admin)
                        <a class="ml-2 color-red" href="{{ route('sale_customer_delete', ['id' => $item->id ]) }}" onclick="return confirm('{{ trans('fotober.common.confirm_delete') }}');" title="Click to Delete">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        @endif
                    </div>
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
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
