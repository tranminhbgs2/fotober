<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th>{{ trans('fotober.order.table.order_id') }}</th>
            <th>{{ trans('fotober.order.table.customer_name') }}</th>
            <th>{{ trans('fotober.order.table.service_name') }}</th>
            <th>{{ trans('fotober.common.col_deadline') }}</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.order.table.note') }}</th>
            @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                <th>{{ trans('fotober.common.col_assign') }}</th>
            @else
            <th style="min-width: 150px !important;">{{ trans('fotober.common.col_action') }}</th>
            @endif
            <th>{{ trans('fotober.order.table.invoice') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr>
            <!-- STT -->
            <td class="no">{{ $offset + $key + 1 }}</td>
            <!-- Tên order -->
            
            @if (\Illuminate\Support\Facades\Auth::user()->is_admin || $item->status > \App\Helpers\Constants::ORDER_STATUS_PENDING)
            <td class="name w-200 font-weight-500">
                <a href="{{ route('sale_order_summary', ['id' => $item->id ]) }}" title="Click to show Order Sumary">
                    {{ $item->code }}
                </a>
            </td>
            @else
            <td class="name font-weight-500">
                <a href="javascript:void(0)" title="Click to show Order Sumary">
                    {{ $item->code }}
                </a>
            </td>
            @endif
            <!-- Tên KH -->
            <td class="name font-weight-500">
                <a href="javascript:void(0)" onclick="getDataByCustomerId({{ $item->customer_id }})" title="Click to show list by customer">
                    {{ $item->customer->fullname }}
                </a>
            </td>
            <!-- Tên dịch vụ -->
            <td class="name">
                {{ $item->service->name }}
            </td>
            <!-- Deadline -->
            <td>
                {{ date('d/m/Y H:i', strtotime($item->deadline)) }}
            </td>
            <!-- Trạng thái -->
            <td class="text-center">
                {{ getOrderStatus($item->status) }}
            </td>
            <!-- Note -->
            <td style="text-align: center !important;">
                <div class="ftb-badge" style="cursor: pointer;" onclick="showChat({{$item->id}},'{{ $item->service->name }}', '{{ $item->assigned_sale_id }}')">
                    <div class="ftb-avatar">
                        <img src="{{ asset('themes/cms/fotober/images/icon-chat.png') }}" style="width: 32px">
                    </div>
                    <?php $total = 0 ?>
                    @forelse ($item->total_no_seen as $no_seen)
                        @if ($no_seen->customer_id > 0)
                            <?php $total++ ?>
                        @endif
                        @empty
                    @endforelse
                    @if ($total > 0)
                        <div id="total_no_seen_cus_{{$item->id}}">
                            <sup class="ftb-badge-count">
                                <span class="ftb-scroll-number-only">
                                    <span class="total_seen ftb-scroll-number-only-unit current" >{{$total}}</span>
                                </span>
                            </sup>
                        </div>
                    @else
                        <div id="total_no_seen_cus_{{$item->id}}">
                        </div>
                    @endif
                </div>
            </td>
            <!-- Gán sale member -->
            @if(\Illuminate\Support\Facades\Auth::user()->is_admin)
                <td>
                    <select class="form-control" onchange="updateAssignSale({{ $item->id }}, $(this).val())" style="width: 150px">
                        <option value="-1">{{ trans('fotober.common._select_sale_') }}</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" {{ ($sale->id == $item->assigned_sale_id) ? 'selected' : '' }}>{{ $sale->email }}</option>
                        @endforeach
                    </select>
                </td>
            @else
            <!-- Xử lý -->
            <td>
                <div class="btn-group">
                    @if(($item->assigned_sale_id > 0 && $item->status >= \App\Helpers\Constants::ORDER_STATUS_NEW) || \Illuminate\Support\Facades\Auth::user()->is_admin)
                        {{-- Re-do --}}
                        @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_PENDING || $item->status == \App\Helpers\Constants::ORDER_STATUS_NEW)
                                <a class="mr-2" href="javascript:void(0)" onclick="changeStatus({{ $item->id }}, {{\App\Helpers\Constants::ORDER_STATUS_EDITING}},'{{ trans('fotober.order.job_recept') }}')" title="Click to Job recept">
                                    {{ trans('fotober.order.job_recept') }}
                                </a>
                        @endif
                        {{--Show add Output --}}
                        {{-- @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                            <a href="javascript:void(0)" onclick="showOutput({{ $item->id }}, {{ $item->customer->id }})" title="Click to show output list">
                                <i class="ace-icon fa fa-paper-plane bigger-120"></i>
                            </a>
                        @endif --}}
                        {{-- <a class="mr-2" href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to show info">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        </a> --}}
                        @if ($item->status > \App\Helpers\Constants::ORDER_STATUS_PENDING)
                            <a class="mr-2" href="{{ route('sale_order_summary', ['id' => $item->id ]) }}" title="Click to show summary">
                                {{ trans('fotober.order.view') }}
                            </a>
                        @endif
                        {{-- @if ($item->status < 3)
                        <a class="mr-2" href="{{ route('sale_order_edit', ['id' => $item->id ]) }}" title="Click to add output">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        @endif --}}
                        @if(in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_DRAFT]))
                        <a class="mr-2" href="{{ route('sale_order_delete', ['id' => $item->id ]) }}" onclick="return confirm('{{ trans('fotober.common.confirm_delete') }}');" title="Click to Delete">
                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                        </a>
                        @endif
                    @else
                    {{-- <a class="mr-2" href="javascript:void(0)" onclick="takeOrder({{ $item->id }})" title="Click to take order">
                        <i class="ace-icon fa fa-paper-plane bigger-120"></i>
                    </a> --}}
                    @endif
                </div>
            </td>
            @endif
            <!-- Tạo invoice chuyển qua paypal -->
            <td>
                @if (isset($item->payment) && in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT, \App\Helpers\Constants::ORDER_STATUS_COMPLETED, \App\Helpers\Constants::ORDER_STATUS_PAID]))
                    <a href="{{$item->payment->link_payment}}" target="_blank" title="Click to view paypal invoice">
                        View Invoice
                    </a>
                @endif
            </td>
        </tr>
        @empty
            <tr>
                <td colspan="13">
                    <div class="block-no-order">
                        <div class="content">
                            <h2>NO ORDER</h2>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('sale_order_create') }}" class="btn btn-info mr-1">
                                    <i class="ti-plus" style="font-size: 12px;"></i> {{ trans('fotober.order.create_title') }}
                                </a>
                                to track them here
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
