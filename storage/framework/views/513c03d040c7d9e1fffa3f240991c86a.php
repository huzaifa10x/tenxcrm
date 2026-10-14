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
        <?php echo e(__('Content Writers Performance')); ?>

     <?php $__env->endSlot(); ?>

    <div class="mb-4">
        <div class="card">
            <div class="text-white card-header bg-success">
                <h5 class="mb-0 text-light">
                    <i class="bi bi-people-fill me-2"></i>Content Writers Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <?php $__currentLoopData = $contentWriters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="border-0 shadow-sm card h-100 hover-shadow">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-center">
                                        <div class="text-white avatar-circle bg-success me-3">
                                            <?php echo e(strtoupper(substr($writer->name, 0, 2))); ?>

                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?php echo e($writer->name); ?></h6>
                                            <small class="text-muted"><?php echo e($writer->email); ?></small>
                                        </div>
                                    </div>

                                    <div class="mb-3 row g-2">
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-primary"><?php echo e($writer->total_tasks); ?></div>
                                                <small class="text-muted">Total</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-success"><?php echo e($writer->completed_tasks); ?>

                                                </div>
                                                <small class="text-muted">Done</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 text-center rounded bg-light">
                                                <div class="fs-5 fw-bold text-warning"><?php echo e($writer->pending_tasks); ?>

                                                </div>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        $completionRate =
                                            $writer->total_tasks > 0
                                                ? round(($writer->completed_tasks / $writer->total_tasks) * 100)
                                                : 0;
                                    ?>

                                    <div class="mb-3">
                                        <div class="mb-1 d-flex justify-content-between">
                                            <small class="text-muted">Completion Rate</small>
                                            <small class="fw-bold"><?php echo e($completionRate); ?>%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: <?php echo e($completionRate); ?>%"></div>
                                        </div>
                                    </div>

                                    <a href="<?php echo e(route('admin.content-writers.show', $writer->id)); ?>"
                                        class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-bar-chart-line me-1"></i>View Detailed Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/admin/content-writers/index.blade.php ENDPATH**/ ?>