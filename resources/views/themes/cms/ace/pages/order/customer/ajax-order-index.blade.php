{{-- ajax --}}

@forelse($data as $key => $item)
<div class="card box-shadow border-r10">
    <div class="card-body" id="web-order">

        <div class="row d-flex align-items-center no-block">
            <div class="col">
                <div class="col-md-12">
                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.order.order_submited') }}</h6>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_status') }}</h6>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_action') }}</h6>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.total') }}</h6>
                </div>
            </div>
        </div>
        <div class="row d-flex no-block">
            <div class="col">
                <div class="col-md-12">
                    <p class="font-weight-bold p-12 mb-5 pt-5px">{{$item->code}}</p>
                    <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.order_type') }}</h6>
                    <p class="font-weight-bold p-12">{{ $item->service->name }}</p>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <p class="font-weight-bold p-12 mb-48 pt-5px">{{ (($item->sent_sale_at) ? date('d/m/Y H:i', strtotime($item->sent_sale_at)) : '') }}</p>
                    <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.turnaround') }}</h6>
                    <p class="font-weight-bold p-12">{{ $item->turn_arround_time }} {{ trans('fotober.order.hours') }}</p>
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <div class="timeline">
                        <div class="tl-item active">
                            <div class="tl-dot b-primary"></div>
                            <div class="tl-content">
                                @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_DRAFT)
                                    <div class="color-blue">{{ trans('fotober.order.status_0') }}</div>
                                @else
                                    <div class="color-blue">{{ trans('fotober.order.status_1') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="tl-item {{ ($item->status > \App\Helpers\Constants::ORDER_STATUS_NEW) ? 'active' : ''}}">
                            <div class="tl-dot b-primary"></div>
                            <div class="tl-content">
                                <div class="">{{ trans('fotober.order.status_2') }}</div>
                            </div>
                        </div>
                        <div class="tl-item {{ ($item->status > \App\Helpers\Constants::ORDER_STATUS_PENDING) ? 'active' : ''}}">
                            <div class="tl-dot b-primary"></div>
                            <div class="tl-content">
                                <div class="">{{ trans('fotober.order.status_3') }}</div>
                            </div>
                        </div>
                        <div class="tl-item {{ ($item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED) ? 'active' : ''}}">
                            <div class="tl-dot b-primary"></div>
                            <div class="tl-content">
                                <div class="">{{ trans('fotober.order.status_8') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col p-0">
                <div class="col-md-12">
                    @if (in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_DRAFT]))
                    <a href="javascript:void(0)" onclick="showInfo({{$item->id}})" style="color: white;" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                        {{-- <i class="ti-trash"></i> --}}
                        {{ trans('fotober.order.review_and_place_order') }}
                    </a>
                    <a href="{{ route('customer_order_delete', ['id' => $item->id ]) }}" onclick="return confirm('{{ trans('fotober.common.confirm_delete') }}');" style="color: white; opacity: 50%" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                        {{-- <i class="ti-trash"></i> --}}
                        {{ trans('fotober.order.delete_draft') }}
                    </a>
                @endif

                @if ($item->status != \App\Helpers\Constants::ORDER_STATUS_DRAFT && $item->status < \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                    <a href="javascript:void(0)" onclick="showInfo({{$item->id}})" style="color: white; " class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                        {{ trans('fotober.order.btn_detail') }}
                    </a>
                    @if ($item->assigned_sale_id > 0)
                    <a href="javascript:void(0)" onclick="showChat({{$item->id}},'{{ $item->service->name }}')" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12 py-3" style="color: white;opacity: 50%">
                        
                        <div class="row d-flex justify-content-center align-items-center">
                            <span class="mr-1">{{ trans('fotober.order.add_note') }}</span>
                            <div class="ftb-badge cus" style="cursor: pointer;" onclick="showChat({{$item->id}},'{{ $item->service->name }}')">
                                <div class="ftb-avatar">
                                    <img src="{{ asset('themes/cms/fotober/images/chat_white.png') }}" style="width: 22px">
                                </div>
            
                                <?php $total = 0 ?>
                                @forelse ($item->total_no_seen as $no_seen)
                                    @if ($no_seen->sale_id > 0)
                                        <?php $total++ ?>
                                    @endif
                                @empty
                                @endforelse
                                @if ($total > 0)
                                    <div id="total_no_seen_sale_{{$item->id}}">
                                        <sup class="ftb-badge-count">
                                            <span class="ftb-scroll-number-only">
                                                <span class="total_seen ftb-scroll-number-only-unit current" >{{$total}}</span>
                                            </span>
                                        </sup>
                                    </div>
                                    <div id="total_no_seen_sale_{{$item->id}}">
                                        <sup class="ftb-badge-count">
                                            <span class="ftb-scroll-number-only" id="total_no_seen_sale_{{$item->id}}">
                                                <span class="total_seen ftb-scroll-number-only-unit current" >{{$total}}</span>
                                            </span>
                                        </sup>
                                    </div>
                                @else
                                    <div id="total_no_seen_sale_{{$item->id}}">
                                    </div>
                                @endif
            
                            </div>
                        </div>
                    </a>
                    @endif
                @endif

                    @if ($item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                        <a href="{{route('customer_order_preview', ['id' => $item->id ])}}" style="color: white;" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                            {{ trans('fotober.order.view_completed') }}
                        </a>
                        @if (isset($item->payment))
                        @if ($item->payment->link_payment)
                            @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                            <a href="javascript:void(0)" style="color: white;opacity: 50%" target="_blank" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                                {{ trans('fotober.order.status_11') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                            </a>
                            @else
                            <a href="{{ $item->payment->link_payment }}" style="color: white;opacity: 50%" target="_blank" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                                {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                            </a>
                            @endif
                        @else
                            <a href="javascript:void(0)" style="color: white;opacity: 50%" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                                {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                            </a>
                        @endif
                        @endif
                    @endif
                </div>
            </div>
            <div class="col">
                <div class="col-md-12">
                    <p>
                        @if (isset($item->payment) && $item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED)

                        <span class="color-blue font-weight-bold p-12 pt-5px"> {{!empty($item->total_payment) ? '$'.$item->total_payment :  trans('fotober.order.procesing')}}</span>
                        @else
                        <span class="color-blue font-weight-bold p-12 pt-5px">{{ trans('fotober.order.procesing') }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body" id="mobile-order">

        <div class="row d-flex no-block">
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.table.order_id') }}</h6>
                <p class="font-weight-bold p-12 mb-2">{{$item->code}}</p>
            </div>
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold" style="color: #9b9b9b">{{ trans('fotober.order.order_type') }}</h6>
                <p class="font-weight-bold mb-2 p-12">{{ $item->service->name }}</p>
            </div>
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.order.order_submited') }}</h6>
                <p class="font-weight-bold mb-2 p-12">{{ (($item->sent_sale_at) ? date('d/m/Y H:i', strtotime($item->sent_sale_at)) : '') }} </p>
            </div>
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.delivery') }}</h6>
                <p class="font-weight-bold p-12 mb-2">{{ $item->turn_arround_time }} {{ trans('fotober.order.hours') }}</p>
            </div>
            {{-- <div class="col-md-12">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">Input</h6>
                <a href="javascript:void(0)" class="font-weight-bold p-12 mb-2 pt-5px" onclick="showInput({{ $item->id }})" title="Click to Show Input"><p class="">{{ trans('fotober.order.view') }}</p></a>
            </div> --}}
            
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_status') }}</h6>
                
                <div class="timeline">
                    <div class="tl-item active">
                        <div class="tl-dot b-primary"></div>
                        <div class="tl-content">
                            @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_DRAFT)
                                <div class="color-blue">{{ trans('fotober.order.status_0') }}</div>
                            @else
                                <div class="color-blue">{{ trans('fotober.order.status_1') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="tl-item {{ ($item->status > \App\Helpers\Constants::ORDER_STATUS_NEW) ? 'active' : ''}}">
                        <div class="tl-dot b-primary"></div>
                        <div class="tl-content">
                            <div class="">{{ trans('fotober.order.status_2') }}</div>
                        </div>
                    </div>
                    <div class="tl-item {{ ($item->status > \App\Helpers\Constants::ORDER_STATUS_PENDING) ? 'active' : ''}}">
                        <div class="tl-dot b-primary"></div>
                        <div class="tl-content">
                            <div class="">{{ trans('fotober.order.status_3') }}</div>
                        </div>
                    </div>
                    <div class="tl-item {{ ($item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED) ? 'active' : ''}}">
                        <div class="tl-dot b-primary"></div>
                        <div class="tl-content">
                            <div class="">{{ trans('fotober.order.status_8') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.common.col_action') }}</h6>
                @if (in_array($item->status, [\App\Helpers\Constants::ORDER_STATUS_DRAFT]))
                    <a href="javascript:void(0)" onclick="showInfo({{$item->id}})" style="color: white; font-size: 12px" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                        {{-- <i class="ti-trash"></i> --}}
                        {{ trans('fotober.order.review_and_place_order') }}
                    </a>
                    <a href="{{ route('customer_order_delete', ['id' => $item->id ]) }}" onclick="return confirm('{{ trans('fotober.common.confirm_delete') }}');" style="color: white; font-size: 12px;opacity: 50%" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                        {{-- <i class="ti-trash"></i> --}}
                        {{ trans('fotober.order.delete_draft') }}
                    </a>
                @endif

                @if ($item->status != \App\Helpers\Constants::ORDER_STATUS_DRAFT && $item->status < \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                    <a href="javascript:void(0)" onclick="showInfo({{$item->id}})" style="color: white;" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                        {{ trans('fotober.order.btn_detail') }}
                    </a>
                    @if ($item->assigned_sale_id > 0)
                        <a href="javascript:void(0)" onclick="showChat({{$item->id}},'{{ $item->service->name }}')" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12 py-3" style="color: white;opacity: 50%">
                            {{-- <div class="row">
                                {{ trans('fotober.order.add_note') }} 
                                <img width="20" src="{{ asset('themes/cms/fotober/images/chat_white.png') }}" alt="" srcset="">
                                <div  id="total_seen">0</div>
                            </div> --}}
                            <div class="row d-flex justify-content-center align-items-center">
                                <span class="mr-1">{{ trans('fotober.order.add_note') }}</span>
                                <div class="ftb-badge cus" style="cursor: pointer;" onclick="showChat({{$item->id}},'{{ $item->service->name }}')">
                                    <div class="ftb-avatar">
                                        <img src="{{ asset('themes/cms/fotober/images/chat_white.png') }}" style="width: 22px">
                                    </div>
                                    <?php $total = 0 ?>
                                    @forelse ($item->total_no_seen as $no_seen)
                                        @if ($no_seen->sale_id > 0)
                                            <?php $total++ ?>
                                        @endif
                                    @empty
                                    @endforelse
                                    @if ($total > 0)
                                        <div id="total_no_seen_sale_{{$item->id}}">
                                            <sup class="ftb-badge-count">
                                                <span class="ftb-scroll-number-only">
                                                    <span class="total_seen ftb-scroll-number-only-unit current" >{{$total}}</span>
                                                </span>
                                            </sup>
                                        </div>
                                        <div id="total_no_seen_sale_{{$item->id}}">
                                            <sup class="ftb-badge-count">
                                                <span class="ftb-scroll-number-only" id="total_no_seen_sale_{{$item->id}}">
                                                    <span class="total_seen ftb-scroll-number-only-unit current" >{{$total}}</span>
                                                </span>
                                            </sup>
                                        </div>
                                    @else
                                        <div id="total_no_seen_sale_{{$item->id}}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                @endif

                @if ($item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                    <a href="{{route('customer_order_preview', ['id' => $item->id ])}}" style="color: white;" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                        {{ trans('fotober.order.view_completed') }}
                    </a>
                    @if (isset($item->payment))
                    @if ($item->payment->link_payment)
                        @if ($item->status == \App\Helpers\Constants::ORDER_STATUS_PAID)
                        <a href="javascript:void(0)" style="color: white;opacity: 50%" target="_blank" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                            {{ trans('fotober.order.status_11') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                        </a>
                        @else
                        <a href="{{ $item->payment->link_payment }}" style="color: white;opacity: 50%" target="_blank" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                            {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                        </a>
                        @endif
                    @else
                        <a href="javascript:void(0)" style="color: white;opacity: 50%" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                            {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                        </a>
                    @endif
                    @endif
                @endif
                {{-- @if (isset($item->payment))
                    @if ($item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED)
                        <a href="{{route('customer_order_preview', ['id' => $item->id ])}}" style="color: white;" class="btn btn-info d-lg-inline-block ml-auto mb-3 mr-1 col-md-12">
                            {{ trans('fotober.order.view_completed') }}
                        </a>
                        @if ($item->payment->link_payment)
                            <a href="{{ $item->payment->link_payment }}" style="color: white;opacity: 50%" target="_blank" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                                {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                            </a>
                        @else
                            <a href="javascript:void(0)" style="color: white;opacity: 50%" class="btn btn-info d-lg-inline-block ml-auto mr-1 col-md-12">
                                {{ trans('fotober.order.pay_now_paypal') }} <img width="20" src="{{ asset('themes/cms/fotober/images/favicon-paypal.ico') }}" alt="" srcset="">
                            </a>
                        @endif
                    @endif
                @endif --}}
            </div>
            <div class="col-md-12">
                <h6 class="text-uppercase font-weight-bold"  style="color: #9b9b9b">{{ trans('fotober.payment.total') }}</h6>
                <p>
                    @if (isset($item->payment) && $item->status >= \App\Helpers\Constants::ORDER_STATUS_COMPLETED)

                    <span class="color-blue font-weight-bold p-12 pt-5px"> {{!empty($item->total_payment) ? '$'.$item->total_payment :  trans('fotober.order.procesing')}}</span>
                    @else
                    <span class="color-blue font-weight-bold p-12 pt-5px">{{ trans('fotober.order.procesing') }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@empty
<div class="col-md-12">
    {{-- <p class="text-center">{{ trans('fotober.common.no_data') }}</p> --}}
    <div class="block-no-order">
        <div class="content">
            <h2>NO ORDER</h2>
            <div class="d-flex align-items-center">
                <a href="{{ route('customer_order_create') }}" class="btn btn-info mr-1">
                    <i class="ti-plus" style="font-size: 12px;"></i> {{ trans('fotober.order.create_title') }}
                </a>
                to track them here
            </div>
        </div>
    </div>
</div>
@endforelse
<div class="text-center" style="margin: 15px 0px">
    {!! $paginate_link !!}
</div>
