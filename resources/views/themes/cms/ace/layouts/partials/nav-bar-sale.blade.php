<li class="{{ setActiveMenu(['sale_order', 'sale_order_create', 'sale_order_edit', 'sale_order_detail']) }}">
    <a href="{{ route('sale_order') }}">
        <i class="menu-icon fa fa-shopping-cart"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.my_order') }} </span>
    </a>
    <b class="arrow"></b>
</li>
{{--@if(\Illuminate\Support\Facades\Auth::user()->is_admin == 1)--}}
<li class="{{ setActiveMenu(['sale_customer_listing', 'sale_customer_create', 'sale_customer_edit']) }}">
    <a href="{{ route('sale_customer_listing') }}">
        <i class="menu-icon fa fa-users"></i>
        <span class="hide-menu"> {{ trans('fotober.order.customer') }} </span>
    </a>
    <b class="arrow"></b>
</li>
{{--@endif--}}
{{-- <li class="{{ setActiveMenu('sale_payment') }}">
    <a href="{{ route('sale_payment') }}">
        <i class="menu-icon fa fa-money"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.payment') }} </span>
    </a>
    <b class="arrow"></b>
</li> --}}
<li class="{{ setActiveMenu('sale_payment') }}"> 
    <a class="waves-effect waves-dark" href="{{ route('sale_payment') }}" aria-expanded="false">
        <i class="ti-credit-card"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.payment') }}</span>
    </a>
</li>
{{-- <li>
    <a href="#">
        <i class="menu-icon fa fa-user"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.referal') }} </span>
    </a>
    <b class="arrow"></b>
</li> --}}
<li class="{{ setActiveMenu(['sale_listing_report']) }}">
    <a href="{{ route('sale_listing_report') }}">
        <i class="menu-icon fa fa-signal"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.report') }} </span>
    </a>
    <b class="arrow"></b>
</li>

<li class="{{ setActiveMenu('sale_kpi') }}">
    <a href="{{ route('sale_kpi') }}">
        <i class="menu-icon fa fa-rocket"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.kpi') }} </span>
    </a>
    <b class="arrow"></b>
</li>
<li>
    <a class="has-arrow waves-effect waves-dark" href="#">
        <i class="menu-icon fa fa-gear"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.setting') }} </span>
    </a>
    <ul aria-expanded="false" class="collapse">
        <li><a href="{{ route('account_show_profile', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">User Info</a></li>
        <li><a href="{{ route('account_change_password', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">Change Password</a></li>
    </ul>
</li>
