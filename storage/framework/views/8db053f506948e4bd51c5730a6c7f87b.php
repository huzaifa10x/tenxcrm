<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div class="sidebar-logo-wrap">
        <a href="<?php echo e(route('dashboard')); ?>" class="sidebar-logo">
            <img src="<?php echo e(asset('/public/light-logo.png')); ?>" class="p-1" alt="site logo" class="">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <a href="<?php echo e(route('profile.edit')); ?>" class="mb-10 d-flex w-100 align-items-center justify-content-center">
            <img style="height: 140px; width: 140px"
                src="<?php echo e(auth()->user()->img ? asset('/public/storage/' . auth()->user()->img) : asset('/images/avatar/avatar1.png')); ?>"
                class="rounded rounded-circle img-fluid object-fit-cover" alt="">
        </a>
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="<?php echo e(route('dashboard')); ?>">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if(auth()->user()->hasRole('ADMIN')): ?>
                <li>
                    <a href="<?php echo e(route('xero.analytics.dashboard')); ?>">
                        <i class="w-auto text-xl ri-news-line me-14 d-flex"></i>
                        <span>Xero Analytics</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view users')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="w-auto text-xl ri-user-settings-line me-14 d-flex"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('users.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Users List</a>
                        </li>
                        <?php if($userPermissions->contains('can manage users')): ?>
                            <li>
                                <a href="<?php echo e(route('users.create')); ?>"><i
                                        class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add User</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view permissions')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Permissions</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('permissions.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Permissions List</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view companies')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Companies</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('companies.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Companies List</a>
                        </li>
                        <?php if($userPermissions->contains('can manage companies')): ?>
                            <li>
                                <a href="<?php echo e(route('companies.create')); ?>"><i
                                        class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Company</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view roles')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                        <span>Roles</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('roles.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All Roles</a>
                        </li>
                        <?php if($userPermissions->contains('can manage roles')): ?>
                            <li>
                                <a href="<?php echo e(route('roles.create')); ?>"><i
                                        class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Role</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view industries')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                        <span>Industries</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('industries.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All Industries</a>
                        </li>
                        <?php if($userPermissions->contains('can manage industries')): ?>
                            <li>
                                <a href="<?php echo e(route('industries.create')); ?>"><i
                                        class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Industry</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if($userPermissions->contains('can view payment methods')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                        <span>Payment Methods</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('payment-methods.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All payment
                                methods</a>
                        </li>
                        <?php if($userPermissions->contains('can manage payment methods')): ?>
                            <li>
                                <a href="<?php echo e(route('payment-methods.create')); ?>"><i
                                        class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Method</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Projects</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="<?php echo e(route('projects.index')); ?>"><i
                                class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All projects</a>
                    </li>
                    <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SEO ADMIN')): ?>
                        <li>
                            <a href="<?php echo e(route('projects.index', ['projects-category' => 'SEO'])); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All SEO Projects</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('projects.index', ['projects-category' => 'Hosting'])); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> All Hosting
                                Projects</a>
                        </li>
                    <?php endif; ?>
                    <?php if($userPermissions->contains('can add projects')): ?>
                        <li>
                            <a href="<?php echo e(route('projects.create')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Project</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <i class="w-auto text-xl ri-news-line me-14 d-flex"></i>
                    <span>Tasks</span>
                </a>
                <ul class="sidebar-submenu">
                    <?php if($userPermissions->contains('can view all tasks')): ?>
                        <li>
                            <a href="<?php echo e(route('tasks.index')); ?>?type=all-tasks">
                                <span>All Tasks</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if($userPermissions->contains('can create tasks')): ?>
                        <li>
                            <a href="<?php echo e(route('tasks.index')); ?>?type=task-created-by-me">
                                <span>Created by Me</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('tasks.index')); ?>?type=assigned-to-me">
                            <span>Tasks for Me</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('tasks.index')); ?>?type=where-im-collaborator">
                            <span>Tasks I'm a Collaborator In</span>
                        </a>
                    </li>
                </ul>

            </li>

            <?php if(auth()->user()->hasRole('ADMIN')): ?>
                <li>
                    <a href="<?php echo e(route('website-projects.create')); ?>">
                        <iconify-icon icon="flowbite:users-group-solid" class="menu-icon"></iconify-icon>
                        <span>Website Projects</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('account-maanger.index')); ?>">
                        <iconify-icon icon="flowbite:users-group-solid" class="menu-icon"></iconify-icon>

                        <span>Account Managers</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php
                $__navManage = \App\Support\Access::manageAttendance(auth()->user());
                $__navApprove = \App\Support\Access::approveLeaves(auth()->user());
                $__navPending = $__navApprove ? \App\Models\LeaveRequest::where('status', 'pending')->count() : 0;
            ?>
            <li>
                <a href="<?php echo e(route('attendance.index')); ?>" class="<?php echo e(request()->routeIs('attendance.index') ? 'active-page' : ''); ?>">
                    <iconify-icon icon="solar:calendar-mark-linear" class="menu-icon"></iconify-icon>
                    <span><?php echo e($__navManage ? 'Attendance' : 'My Attendance'); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e($__navApprove ? route('leaves.admin') : route('leaves.index')); ?>" class="<?php echo e(request()->routeIs('leaves.*') ? 'active-page' : ''); ?>">
                    <iconify-icon icon="solar:suitcase-tag-linear" class="menu-icon"></iconify-icon>
                    <span><?php echo e($__navApprove ? 'Leave Requests' : 'My Leave'); ?></span>
                    <?php if($__navPending): ?>
                        <span class="badge rounded-pill ms-auto" style="background:#a9e324;color:#0f1412;font-weight:700"><?php echo e($__navPending); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php if($__navApprove): ?>
                <li>
                    <a href="<?php echo e(route('leaves.index')); ?>">
                        <iconify-icon icon="solar:calendar-add-linear" class="menu-icon"></iconify-icon>
                        <span>My Leave</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('ADMIN')): ?>
                <li>
                    <a href="<?php echo e(route('settings.index')); ?>">
                        <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                        <span>System Setting</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SEO ADMIN')): ?>
                <li>
                    <a href="<?php echo e(route('admin.offsite-seo.index')); ?>">
                        <i class="w-auto text-xl ri-links-line me-14 d-flex"></i>
                        <span>Offsite SEO Performance</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SEO ADMIN') || auth()->user()->hasRole('OFFSITE')): ?>
                <li>
                    <a href="<?php echo e(route('admin.offsite-seo.links.index')); ?>">
                        <i class="w-auto text-xl ri-link me-14 d-flex"></i>
                        <span>Offsite Links</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('Accountant')): ?>
                <li>
                    <a href="<?php echo e(route('invoices.index')); ?>">
                        <i class="w-auto text-xl ri-news-line me-14 d-flex"></i>
                        <span>Invoices</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('AM') || auth()->user()->hasRole('SALES MANAGER')): ?>
                <li>
                    <a href="<?php echo e(route('services.index')); ?>">
                        <i class="w-auto text-xl ri-news-line me-14 d-flex"></i>
                        <span>Services</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('crm.leads.index')); ?>">
                        <i class="w-auto text-xl ri-news-line me-14 d-flex"></i>
                        <span>Leads</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SALES MANAGER')): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:chart-2-outline" class="menu-icon"></iconify-icon>
                        <span>Pipedrive</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?php echo e(route('pipedrive.deals.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Deals</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('pipedrive.deals.create')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-info-main"></i> Add Deal</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('pipedrive.persons.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Contacts</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('pipedrive.organizations.index')); ?>"><i
                                    class="w-auto ri-circle-fill circle-icon text-primary-600"></i> Organizations</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo e(route('messages.index')); ?>">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Messages</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('compress.form')); ?>">
                    <iconify-icon icon="mdi:folder-zip-outline" class="menu-icon"></iconify-icon>
                    <span>Image Compressor</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('resizer.form')); ?>">
                    <iconify-icon icon="mdi:folder-zip-outline" class="menu-icon"></iconify-icon>
                    <span>Image Resizer</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('converter.form')); ?>">
                    <iconify-icon icon="mdi:folder-zip-outline" class="menu-icon"></iconify-icon>
                    <span>Image Converter</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/layouts/navigation.blade.php ENDPATH**/ ?>