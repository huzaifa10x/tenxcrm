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
        <?php echo e(__('Website Project')); ?> - <?php echo e($websiteProject->project->title); ?>

     <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <!-- Project Overview -->
    <div class="mb-3 row gy-3">
        <div class="col-lg-3">
            <div class="border card border-primary">
                <div class="card-body">
                    <h6 class="mb-2 text-primary">Platform</h6>
                    <h4><?php echo e($websiteProject->platform); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="border card border-warning">
                <div class="card-body">
                    <h6 class="mb-2 text-warning">Design Status</h6>
                    <h5><?php echo e($websiteProject->design_status); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="border card border-info">
                <div class="card-body">
                    <h6 class="mb-2 text-info">Development Status</h6>
                    <h5><?php echo e($websiteProject->development_status); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border <?php echo e($websiteProject->approved_for_go_live ? 'border-success' : 'border-secondary'); ?>">
                <div class="card-body">
                    <h6 class="<?php echo e($websiteProject->approved_for_go_live ? 'text-success' : 'text-secondary'); ?> mb-2">Go-Live Status</h6>
                    <h5>
                        <?php if($websiteProject->go_live_date): ?>
                            <span class="badge bg-success">Live</span>
                        <?php elseif($websiteProject->approved_for_go_live): ?>
                            <span class="badge bg-warning">Ready</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Target Date & Days Remaining -->
    <?php if($websiteProject->target_go_live_date): ?>
    <div class="alert <?php echo e($websiteProject->isOverdue() ? 'alert-danger' : 'alert-info'); ?>">
        <strong>Target Go-Live Date:</strong> <?php echo e($websiteProject->target_go_live_date->format('M d, Y')); ?>

        <?php $daysUntil = $websiteProject->getDaysUntilGoLive(); ?>
        <?php if($daysUntil !== null && !$websiteProject->go_live_date): ?>
            <?php if($daysUntil > 0): ?>
                | <strong><?php echo e($daysUntil); ?> days remaining</strong>
            <?php elseif($daysUntil == 0): ?>
                | <strong class="text-warning">Due Today!</strong>
            <?php else: ?>
                | <strong class="text-danger">Overdue by <?php echo e(abs($daysUntil)); ?> days</strong>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Project Details -->
    <div class="mb-3 row gy-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Project Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('website-projects.update', $websiteProject->id)); ?>" method="POST" id="updateForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <input type="text" class="form-control" value="<?php echo e($websiteProject->project->title); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Developer Assigned</label>
                            <select name="developer_assigned" class="form-control" onchange="this.form.submit()">
                                <option value="">Not Assigned</option>
                                <?php $__currentLoopData = $developers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dev->id); ?>" <?php echo e($websiteProject->developer_assigned == $dev->id ? 'selected' : ''); ?>>
                                        <?php echo e($dev->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lead Developer</label>
                            <input type="text" class="form-control" value="<?php echo e($websiteProject->leadDeveloper->name ?? 'Not Assigned'); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Has Current SEO</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="has_current_seo" value="1"
                                    <?php echo e($websiteProject->has_current_seo ? 'checked' : ''); ?> onchange="this.form.submit()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Has Current Google Ads</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="has_current_google_ads" value="1"
                                    <?php echo e($websiteProject->has_current_google_ads ? 'checked' : ''); ?> onchange="this.form.submit()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Go-Live Date</label>
                            <input type="date" name="target_go_live_date" class="form-control"
                                value="<?php echo e($websiteProject->target_go_live_date?->format('Y-m-d')); ?>" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Links & Files</h5>
                </div>
                <div class="card-body">
                    <?php if($websiteProject->figma_link): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Figma Design Link</label>
                            <a href="<?php echo e($websiteProject->figma_link); ?>" target="_blank" class="d-block">
                                <?php echo e($websiteProject->figma_link); ?>

                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($websiteProject->staging_link): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Staging Link</label>
                            <a href="<?php echo e($websiteProject->staging_link); ?>" target="_blank" class="d-block">
                                <?php echo e($websiteProject->staging_link); ?>

                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($websiteProject->design_approval_screenshot): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Design Approval</label>
                            <a href="<?php echo e(asset('storage/' . $websiteProject->design_approval_screenshot)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                View Screenshot
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if($websiteProject->completion_agreement): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Completion Agreement</label>
                            <a href="<?php echo e(asset('storage/' . $websiteProject->completion_agreement)); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                View Document
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Design Phase -->
    <div class="mb-3 card">
        <div class="card-header bg-warning-subtle">
            <h5 class="mb-0">Design Phase</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('website-projects.update-design-status', $websiteProject->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row gy-3">
                    <div class="col-lg-4">
                        <label class="form-label">Design Status</label>
                        <select name="design_status" class="form-control" required>
                            <option value="Not Started" <?php echo e($websiteProject->design_status == 'Not Started' ? 'selected' : ''); ?>>Not Started</option>
                            <option value="In Progress" <?php echo e($websiteProject->design_status == 'In Progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="Completed" <?php echo e($websiteProject->design_status == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                            <option value="Approved" <?php echo e($websiteProject->design_status == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Figma Link</label>
                        <input type="url" name="figma_link" class="form-control" placeholder="https://figma.com/..." value="<?php echo e($websiteProject->figma_link); ?>">
                        <small class="text-muted">Required when marking as Completed</small>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Client Approval Screenshot</label>
                        <input type="file" name="design_approval_screenshot" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted">Required when marking as Approved</small>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-warning">Update Design Status</button>
                    </div>
                </div>

                <?php if($websiteProject->design_completed_at): ?>
                    <div class="mt-3">
                        <small class="text-muted">Design completed on: <?php echo e($websiteProject->design_completed_at->format('M d, Y H:i')); ?></small>
                    </div>
                <?php endif; ?>

                <?php if($websiteProject->design_approved_at): ?>
                    <div class="mt-2">
                        <small class="text-success">Design approved on: <?php echo e($websiteProject->design_approved_at->format('M d, Y H:i')); ?></small>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Development Phase -->
    <div class="mb-3 card">
        <div class="card-header bg-info-subtle">
            <h5 class="mb-0">Development Phase</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('website-projects.update-development-status', $websiteProject->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row gy-3">
                    <div class="col-lg-4">
                        <label class="form-label">Development Status</label>
                        <select name="development_status" class="form-control" required>
                            <option value="Not Started" <?php echo e($websiteProject->development_status == 'Not Started' ? 'selected' : ''); ?>>Not Started</option>
                            <option value="In Progress" <?php echo e($websiteProject->development_status == 'In Progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="Internal QA" <?php echo e($websiteProject->development_status == 'Internal QA' ? 'selected' : ''); ?>>Internal QA (Lead Dev Review)</option>
                            <option value="Client Review" <?php echo e($websiteProject->development_status == 'Client Review' ? 'selected' : ''); ?>>Client Review</option>
                            <option value="Completed" <?php echo e($websiteProject->development_status == 'Completed' ? 'selected' : ''); ?>>Completed (Ready for Go-Live)</option>
                            <option value="Delivered" <?php echo e($websiteProject->development_status == 'Delivered' ? 'selected' : ''); ?>>Delivered / Live</option>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Staging Link</label>
                        <input type="url" name="staging_link" class="form-control" placeholder="https://staging.example.com" value="<?php echo e($websiteProject->staging_link); ?>">
                        <small class="text-muted">Required when marking as In Progress</small>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Completion Agreement</label>
                        <input type="file" name="completion_agreement" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted">Sign-off email/WhatsApp when Completed</small>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-info">Update Development Status</button>
                    </div>
                </div>

                <?php if($websiteProject->development_completed_at): ?>
                    <div class="mt-3">
                        <small class="text-success">Development completed on: <?php echo e($websiteProject->development_completed_at->format('M d, Y H:i')); ?></small>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Pre-Go-Live Checklist -->
    <?php if($websiteProject->development_status === 'Completed'): ?>
    <div class="mb-3 card">
        <div class="card-header bg-success-subtle">
            <h5 class="mb-0">Pre-Go-Live Checklist</h5>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <!-- Lead Dev QA -->
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header <?php echo e($websiteProject->lead_dev_qa_approved ? 'bg-success text-white' : ''); ?>">
                            <h6 class="mb-0">Lead Developer Final QA
                                <?php if($websiteProject->lead_dev_qa_approved): ?>
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                                $leadDevChecklist = $websiteProject->checklists()->where('type', 'lead_dev_qa')->first();
                            ?>

                            <?php if(!$websiteProject->lead_dev_qa_approved): ?>
                                <form action="<?php echo e(route('website-projects.submit-checklist', $websiteProject->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="type" value="lead_dev_qa">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="code_quality_reviewed" value="1" <?php echo e($leadDevChecklist?->code_quality_reviewed ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Code Quality Reviewed</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="security_checked" value="1" <?php echo e($leadDevChecklist?->security_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Security Checked</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="performance_optimized" value="1" <?php echo e($leadDevChecklist?->performance_optimized ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Performance Optimized</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="cross_browser_tested" value="1" <?php echo e($leadDevChecklist?->cross_browser_tested ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Cross-browser Tested</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="QA Notes"><?php echo e($leadDevChecklist?->notes); ?></textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            <?php else: ?>
                                <p class="mb-0 text-success">Approved by <?php echo e($websiteProject->leadDevQAApprovedBy->name); ?> on <?php echo e($websiteProject->lead_dev_qa_approved_at->format('M d, Y')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- AM Validation -->
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header <?php echo e($websiteProject->am_validation_approved ? 'bg-success text-white' : ''); ?>">
                            <h6 class="mb-0">AM Validation
                                <?php if($websiteProject->am_validation_approved): ?>
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                                $amChecklist = $websiteProject->checklists()->where('type', 'am_validation')->first();
                            ?>

                            <?php if(!$websiteProject->am_validation_approved): ?>
                                <form action="<?php echo e(route('website-projects.submit-checklist', $websiteProject->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="type" value="am_validation">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="forms_working" value="1" <?php echo e($amChecklist?->forms_working ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Forms Working</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="content_validated" value="1" <?php echo e($amChecklist?->content_validated ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Content Validated</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="responsiveness_checked" value="1" <?php echo e($amChecklist?->responsiveness_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Responsiveness Checked</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="Validation Notes"><?php echo e($amChecklist?->notes); ?></textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            <?php else: ?>
                                <p class="mb-0 text-success">Approved by <?php echo e($websiteProject->amValidationApprovedBy->name); ?> on <?php echo e($websiteProject->am_validation_approved_at->format('M d, Y')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- SEO Tech Approval (if has SEO) -->
                <?php if($websiteProject->has_current_seo): ?>
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header <?php echo e($websiteProject->seo_tech_approved ? 'bg-success text-white' : ''); ?>">
                            <h6 class="mb-0">SEO Tech Approval
                                <?php if($websiteProject->seo_tech_approved): ?>
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                                $seoChecklist = $websiteProject->checklists()->where('type', 'seo_tech')->first();
                            ?>

                            <?php if(!$websiteProject->seo_tech_approved): ?>
                                <form action="<?php echo e(route('website-projects.submit-checklist', $websiteProject->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="type" value="seo_tech">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="urls_checked" value="1" <?php echo e($seoChecklist?->urls_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">URLs Checked</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="meta_setup_checked" value="1" <?php echo e($seoChecklist?->meta_setup_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Meta Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="tracking_setup" value="1" <?php echo e($seoChecklist?->tracking_setup ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Tracking Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="indexing_checked" value="1" <?php echo e($seoChecklist?->indexing_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Indexing Checked</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="page_speed_checked" value="1" <?php echo e($seoChecklist?->page_speed_checked ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Page Speed Checked</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="SEO Notes"><?php echo e($seoChecklist?->notes); ?></textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            <?php else: ?>
                                <p class="mb-0 text-success">Approved by <?php echo e($websiteProject->seoTechApprovedBy->name); ?> on <?php echo e($websiteProject->seo_tech_approved_at->format('M d, Y')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ads AM Approval (if has Ads) -->
                <?php if($websiteProject->has_current_google_ads): ?>
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header <?php echo e($websiteProject->ads_am_approved ? 'bg-success text-white' : ''); ?>">
                            <h6 class="mb-0">Ads AM Approval
                                <?php if($websiteProject->ads_am_approved): ?>
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php
                                $adsChecklist = $websiteProject->checklists()->where('type', 'ads_am')->first();
                            ?>

                            <?php if(!$websiteProject->ads_am_approved): ?>
                                <form action="<?php echo e(route('website-projects.submit-checklist', $websiteProject->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="type" value="ads_am">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="conversions_setup" value="1" <?php echo e($adsChecklist?->conversions_setup ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Conversions Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="tracking_validated" value="1" <?php echo e($adsChecklist?->tracking_validated ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Tracking Validated</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="forms_tracking" value="1" <?php echo e($adsChecklist?->forms_tracking ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Forms Tracking</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="pixel_ga4_setup" value="1" <?php echo e($adsChecklist?->pixel_ga4_setup ? 'checked' : ''); ?>>
                                        <label class="form-check-label">Pixel/GA4 Setup</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="Ads Notes"><?php echo e($adsChecklist?->notes); ?></textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            <?php else: ?>
                                <p class="mb-0 text-success">Approved by <?php echo e($websiteProject->adsAmApprovedBy->name); ?> on <?php echo e($websiteProject->ads_am_approved_at->format('M d, Y')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Go Live Button -->
    <?php if($websiteProject->approved_for_go_live && !$websiteProject->go_live_date): ?>
    <div class="mb-3 card border-success">
        <div class="text-center card-body">
            <h4 class="mb-3 text-success">🎉 Website Ready for Go-Live!</h4>
            <p>All approvals completed. Click below to mark as live.</p>
            <form action="<?php echo e(route('website-projects.mark-as-live', $websiteProject->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to mark this project as LIVE?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Mark as LIVE
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if($websiteProject->go_live_date): ?>
    <div class="alert alert-success">
        <h5>🎉 Website is LIVE!</h5>
        <p class="mb-0">Went live on: <?php echo e($websiteProject->go_live_date->format('M d, Y H:i')); ?></p>
    </div>
    <?php endif; ?>

    <!-- Activity History -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Activity History</h5>
        </div>
        <div class="card-body">
            <?php $__currentLoopData = $websiteProject->history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gap-3 mb-3 d-flex">
                    <div class="flex-shrink-0">
                        <iconify-icon icon="mdi:history" width="20" height="20"></iconify-icon>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0"><?php echo e($history->action); ?></p>
                        <small class="text-muted">
                            <?php echo e($history->user->name ?? 'System'); ?> • <?php echo e($history->created_at->diffForHumans()); ?>

                        </small>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/website-projects/show.blade.php ENDPATH**/ ?>