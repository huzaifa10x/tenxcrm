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
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><i class="bi bi-person"></i> Manager</th>
                                <th scope="col"><i class="bi bi-people"></i> Team Members</th>
                                <th scope="col" class="text-center"><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold text-primary"><?php echo e($manager->name); ?></td>
                                    <td>
                                        <?php if($manager->teams->isEmpty()): ?>
                                            <span class="text-muted fst-italic">No team members</span>
                                        <?php else: ?>
                                            <ul class="list-unstyled mb-0 ps-3">
                                                <?php $__currentLoopData = $manager->teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><i
                                                            class="bi bi-person-circle me-1 text-success"></i><?php echo e($member->name); ?>

                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-primary btn-sm add-team-btn"
                                            data-manager-id="<?php echo e($manager->id); ?>" data-bs-toggle="modal"
                                            data-bs-target="#amModal">
                                            <i class="bi bi-plus-circle me-1"></i>Add Member
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="am-list" class="d-flex flex-wrap gap-3"></div>
        <script>
            let selectedManagerId = null;

            $(document).on('click', '.add-team-btn', function() {
                selectedManagerId = $(this).data('manager-id');
                $('#am-list').empty();

                $.get('<?php echo e(route('account-maanger.users')); ?>', {
                    manager_id: selectedManagerId
                }, function(data) {
                    const {
                        all_users,
                        team_users
                    } = data;

                    all_users.forEach(user => {
                        const alreadyAdded = team_users.some(teamUser => teamUser.id === user.id);

                        const btn = $(`<button class="btn m-1">${user.name}</button>`);

                        if (alreadyAdded) {
                            btn
                                .addClass('btn-success')
                                .text(user.name + ' ✅')
                                .click(() => {
                                    if (confirm(`Remove ${user.name} from team?`)) {
                                        $.ajax({
                                            url: '<?php echo e(route('account-maanger.remove-member')); ?>',
                                            method: 'DELETE',
                                            data: {
                                                am_id: user.id,
                                                manager_id: selectedManagerId,
                                                _token: '<?php echo e(csrf_token()); ?>'
                                            },
                                            success: function(res) {
                                                if (res.status === 'success') {
                                                    btn.removeClass('btn-success')
                                                        .addClass('btn-outline-success')
                                                        .text(user.name)
                                                        .off(
                                                            'click'
                                                        ) // remove old click handler
                                                        .click(addMemberHandler(user,
                                                            btn));
                                                }
                                            }
                                        });
                                    }
                                });
                        } else {
                            btn
                                .addClass('btn-outline-success')
                                .click(addMemberHandler(user, btn));
                        }

                        $('#am-list').append(btn);
                    });
                });
            });

            function addMemberHandler(user, btn) {
                return function() {
                    $.post('<?php echo e(route('account-maanger.member')); ?>', {
                        am_id: user.id,
                        manager_id: selectedManagerId,
                        _token: '<?php echo e(csrf_token()); ?>'
                    }, res => {
                        if (res.status === 'success') {
                            btn.removeClass('btn-outline-success')
                                .addClass('btn-success')
                                .text(user.name + ' ✅')
                                .off('click') // remove old click handler
                                .click(() => {
                                    if (confirm(`Remove ${user.name} from team?`)) {
                                        $.ajax({
                                            url: '<?php echo e(route('account-maanger.remove-member')); ?>',
                                            method: 'DELETE',
                                            data: {
                                                am_id: user.id,
                                                manager_id: selectedManagerId,
                                                _token: '<?php echo e(csrf_token()); ?>'
                                            },
                                            success: function(res) {
                                                if (res.status === 'success') {
                                                    btn.removeClass('btn-success')
                                                        .addClass('btn-outline-success')
                                                        .text(user.name)
                                                        .off('click')
                                                        .click(addMemberHandler(user, btn));
                                                }
                                            }
                                        });
                                    }
                                });
                        }
                    });
                }
            }
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/account-manager/index.blade.php ENDPATH**/ ?>