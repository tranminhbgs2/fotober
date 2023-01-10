@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\App;
    if (! session()->has('locale')) { session()->put('locale', 'vi'); }
@endphp
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8"/>
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" sizes="24x24" type="image/png" />
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/datepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/daterangepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/jquery-ui.min.css') }}"/>
    <link href="{{ asset('libs/filepond/filepond.css') }}" rel="stylesheet" />
    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/fonts/fonts.googleapis.com.css') }}"/>
    <!-- ace styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/customize.css?version=' . \App\Helpers\Constants::ASSET_VERSION) }}"/>
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/admin/css/style.css?version=' . \App\Helpers\Constants::ASSET_VERSION) }}') }}" class="main-stylesheet" id="main-style"/>
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/admin/css/app.css?version=' . \App\Helpers\Constants::ASSET_VERSION) }}') }}" class="app-stylesheet" id="app-style"/>
    <!-- fotober style -->
    <link rel="stylesheet" href="{{ asset('themes/cms/ace/assets/css/pagination.css') }}"/>
    <link rel="stylesheet" href="{{ asset('libs/jquery-toast/jquery.toast.css') }}"/>
    {{-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> --}}
    <!-- inline styles related to this page -->
    <style>
    </style>
    <!-- ace settings handler -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('libs/fckeditor/ckfinder/ckfinder.js') }}"></script>
    @yield('asset-header')
    <!-- include FilePond library -->
    <script src="{{ asset('libs/filepond/filepond.min.js') }}"></script>
    {{-- Zoom image --}}
    <script src="{{ asset('themes/cms/ace/assets/admin/js/js-image-zoom.js') }}"></script>

    <!-- include FilePond jQuery adapter -->
    <script src="{{ asset('libs/filepond/filepond.jquery.js') }}"></script>
    <script type="text/javascript">
        window.user = {
            user_id: '{{ \Illuminate\Support\Facades\Auth::id() }}',
            account_type: '{{ (\Illuminate\Support\Facades\Auth::user()) ? \Illuminate\Support\Facades\Auth::user()->account_type : '' }}',
            is_admin: '{{ (\Illuminate\Support\Facades\Auth::user()) ? \Illuminate\Support\Facades\Auth::user()->is_admin : '' }}',
        };
        window.ajax_url = {
            order_list: '{{ route('customer_order_listing_ajax') }}',
            summary_order: '{{ route('dashboard_summary_order_ajax') }}',
            service_list: '{{ route('service_listing_ajax') }}',
            draft_order_list: '{{ route('dashboard_draft_order_ajax') }}',
            recent_order_list: '{{ route('dashboard_recent_order_ajax') }}',
            sale_customer_list: '{{ route('sale_customer_listing_ajax') }}',
        }
    </script>
    {{-- <script src="{{ asset('js/notification.js') }}"></script> --}}
    <script src="{{ mix('js/notification.js') }}"></script>
