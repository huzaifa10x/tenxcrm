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
        <?php echo e(__('Roles')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card basic-data-table">
        <?php if($userPermissions->contains('can manage roles')): ?>
            <div class="card-header">
                <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-outline-primary">
                    Add New Role
                </a>
            </div>
        <?php endif; ?>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <?php if($userPermissions->contains('can manage roles')): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td> <?php echo e($role->id); ?></td>
                            <td><?php echo e($role->name); ?></td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 overflow-auto" style="max-width: 300px">
                                    <?php $__currentLoopData = $role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="badge fw-light text-dark bg-warning border p-1">
                                            <?php echo e($permission->name); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <?php if($userPermissions->contains('can manage roles')): ?>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <button class="px-3 py-1 btn btn-sm btn-outline-warning"
                                            onclick="openPermissionsModal(<?php echo e($role->id); ?>, <?php echo json_encode($role->permissions->pluck('id')); ?>)">
                                            Permissions
                                        </button>

                                        <a href="<?php echo e(route('roles.edit', $role->id)); ?>"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="<?php echo e(route('roles.destroy', $role->id)); ?>" method="POST"
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
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($roles->links('vendor.pagination.custom')); ?>

        </div>
    </div>

    <!-- Permissions Modal -->
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Manage Role Permissions</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="permissionsForm" class="container">
                        <input type="hidden" id="role_id">
                        <div class="row">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 form-check d-flex align-items-center g-2">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        name="permissions[]" value="<?php echo e($permission->id); ?>"
                                        id="permission-<?php echo e($permission->id); ?>">
                                    <label class="form-check-label fs-7" for="permission-<?php echo e($permission->id); ?>">
                                        <?php echo e($permission->name); ?>

                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPermissionsModal(roleId, selectedPermissions) {
            $("#role_id").val(roleId);

            $(".permission-checkbox").each(function() {
                let permissionId = $(this).val();
                $(this).prop("checked", selectedPermissions.includes(parseInt(permissionId)));
            });

            $("#permissionsModal").modal("show");
        }

        // Handle form submission
        $("#permissionsForm").submit(function(e) {
            e.preventDefault();

            let roleId = $("#role_id").val();
            let selectedPermissions = $(".permission-checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "<?php echo e(route('roles.update.permissions')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    role_id: roleId,
                    permissions: selectedPermissions
                },
                success: function(response) {
                    toastr.success("Permission Updated!", "Success");
                    $("#permissionsModal").modal("hide");
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error updating permissions!");
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/roles/index.blade.php ENDPATH**/ ?>