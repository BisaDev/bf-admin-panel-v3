<div class="topbar">
    <div class="topbar-left">
        <div class="text-center">
            <a href="index.html" class="logo">
                <i class="icon-c-logo"> <img src="{{ asset('images/logo_sm.png') }}" /> </i>
                <span><img src="{{ asset('images/logo.png') }}"/></span>
            </a>
        </div>
    </div>

    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>

                <ul class="nav navbar-nav navbar-right pull-right">
                    <li class="dropdown top-menu-item-xs">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="{{ $logged_user->user_detail->photo }}" alt="user-img" class="img-circle"></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                            <li class="divider"></li>
                            <li>
                                <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>