</head>
<body class="skin-blue fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">CMS Fotober</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Light Logo icon -->
                            <img src="{{ asset('images/favicon.png') }}" alt="homepage" class="light-logo d-lg-none" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- Light Logo text -->
                         <img src="{{ asset('images/logo_white.png') }}" style="max-width: 150px;" class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        @guest
                            @php redirect('/') @endphp
                        @else
                            @if (in_array(Auth::user()->account_type, [\App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER, \App\Helpers\Constants::ACCOUNT_TYPE_SALE]))
                                <li class="nav-item d-none d-sm-block mr-3">
                                    <a class="nav-link waves-effect waves-dark nav-create" href="{{ ((Auth::user()->account_type == \App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER)) ? route('customer_order_create') : route('sale_order_create') }}"
                                    title="{{ trans('fotober.common.create_order') }}">
                                        <i class="ti-plus" style="font-size: 12px;"></i>
                                        <span>{{ trans('fotober.common.create_order') }}</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Nếu là quyền KH thì bỏ cái chuyển ngôn ngữ đi, update 07/10/2021 --}}
                            @if (Auth::user()->account_type !== \App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER)
                                <!--<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ trans('fotober.language') . ': ' . trans('fotober.locale') }}">
                                        <span class="">
                                            <img src="{{ asset('images/languages/' . session()->get('locale') . '.png') }}" />
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                        <a class="dropdown-item" href="{{ route('change_language', ['locale' => 'vi']) }}">
                                            <img class="mr-2" src="{{ asset('images/languages/vi.png') }}" />
                                            Tiếng Việt
                                        </a>
                                        <a class="dropdown-item" href="{{ route('change_language', ['locale' => 'en']) }}">
                                            <img class="mr-2" src="{{ asset('images/languages/en.png') }}" />
                                            English
                                        </a>
                                    </div>
                                </li>-->
                            @endif
                            <!-- ============================================================== -->
                            <!-- Comment -->
                            <!-- ============================================================== -->
                            <li class="nav-item dropdown">
                                @include('themes.cms.ace.layouts.common.notification', [])
                            </li>
                            <!-- ============================================================== -->
                            <!-- End Comment -->

                            <!-- ============================================================== -->
                            <!-- ============================================================== -->
                            <!-- User Profile -->
                            <!-- ============================================================== -->
                            <li class="nav-item dropdown u-pro" style="margin-right: 24px;">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic d-flex align-items-center h-100" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('images/favicon.png') }}" alt="user" class="">
                                    <div class="mx-2 hidden-md-down">
                                        <span class="hidden-md-down d-block" style="line-height: 20px;">{{ trans('fotober.welcome') }}</span>
                                        <span class="hidden-md-down d-block" style="line-height: 20px;">{{ Auth::user()->username }}</span>
                                    </div>
                                    <i class="ti-angle-down hidden-md-down" style="font-size: 12px;"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                    <!-- text-->
                                    <a href="{{ route('account_change_password', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}" class="dropdown-item">
                                        <i class="ti-reload mr-2"></i>
                                        {{ trans('fotober.profile.change_password') }}
                                    </a>
                                    <!-- text-->
                                    <a href="{{ route('account_show_profile', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}" class="dropdown-item">
                                        <i class="ti-info-alt mr-2"></i>
                                        {{ trans('fotober.profile.user_info') }}
                                    </a>
                                    <!-- text-->
                                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="ti-power-off mr-2"></i>
                                        {{ trans('fotober.profile.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <?php if(in_array(Auth::user()->account_type, ['CUSTOMER', 'SALE', 'ADMIN', 'EDITOR', 'QAQC', 'SUPER_ADMIN'])): ?>
                            @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.partials.nav-bar-' . strtolower(Auth::user()->account_type))
                        <?php else: ?>
                            @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.partials.nav-bar-default')
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapperr" id="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            {{-- © 2021 CMS FOTOBER by Design Team --}}
        </footer>

    {{-- <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a> --}}
    </div>
    <!-- /.main-container -->
    <!-- daterange picker scripts -->
    <script src="{{ asset('themes/cms/ace/assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/fuelux.spinner.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/jquery.autosize.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/jquery.inputlimiter.1.3.1.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/js/jquery.maskedinput.min.js') }}"></script>
    <!-- /daterange picker scripts -->

    <!-- ace scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.13/ace.min.js"></script>
    {{-- <script src="{{ asset('themes/cms/ace/assets/admin/js/ace.js') }}"></script> --}}
    <script src="{{ asset('libs/jquery-toast/jquery.toast.js') }}"></script>


    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- Bootstrap popper Core JavaScript -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('themes/cms/ace/assets/admin/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('themes/cms/ace/assets/admin/js/custom.min.js') }}"></script>
    {{-- ckeditor --}}
    <script type="text/javascript" src="{{ asset('libs/fckeditor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('libs/filepond/filepond.js') }}"></script>

    @yield('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function(){
            //$('.lazy').lazy();
            @if (! in_array(\Illuminate\Support\Facades\Route::currentRouteName(), ['customer_order_detail', 'sale_order_detail']))
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                startDate: '30/03/2020',
                endDate: '<?php echo date('d/m/Y', time()-86400)?>'
            });
            $('input[name=date-range-picker]').daterangepicker({
                'applyClass' : 'btn-sm btn-success',
                'cancelClass' : 'btn-sm btn-default',
                locale: {
                    applyLabel: 'Chọn',
                    cancelLabel: 'Hủy'
                },
                format: 'DD/MM/YYYY',
                maxDate: '<?php echo date('d/m/Y')?>'
            });
            @endif

            // Call notification: total unread & list
            updateNotification();
        });

        function updateNotification() {
            $.ajax({
                url: "{{ route('listing_by_user_ajax') }}",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_counting: true
                },
                success: function(result) {
                    //$('#data-loading').hide();
                    if (result.code == 200) {
                        if(result.data.total == 0){
                            $("#notification_count").css("display", "none");
                        } else {
                            $('#notification_count').html(result.data.total);
                        }
                        //$('#notifications_counter').html(result.data.total);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    //$('#data-loading').hide();
                }
            });

            $.ajax({
                url: "{{ route('listing_by_user_ajax') }}",
                type: 'POST',
                dataType: 'HTML',
                data: data = {
                    _token: '{{ csrf_token() }}',
                },
                success: function(result) {
                    //$('#data-loading').hide();
                    $('#notification_list').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    //$('#data-loading').hide();
                }
            });
        }
    </script>

</body>
</html>
