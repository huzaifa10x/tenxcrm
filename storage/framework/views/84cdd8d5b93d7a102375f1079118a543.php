<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <?php echo e(__('Users')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card basic-data-table">
        <?php if($userPermissions->contains('can manage users')): ?>
            <div class="card-header">
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-outline-primary">
                    Add New User
                </a>
                <a href="<?php echo e(route('users.gridView')); ?>" class="btn btn-outline-info ms-3">
                    Grid
                </a>
            </div>
        <?php endif; ?>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td> <?php echo e($user->id); ?></td>
                            <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        <?php if($user->img): ?>
                                            <img src="<?php echo e(asset('/public/storage/')); ?>/<?php echo e($user->img); ?>"
                                                class="rounded-pill me-1 w-40-px h-40-px img-fluid" alt="Logo"
                                                width="50">
                                        <?php endif; ?>
                                        <?php echo e($user->name); ?>

                                    </label>
                                </div>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->roles->pluck('name')->implode(', ')); ?></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <?php if($userPermissions->contains('can manage users')): ?>
                                        <button class="px-3 py-1 btn btn-sm btn-outline-warning"
                                            onclick="openRolesModal(<?php echo e($user->id); ?>, <?php echo json_encode($user->roles->pluck('id')); ?>)">
                                            Roles
                                        </button>
                                    <?php endif; ?>

                                    <a href="<?php echo e(route('users.show', $user->id)); ?>"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    <?php if($userPermissions->contains('can manage users')): ?>
                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST"
                                            style="display: inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($users->links('vendor.pagination.custom')); ?>

        </div>
    </div>
    
    <div class="modal fade" id="manageRolesModal" tabindex="-1" aria-labelledby="manageRolesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="manageRolesLabel">Manage Roles</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRolesForm">
                        <input type="hidden" id="userId">
                        <div class="row">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 mb-3">
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                            value="<?php echo e($role->id); ?>" id="role_<?php echo e($role->id); ?>">
                                        <label class="form-check-label fs-7" for="role_<?php echo e($role->id); ?>">
                                            <?php echo e($role->name); ?>

                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Roles</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openRolesModal(userId, selectedRoles) {
            $('#userId').val(userId);

            // Uncheck all checkboxes first
            $('.role-checkbox').prop('checked', false);

            // Check roles assigned to the user
            selectedRoles.forEach(roleId => {
                $('#role_' + roleId).prop('checked', true);
            });

            // Show the modal
            $('#manageRolesModal').modal('show');
        }

        $('#updateRolesForm').submit(function(e) {
            e.preventDefault();

            let userId = $('#userId').val();
            let selectedRoles = $('.role-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "<?php echo e(route('users.updateRoles', ['id' => '__USER_ID__'])); ?>".replace('__USER_ID__',
                    userId),
                method: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    roles: selectedRoles
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success("Role Updated Successfully!", "Success");
                        $('#manageRolesModal').modal('hide');
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            });
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/users/index.blade.php ENDPATH**/ ?>