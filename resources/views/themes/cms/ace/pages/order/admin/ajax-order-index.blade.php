<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th class="name">{{ trans('fotober.common.col_create') }}</th>
            <th class="name w-200">{{ trans('fotober.order.form_name') }}</th>
            <th class="name">{{ trans('fotober.order.form_customer') }}</th>
            <th class="name">{{ trans('fotober.common.col_service') }}</th>
            <th>{{ trans('fotober.order.require') }}</th>
            <th>{{ trans('fotober.common.col_deadline') }}</th>
            <th>{{ trans('fotober.common.col_assign') }}</th>
            <th>{{ trans('fotober.common.col_update') }}</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr style="{{ ($url_id == $item->id) ? 'background-color: orange' : '' }}">
            <td class="no">{{ $offset + $key + 1 }}</td>
            <td>
                {{ date('d/m/Y H:i', strtotime($item->created_at)) }}
            </td>
            <td class="font-weight-500">
                <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                    {{ $item->name }}
                </a>
            </td>
            <td>
                {{ $item->customer->fullname }} ({{ $item->customer->email }})
            </td>
            <td >
                {{ $item->service->name }}
            </td>
            <td>
                <a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="showRequirement({{ $item->id }}, {{ $item->customer->id }})" title="Click to show requirement list">
                    <i class="ti-align-center"></i>
                    {{-- <span class="badge badge-light">{{count($item->requirementDone)}}</span> --}}
                </a>
            </td>
            <td>
                {{ date('d/m/Y H:i', strtotime($item->deadline)) }}
            </td>
            <td>
                @if (in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_COMPLETED,\App\Helpers\Constants::ORDER_STATUS_AWAITING_PAYMENT,\App\Helpers\Constants::ORDER_STATUS_PAID]))
                    @foreach($editors as $editor)
                        {{ ($editor->id == $item->assigned_editor_id) ? $editor->email : '' }}
                    @endforeach
                @else
                    <select class="form-control" onchange="updateAssignSale({{ $item->id }}, $(this).val())">
                        <option value="-1">{{ trans('fotober.common._select_editor_') }}</option>
                        @foreach($editors as $editor)
                            <option value="{{ $editor->id }}" {{ ($editor->id == $item->assigned_editor_id) ? 'selected' : '' }}>{{ $editor->email }}</option>
                        @endforeach
                    </select>
                @endif
            </td>
            <td>
                {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}
            </td>
            <td>
                {{ getOrderStatus($item->status) }}
            </td>
            <td>
                <div class="btn-group">
                    <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    </a>
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
