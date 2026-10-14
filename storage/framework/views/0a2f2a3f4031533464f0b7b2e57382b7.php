<style>
    .fc-toolbar-title {
        font-size: 16px !important;
    }

    .fc-h-event .fc-event-main {
        color: #000000 !important;
        font-size: 10px !important;
    }

    .show-am-projects-info,
    .show-am-new-sell-projects {
        cursor: pointer;
    }
</style>
<div class="row gy-3 mb-10">
    <div class="col-xxl-3 col-sm-6 show-am-projects-info">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon1.svg')); ?>" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">Current Portfolio / MO</span>
                            <h6 class="fw-semibold mt-2"><?php echo e(number_format($currentPortfolio, 2)); ?></h6>
                        </div>
                    </div>
                </div>
                <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                    <span
                        class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                        <i class="ri-arrow-right-up-line"></i> 00
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 am-projects-info" style="display: none">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0 table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Service</th>
                            <th>Contract Value</th>
                            <th>Contract Start Date</th>
                            <th>Contract Term</th>
                            <th>Monthly Revenue (w/o VAT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $projects->whereIn('status', ['Current']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('projects.show', $project->id)); ?>"><?php echo e($project->title); ?></a>
                                </td>
                                <td><?php echo e($project->project_type); ?></td>
                                <td><?php echo e($project->contract_value); ?></td>
                                <td><?php echo e($project->contract_start_date ?? ''); ?></td>
                                <td><?php echo e($project->contract_term); ?> /Mo</td>
                                <td><?php echo e($project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-2 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon2.svg')); ?>" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">Current Revenue</span>
                            <h6 class="fw-semibold mt-2">00</h6>
                        </div>
                    </div>
                </div>
                <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                    <span
                        class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                        <i class="ri-arrow-right-up-line"></i> 00
                </p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-3 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-lilac-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon3.svg')); ?>" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">Total Project Renewals</span>
                            <h6 class="fw-semibold mt-2"><?php echo e($totalRenewals); ?></h6>
                        </div>
                    </div>
                </div>
                <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                    <span
                        class="bg-danger-focus px-6 py-2 rounded-2 fw-medium text-danger-main text-sm d-flex align-items-center gap-1">
                        <i class="ri-arrow-right-down-line"></i> 00
                </p>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 show-am-new-sell-projects">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-4 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-success-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon4.svg')); ?>" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">New / Renew / UpSell</span>
                            <h6 class="fw-semibold mt-2"><?php echo e(number_format($newSell, 2)); ?></h6>
                        </div>
                    </div>
                </div>
                <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                    <span
                        class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                        <i class="ri-arrow-right-up-line"></i>00
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 am-new-sell-projects" style="display: none">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0 table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Service</th>
                            <th>Contract Value</th>
                            <th>Contract Start Date</th>
                            <th>Contract Term</th>
                            <th>Monthly Revenue (w/o VAT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $newSellProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('projects.show', $project->id)); ?>"><?php echo e($project->title); ?></a>
                                </td>
                                <td><?php echo e($project->project_type); ?></td>
                                <td><?php echo e($project->contract_value); ?></td>
                                <td><?php echo e($project->contract_start_date ?? ''); ?></td>
                                <td><?php echo e($project->contract_term); ?> /Mo</td>
                                <td><?php echo e($project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal231b75c24e6541acd34afd1abd545539 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal231b75c24e6541acd34afd1abd545539 = $attributes; } ?>
<?php $component = App\View\Components\Dashboards\AmCalendar::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboards.am-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Dashboards\AmCalendar::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal231b75c24e6541acd34afd1abd545539)): ?>
<?php $attributes = $__attributesOriginal231b75c24e6541acd34afd1abd545539; ?>
<?php unset($__attributesOriginal231b75c24e6541acd34afd1abd545539); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal231b75c24e6541acd34afd1abd545539)): ?>
<?php $component = $__componentOriginal231b75c24e6541acd34afd1abd545539; ?>
<?php unset($__componentOriginal231b75c24e6541acd34afd1abd545539); ?>
<?php endif; ?>

