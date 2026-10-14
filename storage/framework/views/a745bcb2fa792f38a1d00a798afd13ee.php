<?php
    $pageUrl = $member ? route('admin.offsite-seo.show', $member->id) : route('admin.offsite-seo.index');
    $linksUrl = route('admin.offsite-seo.links.index', $query + array_filter(['user_id' => $member->id ?? $selectedUser]));
    $ratedRel = $quality['dofollow'] + $quality['nofollow'];
    $cards = [
        ['Total Tasks', $stats['total'], 'Assigned in this period', '#0d6efd'],
        ['Completed', $stats['completed'], 'Completed in this period', '#198754'],
        ['Pending', $stats['pending'], 'Still open', '#ffc107'],
        ['Overdue', $stats['overdue'], 'Open and past due date', '#dc3545'],
        ['Links Created', $stats['links'], $stats['links_per_day'] . ' per working day', '#6f42c1'],
        ['Manual Links', $stats['manual'], 'From Manual Links tasks', '#20c997'],
    ];
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
        <?php echo e(__($member ? $member->name . ' — Offsite SEO Performance' : 'Offsite SEO Performance')); ?>

     <?php $__env->endSlot(); ?>

    <style>
        .os-stat { border: 0; border-left: 4px solid var(--os-color, #0d6efd); }
        .os-stat .os-value { font-size: 1.75rem; font-weight: 700; line-height: 1.1; }
        .os-stat .os-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: #6c757d; }
        .os-stat .os-sub { font-size: .75rem; color: #6c757d; }
    </style>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <?php if($member): ?>
                <a href="<?php echo e(route('admin.offsite-seo.index', $query)); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>All employees</a>
            <?php endif; ?>
        </div>
        <a href="<?php echo e($linksUrl); ?>" class="btn btn-sm btn-success"><i class="bi bi-link-45deg me-1"></i>Manage links</a>
    </div>

    
    <?php $__env->startComponent('admin.offsite-seo._filters', ['action' => $pageUrl, 'range' => $range, 'ranges' => $ranges, 'from' => $from, 'to' => $to]); ?>
        <?php if (! ($member)): ?>
            <div class="col-md-3 col-6">
                <label class="form-label fw-semibold">Employee</label>
                <select name="user_id" class="form-select">
                    <option value="">All Offsite SEO employees</option>
                    <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e($selectedUser == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>
    <?php echo $__env->renderComponent(); ?>

    <?php if($team->isEmpty()): ?>
        <div class="alert alert-warning">No users have the OFFSITE role yet. Assign the role under Users to see them here.</div>
    <?php endif; ?>

    
    <div class="mb-4 row g-3">
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $sub, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="shadow-sm card h-100 os-stat" style="--os-color: <?php echo e($color); ?>">
                    <div class="card-body">
                        <div class="os-label"><?php echo e($label); ?></div>
                        <div class="os-value"><?php echo e(number_format($value)); ?></div>
                        <div class="os-sub"><?php echo e($sub); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mb-4 row g-3">
        
        <div class="col-lg-8">
            <div class="shadow-sm card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i><?php echo e($group === 'month' ? 'Monthly' : 'Daily'); ?> Productivity</h6>
                    <div class="btn-group btn-group-sm">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['group' => 'day'])); ?>"
                            class="btn <?php echo e($group === 'day' ? 'btn-primary' : 'btn-outline-primary'); ?>">Daily</a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['group' => 'month'])); ?>"
                            class="btn <?php echo e($group === 'month' ? 'btn-primary' : 'btn-outline-primary'); ?>">Monthly</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="productivityChart"></div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="shadow-sm card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-award me-2"></i>Link Quality</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            <tr>
                                <td>Unique referring domains</td>
                                <td class="text-end fw-bold"><?php echo e($quality['domains']); ?> <small class="text-muted fw-normal">of <?php echo e($stats['links']); ?> links</small></td>
                            </tr>
                            <tr>
                                <td>Duplicate links</td>
                                <td class="text-end fw-bold <?php echo e($quality['duplicates'] ? 'text-danger' : ''); ?>"><?php echo e($quality['duplicates']); ?></td>
                            </tr>
                            <tr>
                                <td>Dofollow</td>
                                <td class="text-end fw-bold">
                                    <?php if($ratedRel): ?>
                                        <?php echo e(round($quality['dofollow'] / $ratedRel * 100)); ?>% <small class="text-muted fw-normal">(<?php echo e($quality['dofollow']); ?> of <?php echo e($ratedRel); ?>)</small>
                                    <?php else: ?>
                                        <span class="text-muted fw-normal">Not recorded</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Average DR</td>
                                <td class="text-end fw-bold">
                                    <?php if($quality['avg_dr'] !== null): ?>
                                        <?php echo e($quality['avg_dr']); ?> <small class="text-muted fw-normal">(<?php echo e($quality['dr_count']); ?> links)</small>
                                    <?php else: ?>
                                        <span class="text-muted fw-normal">Not recorded</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Live / Lost</td>
                                <td class="text-end fw-bold">
                                    <span class="text-success"><?php echo e($quality['live']); ?></span> /
                                    <span class="<?php echo e($quality['lost'] ? 'text-danger' : ''); ?>"><?php echo e($quality['lost']); ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Dofollow, DR and status come from what is entered with each link.</small>
                </div>
            </div>
        </div>
    </div>

    
    <?php if (! ($member)): ?>
        <div class="mb-4 shadow-sm card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-people-fill me-2"></i>Employees</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th class="text-center">Tasks</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Overdue</th>
                            <th class="text-center">Links</th>
                            <th class="text-center">Manual</th>
                            <th class="text-center">Links / day</th>
                            <th class="text-center">Unique domains</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($employee['name']); ?></div>
                                    <small class="text-muted"><?php echo e($employee['email']); ?></small>
                                </td>
                                <td class="text-center"><?php echo e($employee['total']); ?></td>
                                <td class="text-center text-success fw-semibold"><?php echo e($employee['completed']); ?></td>
                                <td class="text-center"><?php echo e($employee['pending']); ?></td>
                                <td class="text-center <?php echo e($employee['overdue'] ? 'text-danger fw-semibold' : ''); ?>"><?php echo e($employee['overdue']); ?></td>
                                <td class="text-center fw-semibold"><?php echo e($employee['links']); ?></td>
                                <td class="text-center"><?php echo e($employee['manual']); ?></td>
                                <td class="text-center"><?php echo e($employee['links_per_day']); ?></td>
                                <td class="text-center"><?php echo e($employee['domains']); ?></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.offsite-seo.show', ['user' => $employee['id']] + $query)); ?>"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Details</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="py-4 text-center text-muted">No Offsite SEO employees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="mb-4 shadow-sm card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Overdue Tasks
                <?php if($stats['overdue'] > $overdue->count()): ?>
                    <small class="text-muted">(top <?php echo e($overdue->count()); ?> of <?php echo e($stats['overdue']); ?>)</small>
                <?php endif; ?>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Task</th>
                        <th>Project</th>
                        <?php if (! ($member)): ?>
                            <th>Employee</th>
                        <?php endif; ?>
                        <th>Status</th>
                        <th>Due date</th>
                        <th class="text-end">Days overdue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $overdue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="text-decoration-none">#<?php echo e($task->id); ?> <?php echo e(Str::limit($task->title, 60)); ?></a></td>
                            <td><?php echo e($task->project->title ?? '—'); ?></td>
                            <?php if (! ($member)): ?>
                                <td><?php echo e($task->assignee_name); ?></td>
                            <?php endif; ?>
                            <td><span class="badge bg-secondary"><?php echo e($task->status); ?></span></td>
                            <td><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d M Y')); ?></td>
                            <td class="text-end text-danger fw-bold"><?php echo e($task->days_overdue); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center text-muted">No overdue tasks.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if($member): ?>
        <div class="mb-4 shadow-sm card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Latest Links in This Period</h6>
                <a href="<?php echo e($linksUrl); ?>" class="btn btn-sm btn-outline-primary">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Keyword</th>
                            <th>Link URL</th>
                            <th>DR</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-nowrap"><?php echo e(\Carbon\Carbon::parse($link->created_at)->format('d M Y')); ?></td>
                                <td><?php echo e($link->project ?? '—'); ?></td>
                                <td><span class="badge bg-light text-dark"><?php echo e($link->link_type); ?></span></td>
                                <td><?php echo e($link->keyword); ?></td>
                                <td>
                                    <a href="<?php echo e($link->url); ?>" target="_blank" rel="noopener"><?php echo e(Str::limit($link->url, 50)); ?></a>
                                    <?php if($link->is_duplicate): ?>
                                        <span class="badge bg-danger">Duplicate</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($link->dr ?? '—'); ?></td>
                                <td><?php echo e($link->link_status ? ucfirst($link->link_status) : '—'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-4 text-center text-muted">No links logged in this period.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const series = <?php echo json_encode($series, 15, 512) ?>;
            const keys = Object.keys(series);

            new ApexCharts(document.querySelector('#productivityChart'), {
                chart: { height: 320, type: 'line', toolbar: { show: false } },
                series: [
                    { name: 'Links created', type: 'column', data: keys.map(k => series[k].links) },
                    { name: 'Manual links', type: 'column', data: keys.map(k => series[k].manual) },
                    { name: 'Tasks completed', type: 'line', data: keys.map(k => series[k].completed) }
                ],
                xaxis: { categories: keys, tickAmount: Math.min(keys.length, 15), labels: { rotate: -45, style: { fontSize: '10px' } } },
                yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
                colors: ['#6f42c1', '#20c997', '#198754'],
                stroke: { width: [0, 0, 3], curve: 'smooth' },
                plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top' },
                noData: { text: 'No activity in this period' }
            }).render();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/admin/offsite-seo/index.blade.php ENDPATH**/ ?>