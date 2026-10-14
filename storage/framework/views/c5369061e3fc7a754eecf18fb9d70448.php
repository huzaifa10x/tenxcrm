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
        <?php echo e(__('Account Managers')); ?>

     <?php $__env->endSlot(); ?>
    <?php if(auth()->user()->hasRole('ADMIN')): ?>
        <div class="card basic-data-table container-fluid">
            <h2>Account Managers & Team Control</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Manager</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($manager->name); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm add-team-btn" data-manager-id="<?php echo e($manager->id); ?>"
                                    data-toggle="modal" data-target="#amModal">
                                    Add Team Member
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="amModal" tabindex="-1" role="dialog" aria-labelledby="amModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Account Managers to Team</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="am-list" class="d-flex flex-wrap gap-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let selectedManagerId = null;

            $(document).on('click', '.add-team-btn', function() {
                selectedManagerId = $(this).data('manager-id');
                $('#am-list').empty();

                $.get('<?php echo e(route('account-manager.users')); ?>', function(users) {
                    users.forEach(user => {
                        const btn = $(
                            `<button class="btn btn-outline-success m-1">${user.name}</button>`);
                        btn.click(() => {
                            $.post('<?php echo e(route('account-manager.member')); ?>', {
                                am_id: user.id,
                                manager_id: selectedManagerId,
                                _token: '<?php echo e(csrf_token()); ?>'
                            }, res => {
                                if (res.status === 'success') {
                                    btn.removeClass('btn-outline-success').addClass(
                                        'btn-success').text(user.name + ' ✅');
                                }
                            });
                        });
                        $('#am-list').append(btn);
                    });
                });
            });
        </script>
    <?php endif; ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/account-manager/users.blade.php ENDPATH**/ ?>