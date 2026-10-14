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
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <?php echo e(__('Content Writer Performance: ')); ?> <strong><?php echo e($writer->name); ?></strong>
            </div>
        </div>
     <?php $__env->endSlot(); ?>
    <a href="<?php echo e(route('admin.content-writers.index')); ?>" class="mb-4 btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Back to Writers
    </a>
    <!-- Quick Stats Cards -->
    <div class="mb-4 row g-3">
        <div class="col-md-3">
            <div class="text-white border-0 shadow-sm card bg-gradient bg-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 text-white-50">Total Tasks</h6>
                            <h2 class="mb-0 fw-bold"><?php echo e($stats['total_tasks']); ?></h2>
                        </div>
                        <i class="opacity-50 bi bi-list-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="text-white border-0 shadow-sm card bg-gradient bg-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 text-white-50">Completed</h6>
                            <h2 class="mb-0 fw-bold"><?php echo e($stats['completed_tasks']); ?></h2>
                        </div>
                        <i class="opacity-50 bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="text-white border-0 shadow-sm card bg-gradient bg-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 text-white-50">Pending</h6>
                            <h2 class="mb-0 fw-bold"><?php echo e($stats['pending_tasks']); ?></h2>
                        </div>
                        <i class="opacity-50 bi bi-hourglass-split fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="text-white border-0 shadow-sm card bg-gradient bg-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 text-white-50">Words Written</h6>
                            <h2 class="mb-0 fw-bold"><?php echo e(number_format($stats['total_words_completed'])); ?></h2>
                        </div>
                        <i class="opacity-50 bi bi-file-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 card">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters & Date Range</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.content-writers.show', $writer->id)); ?>" id="filterForm">
                <div class="row g-3">
                    <!-- Quick Filters -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Quick Filters:</label>
                        <div class="flex-wrap btn-group" role="group">
                            <?php $__currentLoopData = $quickFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn btn-outline-primary btn-sm quick-filter"
                                    data-start="<?php echo e($filter['start']); ?>" data-end="<?php echo e($filter['end']); ?>">
                                    <?php echo e($filter['label']); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Custom Date Range -->
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo e($startDate); ?>"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo e($endDate); ?>"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select">
                            <option value="">All Projects</option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"
                                    <?php echo e($projectId == $project->id ? 'selected' : ''); ?>>
                                    <?php echo e($project->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Todo" <?php echo e($status == 'Todo' ? 'selected' : ''); ?>>Todo</option>
                            <option value="In Progress" <?php echo e($status == 'In Progress' ? 'selected' : ''); ?>>In Progress
                            </option>
                            <option value="Review" <?php echo e($status == 'Review' ? 'selected' : ''); ?>>Review</option>
                            <option value="Completed" <?php echo e($status == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>Apply Filters
                        </button>
                        <a href="<?php echo e(route('admin.content-writers.show', $writer->id)); ?>"
                            class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="mb-4 row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Task Completion Timeline (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div id="timelineChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Words by Project</h6>
                </div>
                <div class="card-body">
                    <?php if($wordsByProject->count() > 0): ?>
                        <?php $__currentLoopData = $wordsByProject->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3">
                                <div class="mb-1 d-flex justify-content-between">
                                    <small class="text-truncate" style="max-width: 60%;">
                                        <?php echo e($item['project']); ?>

                                    </small>
                                    <small class="fw-bold"><?php echo e(number_format($item['words'])); ?> words</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary"
                                        style="width: <?php echo e(($item['words'] / $wordsByProject->first()['words']) * 100); ?>%">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo e($item['tasks']); ?> tasks</small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="mb-0 text-center text-muted">No data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="card">
        <div class="text-white card-header bg-dark">
            <h6 class="mb-0 text-light"><i class="bi bi-table me-2"></i>Tasks List (<?php echo e($tasks->count()); ?> tasks)</h6>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Task</th>
                            <th width="20%">Project</th>
                            <th width="12%">Status</th>
                            <th width="10%">Words Target</th>
                            <th width="10%">Words Done</th>
                            <th width="10%">Date</th>
                            <th width="8%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="fw-bold text-primary"><?php echo e($task->title); ?></div>
                                    <?php if($task->contentTask): ?>
                                        <small class="text-muted">
                                            <i class="bi bi-bookmark me-1"></i><?php echo e($task->contentTask->sub_type); ?>

                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($task->project): ?>
                                        <a href="<?php echo e(route('projects.show', $task->project->id)); ?>"
                                            class="text-decoration-none" target="_blank">
                                            <?php echo e($task->project->title); ?>

                                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $badgeClass = match ($task->status) {
                                            'Completed' => 'bg-success',
                                            'In Progress' => 'bg-primary',
                                            'Review' => 'bg-warning',
                                            'Todo' => 'bg-secondary',
                                            default => 'bg-secondary',
                                        };
                                    ?>
                                    <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($task->status); ?></span>
                                </td>
                                <td>
                                    <?php if($task->contentTask): ?>
                                        <strong><?php echo e(number_format($task->contentTask->no_of_words ?? 0)); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $wordsCompleted = $task->completions->sum('field_value');
                                    ?>
                                    <?php if($wordsCompleted > 0): ?>
                                        <strong class="text-success"><?php echo e(number_format($wordsCompleted)); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo e($task->created_at->format('M d, Y')); ?></small>
                                    <?php if($task->status == 'Completed' && $task->completed_date): ?>
                                        <br><small class="text-success">✓
                                            <?php echo e(\Carbon\Carbon::parse($task->completed_date)->format('M d')); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#taskModal<?php echo e($task->id); ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Task Detail Modal -->
                            <div class="modal fade" id="taskModal<?php echo e($task->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="text-white modal-header bg-primary">
                                            <h5 class="modal-title">Task Details</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Task Title:</label>
                                                    <p><?php echo e($task->title); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Status:</label>
                                                    <p><span
                                                            class="badge <?php echo e($badgeClass); ?>"><?php echo e($task->status); ?></span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Project:</label>
                                                    <p>
                                                        <?php if($task->project): ?>
                                                            <a href="<?php echo e(route('projects.show', $task->project->id)); ?>"
                                                                target="_blank">
                                                                <?php echo e($task->project->title); ?>

                                                            </a>
                                                        <?php else: ?>
                                                            N/A
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                                <?php if($task->contentTask): ?>
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Sub Type:</label>
                                                        <p><?php echo e($task->contentTask->sub_type); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Target Words:</label>
                                                        <p><?php echo e(number_format($task->contentTask->no_of_words ?? 0)); ?>

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Completed Words:</label>
                                                        <p class="text-success fw-bold">
                                                            <?php echo e(number_format($wordsCompleted)); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-12">
                                                    <label class="fw-bold">Description:</label>
                                                    <p><?php echo e($task->description ?? 'No description provided'); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-bold">Created:</label>
                                                    <p><?php echo e($task->created_at->format('M d, Y h:i A')); ?></p>
                                                </div>
                                                <?php if($task->completed_date): ?>
                                                    <div class="col-md-6">
                                                        <label class="fw-bold">Completed:</label>
                                                        <p><?php echo e(\Carbon\Carbon::parse($task->completed_date)->format('M d, Y h:i A')); ?>

                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mt-3">
                                                <a href="<?php echo e(route('tasks.show', $task->id)); ?>"
                                                    class="btn btn-primary" target="_blank">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Open Full Task Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="py-4 text-center text-muted">
                                    <i class="mb-2 bi bi-inbox fs-1 d-block"></i>
                                    No tasks found for the selected filters
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Timeline Chart
        var timelineOptions = {
            series: [{
                name: 'Tasks Completed',
                data: <?php echo json_encode($timeline->pluck('count')->toArray()); ?>

            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            colors: ['#0d6efd'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                }
            },
            xaxis: {
                categories: <?php echo json_encode($timeline->pluck('date')->toArray()); ?>

            },
            yaxis: {
                title: {
                    text: 'Tasks'
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " tasks";
                    }
                }
            }
        };

        var timelineChart = new ApexCharts(document.querySelector("#timelineChart"), timelineOptions);
        timelineChart.render();

        // Quick filter buttons
        document.querySelectorAll('.quick-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('input[name="start_date"]').value = this.dataset.start;
                document.querySelector('input[name="end_date"]').value = this.dataset.end;
                document.getElementById('filterForm').submit();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/admin/content-writers/show.blade.php ENDPATH**/ ?>