<?php if($statusCounts->count() && $typeCounts->count()): ?>
    <div class="border border-secondary card mb-10 overflow-hidden position-relative radius-12">
        <div
            class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
            <span class="fw-bold">Projects Status</span>
        </div>
        <div class="card-body">
            <div class="row row-cols-lg-5 mt-2 gy-4">
                <?php $__currentLoopData = $statusCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col mt-1">
                        <div class="card shadow-none border bg-gradient-start-1 h-100">
                            <div class="card-body p-20">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div>
                                        <p class="fw-medium text-primary-light mb-1"><?php echo e($status); ?></p>
                                        <h6 class="mb-0"><?php echo e($count); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="border border-secondary card mb-10 overflow-hidden position-relative radius-12">
        <div
            class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
            <span class="fw-bold">Projects Types</span>
        </div>
        <div class="card-body row">
            <?php $__currentLoopData = $typeCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $offcanvasId = 'typeProjectsOffcanvas_' . Str::slug($type);
                ?>

                <div class="col-3 my-1">
                    <div type="button" data-bs-toggle="offcanvas" data-bs-target="#<?php echo e($offcanvasId); ?>"
                        aria-controls="<?php echo e($offcanvasId); ?>" class="card shadow-none border bg-gradient-start-2 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1"><?php echo e($type); ?></p>
                                    <h6 class="mb-0"><?php echo e($count); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- card end -->
                </div>

                <div class="offcanvas offcanvas-start w-75 mt-0" data-bs-scroll="true" tabindex="-1"
                    id="<?php echo e($offcanvasId); ?>" aria-labelledby="<?php echo e($offcanvasId); ?>Label">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="<?php echo e($offcanvasId); ?>Label">
                            <?php echo e($type); ?> - <?php echo e($projects->where('project_type', $type)->count()); ?> Projects
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="card">
                            <div class="card-body p-0">
                                <table class="table table-striped table-bordered mb-0 small">
                                    <thead>
                                        <tr>
                                            <td>Project</td>
                                            <td>Client</td>
                                            <td>Revenue <span class="fs-7">/mo</span></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $projects->where('project_type', $type); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($project->title); ?></td>
                                                <td><a
                                                        href="<?php echo e($project->companies->website ?? '#'); ?>"><?php echo e($project->companies->name); ?></a>
                                                </td>
                                                <td>
                                                    <?php echo e($project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0); ?>

                                                    AED
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<div class="row gy-3 mb-10">
    <div class="col-lg-6">
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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-calendar4-week me-1"></i> Monthly Partner Links Summary
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Month</th>
                            <th>Total Links</th>
                            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SEO ADMIN')): ?>
                                <th>Total Amount (Rs.)</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $monthlyData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($data['month']); ?></td>
                                <td><?php echo e($data['links']); ?></td>
                                <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('SEO ADMIN')): ?>
                                    <td><?php echo e(number_format($data['amount'], 2)); ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<ul class="nav d-flex align-items-center justify-content-between border-gradient-tab nav-pills mb-0 w-100"
    id="pills-tab" role="tablist">
    <button class="nav-link d-flex align-items-center justify-content-center fs-4 fw-semibold active" id="pending-tab"
        data-bs-toggle="pill" data-bs-target="#pending-task" type="button" role="tab" type="button"
        role="tab" aria-controls="pills-to-do-list" aria-selected="true">
        All Pending Tasks
        <span
            class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert"><?php echo e($pendingTasks->count()); ?></span>
    </button>
</ul>
<div class="tab-content card p-0 mb-10" id="task-tabContent">
    <!-- PENDING TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane card-body table-responsive fade show active p-0"
        id="pending-task" role="tabpanel">
        <?php if($pendingTasks->count()): ?>
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($task->id); ?></td>
                            <td><a target="_blank"
                                    href="<?php echo e(route('projects.show', $task->project->id)); ?>"><?php echo e($task->project->title); ?>

                                    <div class="badge bg-warning fs-7 rounded text-dark">
                                        <?php echo e($task->project->status); ?>

                                    </div>
                                </a>
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
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending tasks found.</p>
        <?php endif; ?>
    </div>
</div>
<div class="card basic-data-table">
    <div class="card-header">
        <span class="fw-bold">Projects</span>
    </div>
    <div class="card-body table-responsive">
        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Contract End Date</th>
                    <th>Monthly Revenue (w/o VAT)</th>
                    <th>Tech</th>
                    <th>Created By</th>
                    <th>Sequence Pending Tasks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><a href="<?php echo e(route('projects.show', $project->id)); ?>"><?php echo e($project->title); ?></a>
                        </td>
                        <td><?php echo e($project->project_type); ?></td>
                        <td>
                            <?php if($userPermissions->contains('can update project status')): ?>
                                <?php if(auth()->user()->hasRole('ADMIN') || ($project->createdBy && $project->createdBy->id == auth()->user()->id)): ?>
                                    <select class="form-control project-status" data-project-id="<?php echo e($project->id); ?>"
                                        required>
                                        <option selected disabled value="">Select status</option>
                                        <option value="Current" <?php echo e($project->status == 'Current' ? 'selected' : ''); ?>>
                                            Current
                                        </option>
                                        <option value="Paused" <?php echo e($project->status == 'Paused' ? 'selected' : ''); ?>>
                                            Paused
                                        </option>
                                        <option value="WFF" <?php echo e($project->status == 'WFF' ? 'selected' : ''); ?>>
                                            WFF
                                        </option>
                                        <option value="Expired" <?php echo e($project->status == 'Expired' ? 'selected' : ''); ?>>
                                            Expired
                                        </option>
                                        <option value="Debt Collection"
                                            <?php echo e($project->status == 'Debt Collection' ? 'selected' : ''); ?>>Debt
                                            Collection
                                        </option>
                                        <option value="Cancelled"
                                            <?php echo e($project->status == 'Cancelled' ? 'selected' : ''); ?>>
                                            Cancelled
                                        </option>
                                        <option value="Completed"
                                            <?php echo e($project->status == 'Completed' ? 'selected' : ''); ?>>
                                            Completed
                                        </option>
                                    </select>
                                <?php else: ?>
                                    <?php echo e($project->status); ?>

                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e($project->status); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e(Carbon\Carbon::parse($project->contract_start_date)->addMonths($project->contract_term)->format('Y-m-d') ?? ''); ?>

                        </td>
                        <td><?php echo e($project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? ''); ?>

                        </td>
                        <td><?php echo e($project->techManagers->pluck('name')->first() ?? ''); ?></td>
                        <td>
                            <?php echo e($project->createdBy->name ?? 'N/A'); ?>

                        </td>
                        <td>
                            <span
                                class="badge text-sm fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border border-danger-600 text-danger-600 radius-4 text-white">
                                <?php echo e($project->tasks->where('status', 'Todo')->count() ?? 0); ?>

                            </span>
                        </td>
                        <td class="">
                            <div class="d-flex align-items-center gap-10">
                                <a href="<?php echo e(route('projects.show', $project->id)); ?>"
                                    class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                    <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).on('click', '.show-am-projects-info', function() {
        $('.am-projects-info').slideToggle();
    })
    $(document).on('click', '.show-am-new-sell-projects', function() {
        $('.am-new-sell-projects').slideToggle();
    })
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/am-dashboard.blade.php ENDPATH**/ ?>