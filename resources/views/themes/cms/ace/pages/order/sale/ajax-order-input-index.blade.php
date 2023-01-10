<div class="modal-body" style="padding-bottom: 2rem;">
    <div class="table-responsive">
        <table id="simple-table" class="table table-custome table-bordered table-small mb-2">
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
            <?php $i = 0; ?>
            @forelse($inputs as $key => $item)
                <tr>
                    {{-- <td class="text-center">{{ ($key+ 1) }}</td> --}}
                    <td class="text-left"> @if ((($item->type == 'ALL') && ($item->name == null)) || ($item->type == 'LINK')) {{$item->link}} @else {{ $item->name }} @endif</td>
                    <td class="text-left">{{ $item->type }}</td>
                    <td class="text-left">
                        @if (($item->type == 'ALL') && ($item->name == null))
                            <a href="{{$item->link}}" target="_blank" title="Click show output">View</a>
                        @else
                            @if ($item->type == 'LINK')
                            <a href="{{$item->link}}" target="_blank" title="Click show output">View</a>
                            @else
                            <?php 
                                $i++;
                                $order_id = $item->order_id; 
                                $customer_id = $item->customer_id; 
                            ?>
                            <a href="{{asset('storage/'.$item->file)}}" target="_blank" title="Click show output">View</a>
                            @endif
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
        @if(count($inputs) > 0)
            @if ($i > 0)
            <a href="{{ route('download_zip', ['order_id' => $order_id, 'user_id' => $customer_id ]) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Dowload All</a>
            @endif
        @endif
    </div>
</div>
