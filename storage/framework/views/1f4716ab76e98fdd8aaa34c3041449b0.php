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
        <?php echo e(__('Create Website Project')); ?>

     <?php $__env->endSlot(); ?>
    <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('ADMIN')): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Website Project Details</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('website-projects.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="row gy-3">
                        <!-- Project Selection -->
                        <?php
                            $selectedProjectId = request('project_id'); // e.g. ?project_id=1
                        ?>

                        <div class="col-lg-6">
                            <label class="form-label" for="project_id">Project *</label>
                            <select name="project_id" id="project_id" class="form-control" required>
                                <option value="">Select Project</option>

                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isSelected = $selectedProjectId && $selectedProjectId == $project->id;
                                    ?>

                                    <option value="<?php echo e($project->id); ?>"
                                        <?php if($isSelected): ?> selected <?php endif; ?>
                                        <?php if($selectedProjectId && !$isSelected): ?> disabled <?php endif; ?>>
                                        <?php echo e($project->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Platform -->
                        <div class="col-lg-6">
                            <label class="form-label" for="platform">Platform *</label>
                            <select name="platform" id="platform" class="form-control" required>
                                <option value="">Select Platform</option>
                                <option value="WordPress">WordPress</option>
                                <option value="Next.js">Next.js</option>
                            </select>
                            <?php $__errorArgs = ['platform'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">WordPress: Auto-assign to Fabiha | Next.js: Auto-assign to
                                Daniyal</small>
                        </div>

                        <!-- Developer Assigned -->
                        <div class="col-lg-6">
                            <label class="form-label" for="developer_assigned">Developer Assigned</label>
                            <select name="developer_assigned" id="developer_assigned" class="form-control">
                                <option value="">Auto-assign based on platform</option>
                                <?php $__currentLoopData = $developers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $developer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($developer->id); ?>"><?php echo e($developer->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['developer_assigned'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Lead Developer -->
                        <div class="col-lg-6">
                            <label class="form-label" for="lead_developer">Lead Developer</label>
                            <select name="lead_developer" id="lead_developer" class="form-control">
                                <option value="">Auto-assign to Huzaifa</option>
                                <?php $__currentLoopData = $leadDevelopers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leadDev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($leadDev->id); ?>"><?php echo e($leadDev->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['lead_developer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Has Current SEO -->
                        <div class="col-lg-6">
                            <label class="form-label">Website has Current SEO?</label>
                            <div class="gap-3 d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_seo" id="seo_yes"
                                        value="1">
                                    <label class="form-check-label" for="seo_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_seo" id="seo_no"
                                        value="0" checked>
                                    <label class="form-check-label" for="seo_no">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Has Current Google Ads -->
                        <div class="col-lg-6">
                            <label class="form-label">Website has Current Google Ads?</label>
                            <div class="gap-3 d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_google_ads"
                                        id="ads_yes" value="1">
                                    <label class="form-check-label" for="ads_yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_current_google_ads"
                                        id="ads_no" value="0" checked>
                                    <label class="form-check-label" for="ads_no">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Target Go-Live Date -->
                        <div class="col-lg-6">
                            <label class="form-label" for="target_go_live_date">Target Go-Live Date</label>
                            <input type="date" name="target_go_live_date" id="target_go_live_date"
                                class="form-control">
                            <?php $__errorArgs = ['target_go_live_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Notes -->
                        <div class="col-lg-12">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-sm text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-lg-12">
                            <div class="gap-3 d-flex justify-content-end">
                                <a href="<?php echo e(route('website-projects.index')); ?>" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Website Project</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/website-projects/create.blade.php ENDPATH**/ ?>