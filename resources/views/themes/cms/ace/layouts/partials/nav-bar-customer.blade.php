<li>
    <a class="waves-effect waves-dark" href="{{ route('customer_order') }}" aria-expanded="false">
        <i class="ti-bookmark-alt"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.my_order') }}</span>
    </a>
</li>
<li>
    <a class="waves-effect waves-dark" href="{{ route('customer_listing_service') }}" aria-expanded="false">
        <i class="fa fa-folder"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.customer_service') }}</span>
    </a>
</li>
<li>
    <a class="waves-effect waves-dark" href="{{ route('customer_payment') }}" aria-expanded="false">
        <i class="ti-credit-card"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.payment') }}</span>
    </a>
</li>
<li>
    <a class="has-arrow waves-effect waves-dark" href="{{ route('customer_setting') }}" aria-expanded="false">
        <i class="ti-settings"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.setting') }}</span>
    </a>
    <ul aria-expanded="false" class="collapse">
        <li><a href="{{ route('account_show_profile', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">User Info</a></li>
        <li><a href="{{ route('account_change_password', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">Change Password</a></li>
    </ul>
</li>
{{-- <li class="{{ setActiveMenu(['customer_order', 'customer_order_create', 'customer_order_edit', 'customer_order_detail']) }}">
    <a href="{{ route('customer_order') }}">
        <i class="menu-icon fa fa-shopping-cart"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.my_order') }} </span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu('customer_payment') }}">
    <a href="{{ route('customer_payment') }}">
        <i class="menu-icon fa fa-money"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.payment') }} </span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu('customer_referal') }}">
    <a href="{{ route('customer_referal') }}">
        <i class="menu-icon fa fa-user"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.referal') }} </span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu('customer_report') }}">
    <a href="{{ route('customer_report') }}">
        <i class="menu-icon fa fa-signal"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.report') }} </span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu('customer_setting') }}">
    <a href="{{ route('customer_setting') }}">
        <i class="menu-icon fa fa-gear"></i>
        <span class="hide-menu"> {{ trans('fotober.sidebar.setting') }} </span>
    </a>
    <b class="arrow"></b>
</li> --}}
