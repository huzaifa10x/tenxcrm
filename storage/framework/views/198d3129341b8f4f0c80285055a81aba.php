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
        <?php echo e(__('Website Projects')); ?>

     <?php $__env->endSlot(); ?>
    <div class="container py-4">
        <div class="row">
            
            <section class="mb-5 col-12">
                <h2 class="mb-4 text-2xl font-semibold text-gray-900">Overview</h2>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-3">

                    
                    <div class="col">
                        <div class="shadow-sm card bg-primary-subtle border-primary-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-primary">
                                    Total Projects
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-primary-emphasis">
                                    <?php echo e($stats['total_projects']); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <div class="shadow-sm card bg-warning-subtle border-warning-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-warning">
                                    In Design
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-warning-emphasis">
                                    <?php echo e($stats['in_design']); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <div class="shadow-sm card bg-info-subtle border-info-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-info">
                                    In Development
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-info-emphasis">
                                    <?php echo e($stats['in_development']); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <div class="shadow-sm card bg-secondary-subtle border-secondary-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-secondary">
                                    Awaiting Approval
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-secondary-emphasis">
                                    <?php echo e($stats['awaiting_approval']); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <div class="shadow-sm card bg-success-subtle border-success-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-success">
                                    Ready for Go Live
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-success-emphasis">
                                    <?php echo e($stats['ready_for_go_live']); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col">
                        <div class="shadow-sm card bg-light border-light h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-muted">
                                    Delivered
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-dark">
                                    <?php echo e($stats['delivered']); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="my-4">

            
            <section class="mb-5 col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h2 class="text-xl font-semibold text-gray-900">Filters</h2>
                    <?php if(request()->hasAny(['platform', 'design_status', 'developer_id', 'ready_for_go_live', 'overdue'])): ?>
                        <span class="border badge text-bg-success border-success-subtle rounded-pill">
                            Filters active
                        </span>
                    <?php endif; ?>
                </div>

                <form method="GET" action="<?php echo e(route('website-projects.index')); ?>"
                    class="p-4 shadow-sm card border-light">
                    <div class="row g-3">
                        
                        <div class="col-md-3">
                            <label for="platform"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Platform</label>
                            <select name="platform" id="platform" class="form-select form-select-sm">
                                <option value="">All Platforms</option>
                                <option value="Wordpress" <?php echo e(request('platform') == 'Wordpress' ? 'selected' : ''); ?>>
                                    Wordpress</option>
                                <option value="Shopify" <?php echo e(request('platform') == 'Shopify' ? 'selected' : ''); ?>>Shopify
                                </option>
                                
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label for="design_status"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Design
                                Status</label>
                            <select name="design_status" id="design_status" class="form-select form-select-sm">
                                <option value="">All Design Statuses</option>
                                <option value="In Progress"
                                    <?php echo e(request('design_status') == 'In Progress' ? 'selected' : ''); ?>>In Progress
                                </option>
                                <option value="Completed"
                                    <?php echo e(request('design_status') == 'Completed' ? 'selected' : ''); ?>>
                                    Completed</option>
                                
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label for="developer_id"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Developer</label>
                            <select name="developer_id" id="developer_id" class="form-select form-select-sm">
                                <option value="">All Developers</option>
                                <?php $__currentLoopData = $developers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $developer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($developer->id); ?>"
                                        <?php echo e(request('developer_id') == $developer->id ? 'selected' : ''); ?>>
                                        <?php echo e($developer->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="ready_for_go_live" id="ready_for_go_live" value="1"
                                    <?php echo e(request('ready_for_go_live') ? 'checked' : ''); ?>

                                    class="form-check-input text-success">
                                <label class="text-sm text-gray-700 form-check-label" for="ready_for_go_live">
                                    Ready for Go Live
                                </label>
                            </div>
                        </div>

                        
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="overdue" id="overdue" value="1"
                                    <?php echo e(request('overdue') ? 'checked' : ''); ?> class="form-check-input text-danger">
                                <label class="text-sm text-gray-700 form-check-label" for="overdue">
                                    Overdue
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex-wrap gap-3 mt-4 d-flex">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Apply Filters
                        </button>

                        <a href="<?php echo e(route('website-projects.index')); ?>" class="btn btn-outline-secondary btn-sm">
                            Reset Filters
                        </a>
                    </div>
                </form>
            </section>

            <hr class="my-4">

            
            <section class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h2 class="text-xl font-semibold text-gray-900">Project List</h2>
                    <p class="text-sm text-muted">
                        Showing
                        <span
                            class="fw-bold text-dark"><?php echo e($websiteProjects->firstItem()); ?>-<?php echo e($websiteProjects->lastItem()); ?></span>
                        of
                        <span class="fw-bold text-dark"><?php echo e($websiteProjects->total()); ?></span> projects
                    </p>
                </div>

                <div class="shadow-sm card border-light">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Project
                                        Name
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Platform
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">
                                        Developer
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Design
                                        Status</th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">
                                        Development
                                        Status</th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Target
                                        Go-Live</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $websiteProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr style="cursor: pointer"
                                        onclick="console.log(window.location.href = '<?php echo e(route('website-projects.show', $project->id)); ?>')">
                                        <td class="align-middle">
                                            <div class="text-sm fw-medium text-dark">
                                                <?php echo e($project->project->title ?? 'N/A'); ?>

                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <?php
                                                $badgeClass = 'bg-secondary text-white'; // Default
                                                if ($project->platform === 'Wordpress') {
                                                    $badgeClass = 'bg-primary text-white';
                                                } elseif ($project->platform === 'Shopify') {
                                                    $badgeClass = 'bg-success text-white';
                                                }
                                            ?>
                                            <span
                                                class="badge <?php echo e($badgeClass); ?>"><?php echo e($project->platform ?? 'N/A'); ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                <?php echo e($project->developer->name ?? 'Unassigned'); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                <?php echo e($project->design_status ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                <?php echo e($project->development_status ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                <?php echo e($project->target_go_live_date
                                                    ? \Carbon\Carbon::parse($project->target_go_live_date)->format('M d, Y')
                                                    : 'N/A'); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-muted">
                                            No website projects found matching the criteria.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="border-0 card-footer bg-light">
                        <?php echo e($websiteProjects->appends(request()->except('page'))->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>
            </section>
        </div>
    </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/website-projects/index.blade.php ENDPATH**/ ?>