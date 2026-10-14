<div class="row gy-3 mb-10">
    <div class="col-xxl-4 col-lg-6">
        <div class="card h-100 border shadow-none radius-8 overflow-hidden">
            <div class="card-body p-24">
                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <h6 class="mb-2 fw-bold text-lg">Tasks</h6>
                </div>
                <div id="compAndPendingTasks"></div>
                <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                    <li class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                        <span class="text-secondary-light text-sm fw-normal">Pending:
                            <span class="text-primary-light fw-semibold"><?php echo e($pendingTasksCount); ?></span>
                        </span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                        <span class="text-secondary-light text-sm fw-normal">Completed:
                            <span class="text-primary-light fw-semibold"><?php echo e($completedTasksCount); ?></span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-lg-6">
        <div class="card h-100 border shadow-none radius-8 overflow-hidden">
            <div class="card-body p-24">
                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <h6 class="mb-2 fw-bold text-lg">Words Count</h6>
                </div>
                <div id="compAndPendingWords"></div>
                <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                    <li class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                        <span class="text-secondary-light text-sm fw-normal">Pending:
                            <span class="text-primary-light fw-semibold"><?php echo e($pendingWords); ?></span>
                        </span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                        <span class="text-secondary-light text-sm fw-normal">Delivered:
                            <span class="text-primary-light fw-semibold"><?php echo e($completedWords); ?></span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row gy-3 mb-10">
    <div class="col-lg-12">
        <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
            <div
                class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                <span class="fw-bold">Last 15 Days Tasks</span>
            </div>
            <div class="card-body p-0">
                <div id="tasksChart"></div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex align-items-center flex-wrap justify-content-between">
    <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center active" id="pending-tab" data-bs-toggle="pill"
                data-bs-target="#pending-task" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-to-do-list" aria-selected="true">
                Pending Tasks
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert"><?php echo e($pendingTasks->count()); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="completed-tab" data-bs-toggle="pill"
                data-bs-target="#completed-task" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                Completed Tasks
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert"><?php echo e($completedTasks->count()); ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="assigned-tab" data-bs-toggle="pill"
                data-bs-target="#assigned-tasks" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                Tasks I've Assigned
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert"><?php echo e($assignedTasks->count()); ?></span>
            </button>
        </li>
    </ul>
    <a href="<?php echo e(route('tasks.index')); ?>"
        class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
        Tasks
        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
    </a>
</div>
<div class="tab-content p-0 card mb-10" id="task-tabContent">
    <!-- PENDING TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade show active p-0 card-body table-responsive"
        id="pending-task" role="tabpanel">
        <?php if($pendingTasks->count()): ?>
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Content Type</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($task->id); ?></td>
                            <td><a target="_blank"
                                    href="<?php echo e(route('projects.show', $task->project->id)); ?>"><?php echo e($task->project->title); ?></a>
                            </td>
                            <td><a href="<?php echo e(route('tasks.show', $task->id)); ?>"><?php echo e($task->title); ?></a>
                            </td>
                            <td><?php if (isset($component)) { $__componentOriginald30cf9cba6bb540c6bffcc9785239679 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald30cf9cba6bb540c6bffcc9785239679 = $attributes; } ?>
