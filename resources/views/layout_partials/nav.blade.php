<nav class="navbar navbar-default navbar-fixed-top top-div">
        <div class="container container-fluid  ">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if (Auth::guest())
                    <a class="navbar-brand" id="mobileHeader"><span class="fonta">ISU SI Attendance</span></a>
                    <a class="navbar-brand" id="desktopHeader"><span class="fonta">Iowa State SI Attendance Management</span></a>
                @else
                    <a class="navbar-brand" id="mobileHeader" href="/Sessions"><span class="fonta">ISU SI Attendance</span></a>
                    <a class="navbar-brand" id="desktopHeader" href="/Sessions"><span class="fonta">Iowa State SI Attendance Management</span></a>
                @endif
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li><a href="/Sessions" id="lia">Home</a></li>
                            <li> <a href="/About" id="lia">About</a> </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/Shibboleth.sso/Logout"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('shibLogout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li> <a href="/Account">Edit Account</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
