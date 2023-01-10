@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.account.profile_title_page') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">User Info</h4>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-block">
                        @include(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '/common/alert-message')
                    </div>
                    @if (isset($user) && $user)
                        <!-- Table  -->
                        <div class="table-responsive">
                            <table class="table table-custome table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.profile_picture') }}</td>
                                        <td>
                                            <img src="{{ $user->avatar }}" alt="Fotober" style="max-width: 100px"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.id') }}</td>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.your_name') }}</td>
                                        <td>{{ $user->fullname }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.birthday') }}</td>
                                        <td>{{ date('d/m/Y', strtotime($user->birthday)) }}</td>
                                    </tr> --}}
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.phone') }}</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.email_paypal') }}</td>
                                        <td>{{ $user->email_paypal }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.address') }}</td>
                                        <td>{{ $user->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.account_type') }}</td>
                                        <td>{{ $user->account_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">Email</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="name font-weight-500">{{ trans('fotober.account.created_at') }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($user->created_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            {{-- <button type="button" class="btn btn-info ml-auto mr-1"> Update </button> --}}
                            <a href="{{route('account_edit_profile', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}" class="btn btn-info ml-auto mr-1">
                                <i class="ti-pencil mr-1" style="vertical-align: middle;"></i>
                                Edit profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