<?php $component = App\View\Components\Badge::resolve(['text' => $task->status,'type' => 'status'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Badge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $attributes = $__attributesOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $component = $__componentOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__componentOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?></td>
                            <td><?php echo e($task->contentTask->sub_type ?? 'N/A'); ?></td>
                            <td><?php echo e($task->start_date); ?></td>
                            <td><?php echo e($task->due_date); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending tasks found.</p>
        <?php endif; ?>
    </div>
    <!-- COMPLETED TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-0 card-body table-responsive"
        id="completed-task" role="tabpanel">
        <?php if($completedTasks->count()): ?>
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Word Count</th>
                        <th>Number Of Pages</th>
                        <th>Content Type</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($task->id); ?></td>
                            <td><a target="_blank"
                                    href="<?php echo e(route('projects.show', $task->project->id)); ?>"><?php echo e($task->project->title); ?></a>
                            </td>
                            <td><a href="<?php echo e(route('tasks.show', $task->id)); ?>"><?php echo e($task->title); ?></a>
                            </td>
                            <td><?php if (isset($component)) { $__componentOriginald30cf9cba6bb540c6bffcc9785239679 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald30cf9cba6bb540c6bffcc9785239679 = $attributes; } ?>
<?php $component = App\View\Components\Badge::resolve(['text' => $task->status,'type' => 'status'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Badge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $attributes = $__attributesOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $component = $__componentOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__componentOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?></td>
                            <td><?php echo e($task->completions->where('field_name', 'number_of_words')->sortByDesc('id')->first()->field_value ?? 'N/A'); ?>

                            </td>
                            <td><?php echo e($task->contentTask->number_of_pages ?? 'N/A'); ?></td>
                            <td><?php echo e($task->contentTask->sub_type ?? 'N/A'); ?></td>
                            <td><?php echo e($task->start_date); ?></td>
                            <td><?php echo e($task->due_date); ?></td>
                            <td><?php echo e($task->completions->sortByDesc('id')->first()->created_at ?? 'N/A'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No completed tasks found.</p>
        <?php endif; ?>
    </div>
    <!-- ASSIGNED TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-0 card-body table-responsive"
        id="assigned-tasks" role="tabpanel">
        <?php if($assignedTasks->count()): ?>
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($task->id); ?></td>
                            <td><a target="_blank"
                                    href="<?php echo e(route('projects.show', $task->project->id)); ?>"><?php echo e($task->project->title); ?></a>
                            </td>
                            <td><a href="<?php echo e(route('tasks.show', $task->id)); ?>"><?php echo e($task->title); ?></a>
                            </td>
                            <td><?php if (isset($component)) { $__componentOriginald30cf9cba6bb540c6bffcc9785239679 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald30cf9cba6bb540c6bffcc9785239679 = $attributes; } ?>
<?php $component = App\View\Components\Badge::resolve(['text' => $task->status,'type' => 'status'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Badge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $attributes = $__attributesOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__attributesOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald30cf9cba6bb540c6bffcc9785239679)): ?>
<?php $component = $__componentOriginald30cf9cba6bb540c6bffcc9785239679; ?>
<?php unset($__componentOriginald30cf9cba6bb540c6bffcc9785239679); ?>
<?php endif; ?></td>
                            <td><?php echo e($task->start_date); ?></td>
                            <td><?php echo e($task->due_date); ?></td>
                            <td><?php echo e($task->completions->sortByDesc('id')->first()->created_at ?? 'N/A'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No assigned tasks found.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    var options = {
        series: [<?php echo e($pendingWords ?? 0); ?>, <?php echo e($completedWords ?? 0); ?>],
        colors: ['#F4941E', '#22C55E'],
        labels: ['Pending', 'Completed'],
        legend: {
            show: false
        },
        chart: {
            type: 'donut',
            height: 270,
            sparkline: {
                enabled: true
            },
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 150,
                    height: 150,
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
    };
    var chart = new ApexCharts(document.querySelector("#compAndPendingWords"), options);
    chart.render();
    var options1 = {
        series: [<?php echo e($pendingTasksCount ?? 0); ?>, <?php echo e($completedTasksCount ?? 0); ?>],
        colors: ['#F4941E', '#22C55E'],
        labels: ['Pending', 'Completed'],
        legend: {
            show: false
        },
        chart: {
            type: 'donut',
            height: 270,
            sparkline: {
                enabled: true
            },
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 150,
                    height: 150,
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
    };
    var chart1 = new ApexCharts(document.querySelector("#compAndPendingTasks"), options1);
    chart1.render();
</script><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/content-dashboard.blade.php ENDPATH**/ ?>