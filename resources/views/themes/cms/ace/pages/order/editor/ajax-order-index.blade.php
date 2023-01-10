<div class="table-responsive">
    <table id="simple-table" class="table table-hover table-centered table-order table-head-bg">
        <thead>
        <tr>
            <th class="no">{{ trans('fotober.common.no') }}</th>
            <th class="name">{{ trans('fotober.order.form_name') }}</th>
            <th>{{ trans('fotober.order.require') }}</th>
            <th>{{ trans('fotober.common.col_service') }}</th>
            <th>{{ trans('fotober.common.col_deadline') }}</th>
            <th>{{ trans('fotober.common.col_update') }}</th>
            <th>{{ trans('fotober.common.col_status') }}</th>
            <th>{{ trans('fotober.common.col_action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $key => $item)
        <tr style="{{ ($url_id == $item->id) ? 'background-color: orange' : '' }}">
            <td class="no">{{ $offset + $key + 1 }}</td>
            <td class="name font-weight-500">
                <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                    {{ $item->name }}
                </a>
            </td>
            <td>
                <a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="showRequirement({{ $item->id }}, {{ $item->customer->id }})" title="Click to show requirement list">
                    <i class="ti-align-center"></i>
                    {{-- <span class="badge badge-light">{{count($item->requirementDone)}}</span> --}}
                </a>
            </td>
            <td>
                {{ $item->service->name }}
            </td>
            <td>
                {{ date('d/m/Y H:i', strtotime($item->deadline)) }}
            </td>
            <td>
                {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}
            </td>
            <td>
                {{ getOrderStatus($item->status) }}
            </td>
            <td>
                @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_EDITING && isset($item->requirementDone) && count($item->requirementDone) == 0)
                    <div class="btn-group">
                        <a href="javascript:void(0)" class="mr-2" onclick="changeStatus({{ $item->id }})" title="Click to update by Edited">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
                {{--Show add Output --}}
                @if (in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_EDITED,\App\Helpers\Constants::ORDER_STATUS_CHECKED]))
                <a href="javascript:void(0)" class="mr-2" onclick="showOutput({{ $item->id }}, {{ $item->customer->id }})" title="Click to show output list">
                    <i class="fa fa-paper-plane"></i>
                </a>
                @endif
                <div class="btn-group">
                    <a href="javascript:void(0)" onclick="showInfo({{ $item->id }})" title="Click to Show Info">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">{{ trans('fotober.common.no_data') }}</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-top: 15px">
    {!! $paginate_link !!}
</div>
