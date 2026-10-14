<?php
    $statusBadge = ['live' => 'success', 'pending' => 'warning', 'lost' => 'danger'];
?>
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
        <?php echo e(__('Offsite Links')); ?>

     <?php $__env->endSlot(); ?>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <?php if($isManager): ?>
                <a href="<?php echo e(route('admin.offsite-seo.index')); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Performance dashboard</a>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-success" id="addLinkBtn"><i class="bi bi-plus-circle me-1"></i>Add Link</button>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <?php $__env->startComponent('admin.offsite-seo._filters', ['action' => route('admin.offsite-seo.links.index'), 'range' => $range, 'ranges' => $ranges, 'from' => $from, 'to' => $to]); ?>
        <?php if($isManager): ?>
            <div class="col-md-2 col-6">
                <label class="form-label fw-semibold">Employee</label>
                <select name="user_id" class="form-select">
                    <option value="">All</option>
                    <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold">Project</label>
            <select name="project_id" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>><?php echo e($project->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold">Status</label>
            <select name="link_status" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e(request('link_status') === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <option value="none" <?php echo e(request('link_status') === 'none' ? 'selected' : ''); ?>>Not set</option>
            </select>
        </div>
    <?php echo $__env->renderComponent(); ?>

    <div class="shadow-sm card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Link-Building Records</h6>
            <span class="badge bg-primary"><?php echo e($links->total()); ?> links</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Project / Task</th>
                        <th>Type</th>
                        <th>Keyword</th>
                        <th>Link URL</th>
                        <th>Target URL</th>
                        <th>Follow</th>
                        <th>DR</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-nowrap"><?php echo e($link->created_at?->format('d M Y')); ?></td>
                            <td><?php echo e($names[$link->owner_id] ?? '—'); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($link->project->title ?? '—'); ?></div>
                                <?php if($link->task): ?>
                                    <a href="<?php echo e(route('tasks.show', $link->task_id)); ?>" class="small text-decoration-none">
                                        #<?php echo e($link->task_id); ?> <?php echo e(Str::limit($link->task->title, 35)); ?></a>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-light text-dark"><?php echo e($link->task->offsiteTask->sub_type ?? ($link->task->type ?? '—')); ?></span></td>
                            <td><?php echo e($link->keyword); ?></td>
                            <td><a href="<?php echo e($link->url); ?>" target="_blank" rel="noopener"><?php echo e(Str::limit($link->url, 40)); ?></a></td>
                            <td>
                                <?php if(Str::startsWith($link->destination_url, 'http')): ?>
                                    <a href="<?php echo e($link->destination_url); ?>" target="_blank" rel="noopener"><?php echo e(Str::limit($link->destination_url, 35)); ?></a>
                                <?php else: ?>
                                    <?php echo e($link->destination_url ?: '—'); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e($link->rel ? ucfirst($link->rel) : '—'); ?></td>
                            <td><?php echo e($link->dr ?? '—'); ?></td>
                            <td>
                                <select class="form-select form-select-sm js-status" style="min-width:105px;"
                                    data-url="<?php echo e(route('admin.offsite-seo.links.status', $link->id)); ?>">
                                    <?php if(!$link->link_status): ?>
                                        <option value="" selected disabled>Not set</option>
                                    <?php endif; ?>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($link->link_status === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-warning js-edit" title="Edit"
                                    data-url="<?php echo e(route('admin.offsite-seo.links.update', $link->id)); ?>"
                                    data-user="<?php echo e($link->owner_id); ?>"
                                    data-task="<?php echo e($link->task_id); ?>"
                                    data-task-label="#<?php echo e($link->task_id); ?> <?php echo e($link->task->title ?? ''); ?> — <?php echo e($link->project->title ?? ''); ?>"
                                    data-keyword="<?php echo e($link->keyword); ?>"
                                    data-link="<?php echo e($link->url); ?>"
                                    data-destination="<?php echo e($link->destination_url); ?>"
                                    data-rel="<?php echo e($link->rel); ?>"
                                    data-dr="<?php echo e($link->dr); ?>"
                                    data-status="<?php echo e($link->link_status ?? 'live'); ?>"
                                    data-date="<?php echo e($link->created_at?->toDateString()); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="<?php echo e(route('admin.offsite-seo.links.destroy', $link->id)); ?>" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this link record?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="py-5 text-center text-muted">
                                <i class="mb-2 bi bi-inbox fs-1 d-block"></i>No links found for this period.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($links->hasPages()): ?>
            <div class="card-footer"><?php echo e($links->links('pagination::bootstrap-5')); ?></div>
        <?php endif; ?>
    </div>

    
    <div class="modal fade" id="linkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="linkForm" action="<?php echo e(route('admin.offsite-seo.links.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" id="linkMethod" value="POST">
                    <div class="modal-header">
                        <h6 class="modal-title" id="linkModalTitle">Add Link</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <?php if($isManager): ?>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" id="linkUser" class="form-select" required>
                                        <option value="">Select employee</option>
                                        <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="<?php echo e($isManager ? 'col-md-6' : 'col-12'); ?>">
                                <label class="form-label fw-semibold">Task <span class="text-danger">*</span></label>
                                <select name="task_id" id="linkTask" class="form-select" required>
                                    <option value="">Select task</option>
                                </select>
                                <small class="text-muted">Project and link type are taken from the task.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Keyword / anchor text <span class="text-danger">*</span></label>
                                <input type="text" name="keyword" id="linkKeyword" class="form-control" maxlength="255" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Link created on</label>
                                <input type="date" name="link_date" id="linkDate" class="form-control" max="<?php echo e(now()->toDateString()); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Link URL (page where the backlink is placed) <span class="text-danger">*</span></label>
                                <input type="url" name="url" id="linkUrl" class="form-control" maxlength="255" placeholder="https://" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Target URL (client page the link points to)</label>
                                <input type="text" name="destination_url" id="linkDestination" class="form-control" placeholder="https://">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Follow type</label>
                                <select name="rel" id="linkRel" class="form-select">
                                    <option value="">Not specified</option>
                                    <?php $__currentLoopData = $rels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Domain Rating (0-100)</label>
                                <input type="number" name="dr" id="linkDr" class="form-control" min="0" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="link_status" id="linkStatus" class="form-select" required>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Save Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isManager = <?php echo json_encode($isManager, 15, 512) ?>;
            const storeUrl = <?php echo json_encode(route('admin.offsite-seo.links.store'), 15, 512) ?>;
            const tasksUrl = <?php echo json_encode(route('admin.offsite-seo.links.tasks'), 15, 512) ?>;
            const today = <?php echo json_encode(now()->toDateString(), 15, 512) ?>;
            const modal = new bootstrap.Modal(document.getElementById('linkModal'));
            const $task = $('#linkTask');

            function loadTasks(userId, selected, selectedLabel) {
                $task.html('<option value="">Loading…</option>');
                if (isManager && !userId) {
                    $task.html('<option value="">Select an employee first</option>');
                    return;
                }
                $.get(tasksUrl, { user_id: userId }, function(tasks) {
                    $task.html('<option value="">Select task</option>');
                    tasks.forEach(function(task) {
                        $task.append($('<option>').val(task.id).text(task.label));
                    });
                    // keep the current task selectable even if it is older than the list covers
                    if (selected && !$task.find('option[value="' + selected + '"]').length) {
                        $task.append($('<option>').val(selected).text(selectedLabel));
                    }
                    $task.val(selected || '');
                }).fail(function() {
                    $task.html('<option value="">Could not load tasks</option>');
                });
            }

            $('#linkUser').on('change', function() {
                loadTasks($(this).val(), null, null);
            });

            $('#addLinkBtn').on('click', function() {
                $('#linkForm').attr('action', storeUrl)[0].reset();
                $('#linkMethod').val('POST');
                $('#linkModalTitle').text('Add Link');
                $('#linkDate').val(today);
                loadTasks(isManager ? $('#linkUser').val() : null, null, null);
                modal.show();
            });

            $(document).on('click', '.js-edit', function() {
                const d = $(this).data();
                $('#linkForm').attr('action', d.url);
                $('#linkMethod').val('PUT');
                $('#linkModalTitle').text('Edit Link');
                $('#linkUser').val(d.user);
                $('#linkKeyword').val(d.keyword);
                $('#linkUrl').val(d.link);
                $('#linkDestination').val(d.destination === '-' ? '' : d.destination);
                $('#linkRel').val(d.rel || '');
                $('#linkDr').val(d.dr);
                $('#linkStatus').val(d.status);
                $('#linkDate').val(d.date);
                loadTasks(d.user, d.task, d.taskLabel);
                modal.show();
            });

            $(document).on('change', '.js-status', function() {
                const select = this;
                $.ajax({
                    url: $(select).data('url'),
                    type: 'PATCH',
                    data: { _token: '<?php echo e(csrf_token()); ?>', link_status: $(select).val() },
                    success: function(response) {
                        $(select).find('option[value=""]').remove();
                        toastr.success(response.message);
                    },
                    error: function() {
                        toastr.error('Could not update the link status.');
                    }
                });
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal64241253160b81d8755926aef84b90bb)): ?>
<?php $attributes = $__attributesOriginal64241253160b81d8755926aef84b90bb; ?>
<?php unset($__attributesOriginal64241253160b81d8755926aef84b90bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal64241253160b81d8755926aef84b90bb)): ?>
<?php $component = $__componentOriginal64241253160b81d8755926aef84b90bb; ?>
<?php unset($__componentOriginal64241253160b81d8755926aef84b90bb); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/admin/offsite-seo/links.blade.php ENDPATH**/ ?>