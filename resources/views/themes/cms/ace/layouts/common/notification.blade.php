{{-- <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="false">
    <i class="ace-icon fa fa-bell"></i>
    <span class="badge badge-important" id="notification_count">0</span>
</a> --}}
<a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="ti-bell"></i>
    <span id="notification_count"></span>
    {{-- <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div> --}}
</a>
<div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
    <ul>
        <li>
            <div class="drop-title">Notifications</div>
        </li>
        <li>
            <ul id="notification_list" class="message-center ps ps--theme_default ps--active-y"></ul>
        </li>
        <li>
            <a class="nav-link text-center link" href="{{ route('notifications') }}"> 
                <strong>{{ trans('fotober.common.see_all') }}</strong> 
                <i class="fa fa-angle-right"></i> 
            </a>
        </li>
    </ul>
    
        {{-- <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
            <li class="dropdown-content ace-scroll" style="position: relative;">
                <div class="scroll-track" style="display: none;">
                    <div class="scroll-bar"></div>
                </div>
                <div class="scroll-content" style="max-height: 350px; overflow-y: scroll">
                    <ul class="dropdown-menu dropdown-navbar navbar-pink" id="notification_list"></ul>
                </div>
            </li>
            <li class="dropdown-footer">
                <a href="{{ route('notifications') }}">{{ trans('fotober.common.see_all') }}<i class="ace-icon fa fa-arrow-right"></i></a>
            </li>
        </ul>
    </ul> --}}
</div>
