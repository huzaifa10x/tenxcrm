<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="index.html" class="sidebar-logo">
            <img src="{{ asset('/images/logo-dark.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        {{-- <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                    <span>Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('users.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Users
                            List</a>
                    </li>
                    <li>
                        <a href="{{ route('users.gridView') }}"><i
                                class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                            Users
                            Grid</a>
                    </li>
                    <li>
                        <a href="{{ route('users.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            User</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                    <span>Permissions</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('permissions.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            All Permissions</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Companies</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('companies.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Companies
                            List</a>
                    </li>
                    <li>
                        <a href="{{ route('companies.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            Company</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                    <span>Customers</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('customers.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            Customers
                            List</a>
                    </li>
                    <li>
                        <a href="{{ route('customers.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            Customer</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                    <span>Roles</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('roles.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            All Roles</a>
                    </li>
                    <li>
                        <a href="{{ route('roles.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            Role</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                    <span>Industries</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('industries.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            All Industries</a>
                    </li>
                    <li>
                        <a href="{{ route('industries.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            Industry</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Seo Campaign</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('seo-campaign.index') }}"><i
                                class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            All Seo Campaign</a>
                    </li>
                    <li>
                        <a href="{{ route('seo-campaign.create') }}"><i
                                class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add
                            Seo Campaign</a>
                    </li>
                </ul>
            </li>
        </ul> --}}
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            @if ($userPermissions->contains('can view users'))
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('users.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
                        </li>
                        @if ($userPermissions->contains('can manage users'))
                            <li>
                                <a href="{{ route('users.create') }}"><i
                                        class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add User</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($userPermissions->contains('can view companies'))
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Companies</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('companies.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Companies List</a>
                        </li>
                        @if ($userPermissions->contains('can manage companies'))
                            <li>
                                <a href="{{ route('companies.create') }}"><i
                                        class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add Company</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($userPermissions->contains('can view roles'))
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                        <span>Roles</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('roles.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Roles</a>
                        </li>
                        @if ($userPermissions->contains('can manage roles'))
                            <li>
                                <a href="{{ route('roles.create') }}"><i
                                        class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add Role</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($userPermissions->contains('can view industries'))
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                        <span>Industries</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('industries.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Industries</a>
                        </li>
                        @if ($userPermissions->contains('can manage industries'))
                            <li>
                                <a href="{{ route('industries.create') }}"><i
                                        class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add Industry</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($userPermissions->contains('can view seo-campaign'))
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                        <span>Seo Campaign</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('seo-campaign.index') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Seo Campaign</a>
                        </li>
                        @if ($userPermissions->contains('can manage seo-campaign'))
                            <li>
                                <a href="{{ route('seo-campaign.create') }}"><i
                                        class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add Seo
                                    Campaign</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</aside>
