<li class="{{ setActiveMenu(['superadmin_listing_customer']) }}">
    <a href="{{ route('superadmin_listing_customer') }}">
        <i class="menu-icon fa fa-users"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.customer') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu(['superadmin_listing_order', 'superadmin_order_edit', 'superadmin_order_detail']) }}">
    <a href="{{ route('superadmin_listing_order') }}">
        <i class="menu-icon fa fa-desktop"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.order') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="">
    <a href="#">
        <i class="menu-icon fa fa-signal"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.report_sadmin') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu(['superadmin_listing_service']) }}">
    <a href="{{ route('superadmin_listing_service') }}">
        <i class="menu-icon fa fa-photo"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.service') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu(['superadmin_listing_group', 'superadmin_create_group', 'superadmin_edit_group']) }}">
    <a href="{{ route('superadmin_listing_group') }}">
        <i class="menu-icon fa fa-th-list"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.group') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu(['superadmin_listing_user', 'superadmin_edit_user', 'superadmin_create_user']) }}">
    <a href="{{ route('superadmin_listing_user') }}">
        <i class="menu-icon fa fa-users"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.user') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<!--<li class="">
    <a href="#">
        <i class="menu-icon fa fa-home"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.role') }}</span>
    </a>
    <b class="arrow"></b>
</li>-->
<!--<li class="">
    <a href="#">
        <i class="menu-icon fa fa-home"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.permission') }}</span>
    </a>
    <b class="arrow"></b>
</li>-->
<!--<li class="">
    <a href="#">
        <i class="menu-icon fa fa-home"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.config') }}</span>
    </a>
    <b class="arrow"></b>
</li>-->
<li class="{{ setActiveMenu(['superadmin_listing_resoure']) }}">
    <a href="{{ route('superadmin_listing_resoure') }}">
        <i class="menu-icon fa fa-file"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.resoure') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<li class="{{ setActiveMenu(['superadmin_listing_in_out_log']) }}">
    <a href="{{ route('superadmin_listing_in_out_log') }}">
        <i class="menu-icon fa fa-calendar"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.inout_log') }}</span>
    </a>
    <b class="arrow"></b>
</li>
<!--<li class="">
    <a href="#">
        <i class="menu-icon fa fa-home"></i>
        <span class="hide-menu">{{ trans('fotober.sidebar.action_log') }}</span>
    </a>
    <b class="arrow"></b>
</li>-->
