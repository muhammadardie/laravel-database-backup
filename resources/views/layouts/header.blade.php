<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark2">
        <a href="{{ url('') }}" class="logo">
             <img src="assets/img/database-icon.png" />
            <h2>Database Backup</h2>
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="dark">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img 
                              src="{{ !empty(Auth::user()->photo) ? 
                                asset('uploaded_files/user') .'/'. Auth::user()->photo :
                                asset('assets/img/avatar.png')  
                            }}" 
                              alt="..." class="avatar-img rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ !empty(Auth::user()->photo) ? 
                                                asset('uploaded_files/user') .'/'. Auth::user()->photo :
                                                asset('assets/img/avatar.png')  
                                            }}"
                                          alt="image profile" class="avatar-img rounded" 
                                        />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->name }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item edit-profile-account" href="#">Profile</a>
                                <a class="dropdown-item edit-password-account" href="#">Change Password</a>
                                <a class="dropdown-item delete-account" href="#" data-href="{{ url('') }}">Delete Account</a>
                                <div class="dropdown-divider"></div>
                                <a id="logout" href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="invisible">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
@include('layouts.modal_edit_password')
@include('layouts.modal_edit_profile', ['roleOpt' => $roleOpt]))
@include('layouts.script_delete_account')