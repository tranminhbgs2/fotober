<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-bordered table-centered table-order">
            <thead>
            <tr>
                {{-- <th class="text-center">{{ trans('fotober.common.no') }}</th> --}}
                <th class="text-center">{{ trans('fotober.common.name_input') }}</th>
                <th class="text-center">{{ trans('fotober.common.type_input') }}</th>
                <th class="text-center">URL</th>
                <th class="text-center">{{ trans('fotober.common.col_create') }}</th>
            </tr>
            </thead>
            <tbody>
                @forelse($inputs as $key => $item)
                <tr>
                    {{-- <td class="text-center">{{ ($key+ 1) }}</td> --}}
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->type }}</td>
                    <td>
                        @if ($item->type == 'LINK')
                        <a href="{{$item->link}}" target="_blank" title="Click show output">View</a>
                        @else
                        <a href="{{asset('storage/'.$item->file)}}" target="_blank" title="Click show output">View</a>
                        @endif
                    </td>
                    <td class="text-center">{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">{{ trans('fotober.common.no_data') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>