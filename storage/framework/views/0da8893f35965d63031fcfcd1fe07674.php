<section class="mb-5">
    <header class="mb-4">
        <h2 class="h5 text-dark">
            <?php echo e(__('Delete Account')); ?>

        </h2>
        <p class="text-muted small">
            <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')); ?>

        </p>
    </header>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <?php echo e(__('Delete Account')); ?>

    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('profile.destroy')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel"><?php echo e(__('Are you sure you want to delete your account?')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo e(__('Close')); ?>"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')); ?>

                        </p>

                        <div class="mb-3">
                            <label for="delete_password" class="form-label"><?php echo e(__('Password')); ?></label>
                            <input type="password" class="form-control <?php if($errors->userDeletion->has('password')): ?> is-invalid <?php endif; ?>"
                                   id="delete_password" name="password" placeholder="<?php echo e(__('Password')); ?>" required>

                            <?php if($errors->userDeletion->has('password')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->userDeletion->first('password')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="btn btn-danger ms-2"><?php echo e(__('Delete Account')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>