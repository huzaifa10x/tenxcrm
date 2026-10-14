<?php if($paginator->hasPages()): ?>
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 my-24">
        <span>Showing <?php echo e($paginator->firstItem()); ?> to <?php echo e($paginator->lastItem()); ?> of <?php echo e($paginator->total()); ?>

            entries</span>
        <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">

            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="<?php echo e($paginator->previousPageUrl()); ?>">
                        <iconify-icon icon="ep:d-arrow-left"></iconify-icon>
                    </a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled"><span class="page-link"><?php echo e($element); ?></span></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item">
                                <a class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600 text-white"
                                    href="javascript:void(0)"><?php echo e($page); ?></a>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px"
                                    href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="<?php echo e($paginator->nextPageUrl()); ?>">
                        <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md"
                        href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-right"></iconify-icon>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/vendor/pagination/custom.blade.php ENDPATH**/ ?>