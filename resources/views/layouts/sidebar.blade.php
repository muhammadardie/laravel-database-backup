<div class="sidebar sidebar-style-2" data-background-color="dark2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img 
                        src="{{ !empty(Auth::user()->photo) ? 
                                asset('uploaded_files/user') .'/'. Auth::user()->photo :
                                asset('assets/img/avatar.png')  
                            }}"
                        alt="..." class="avatar-img rounded-circle" 
                    />
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Auth::user()->name }}
                            <span class="user-level">{{ Auth::user()->role }}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="#profile" class="edit-profile-account">
                                    <span class="link-collapse">Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="#settings" class="edit-password-account">
                                    <span class="link-collapse">Change Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="#settings" class="delete-account" data-href="{{ url('') }}">
                                    <span class="link-collapse">Delete Account</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <span class="link-collapse">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ in_array('home', $url) ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item">
                    <a data-toggle="collapse" href="#user-management">
                        <i class="fas fa-user-cog"></i>
                        <p>User Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ in_array('user-management', $url) ? 'show' : '' }}" id="user-management">
                        <ul class="nav nav-collapse">
                            <li class="{{ in_array('user', $url) ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}">
                                    <span class="sub-item">User</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-toggle="collapse" href="#master-data">
                        <i class="fas fa-server"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ in_array('master-data', $url) ? 'show' : '' }}" id="master-data">
                        <ul class="nav nav-collapse">
                            <li class="{{ in_array('storage', $url) ? 'active' : '' }}">
                                <a href="{{ route('storage.index') }}">
                                    <span class="sub-item">Storage</span>
                                </a>
                            </li>
                            <li class="{{ in_array('database-source', $url) ? 'active' : '' }}">
                                <a href="{{ route('database-source.index') }}">
                                    <span class="sub-item">Database Source</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-toggle="collapse" href="#database">
                        <i class="fas fa-database"></i>
                        <p>Database Backup</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ in_array('database', $url) ? 'show' : '' }}" id="database">
                        <ul class="nav nav-collapse">
                            <li class="{{ in_array('scheduler', $url) ? 'active' : '' }}">
                                <a href="{{ route('scheduler.index') }}">
                                    <span class="sub-item">Scheduler</span>
                                </a>
                            </li>
                            <li class="{{ in_array('histories', $url) ? 'active' : '' }}">
                                <a href="{{ route('histories.index') }}">
                                    <span class="sub-item">Histories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
