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
        <?php echo e(__('Edit Project')); ?>

     <?php $__env->endSlot(); ?>

    <?php if($userPermissions->contains('can edit projects')): ?>
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" action="<?php echo e(route('projects.update', $project->id)); ?>" method="POST"
                    id="edit-project-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="row gy-3">

                        <div class="col-lg-12">
                            <label class="form-label" for="title">Project Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo e($project->title); ?>">
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="description">Project Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10"><?php echo e($project->description); ?></textarea>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label" for="company_id">Company</label>
                            <select class="form-control w-100" name="company_id" id="company_id">
                                <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($project->companies): ?>
                                        <option <?php echo e($project->companies->id == $company->id ? 'selected' : ''); ?>

                                            value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mt-3 row">
                            <div class="col-lg-4">
                                <label class="form-label" for="sales_manager_ids">Sales Managers</label>
                                <select class="form-control w-100" name="sales_manager_ids[]" id="sales_manager_ids">
                                    <?php $__currentLoopData = $users->filter(fn($user) => $user->roles->pluck('name')->contains('SALES MANAGER')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(in_array($user->id, $project->salesManagers->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="account_manager_ids">Account Managers</label>
                                <select class="form-control w-100" name="account_manager_ids[]"
                                    id="account_manager_ids">
                                    <?php $__currentLoopData = $users->filter(fn($user) => $user->roles->pluck('name')->contains('AM')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(in_array($user->id, $project->accountManagers->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="tech_manager_ids">Tech Managers</label>
                                <select class="form-control w-100" name="tech_manager_ids[]" id="tech_manager_ids">
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(in_array($user->id, $project->techManagers->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="seo_admin_ids">Seo Admin</label>
                                <select class="form-control w-100" name="seo_admin_ids[]" id="seo_admin_ids">
                                    <?php $__currentLoopData = $users->filter(fn($user) => $user->roles->pluck('name')->contains('SEO ADMIN')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(in_array($user->id, $project->seoAdmins->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label" for="content_writers_ids">Content Writer</label>
                                <select class="form-control w-100" name="content_writers_ids[]"
                                    id="content_writers_ids">
                                    <?php $__currentLoopData = $users->filter(fn($user) => $user->roles->pluck('name')->contains('CONTENT')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(in_array($user->id, $project->contentWriters->pluck('id')->toArray()) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option selected disabled value="">Select Category</option>
                                <option value="PLATINUM" <?php echo e($project->category == 'PLATINUM' ? 'selected' : ''); ?>>
                                    PLATINUM
                                </option>
                                <option value="GOLD" <?php echo e($project->category == 'GOLD' ? 'selected' : ''); ?>>GOLD
                                </option>
                                <option value="SILVER" <?php echo e($project->category == 'SILVER' ? 'selected' : ''); ?>>SILVER
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option selected disabled value="">Select status</option>
                                <option value="Current" <?php echo e($project->status == 'Current' ? 'selected' : ''); ?>>Current
                                </option>
                                <option value="Paused" <?php echo e($project->status == 'Paused' ? 'selected' : ''); ?>>Paused
                                </option>
                                <option value="WFF" <?php echo e($project->status == 'WFF' ? 'selected' : ''); ?>>WFF</option>
                                <option value="Expired" <?php echo e($project->status == 'Expired' ? 'selected' : ''); ?>>Expired
                                </option>
                                <option value="Debt Collection"
                                    <?php echo e($project->status == 'Debt Collection' ? 'selected' : ''); ?>>Debt Collection
                                </option>
                                <option value="Cancelled" <?php echo e($project->status == 'Cancelled' ? 'selected' : ''); ?>>
                                    Cancelled
                                <option value="Completed" <?php echo e($project->status == 'Completed' ? 'selected' : ''); ?>>
                                    Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="industry">Industry</label>
                            <select class="form-control" id="industry_id" name="industry_id" required>
                                <option selected disabled value="">Select Industry</option>
                                <?php $__currentLoopData = $industries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $industry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($industry->id); ?>"
                                        <?php echo e($project->industry_id == $industry->id ? 'selected' : ''); ?>>
                                        <?php echo e($industry->industry); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method_id">Payment Method</label>
                            <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                                <option selected disabled value="">Select Payment Method</option>
                                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentMethod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($paymentMethod->id); ?>"
                                        <?php echo e($project->payment_method_id == $paymentMethod->id ? 'selected' : ''); ?>>
                                        <?php echo e($paymentMethod->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-lg-6" style="display: none">
                            <label class="form-label" for="contract_start_date">Contract Start Date</label>
                            <input type="date" class="form-control date-picker" id="contract_start_date"
                                name="contract_start_date" value="<?php echo e($project->contract_start_date); ?>" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">Total Contract Value <span
                                    class="fs-7">(w/o VAT - in AED)</span></label>
                            <input type="number" class="form-control" id="contract_value" name="contract_value"
                                value="<?php echo e($project->contract_value); ?>" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">Contract Term</label>
                            <input type="number" class="form-control" id="contract_term" name="contract_term"
                                value="<?php echo e($project->contract_term); ?>" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="free_months">Free Months</label>
                            <input type="number" class="form-control" id="free_months" name="free_months"
                                value="<?php echo e($project->free_months); ?>" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_copy">Contract Copy</label>
                            <input type="file" name="contract_copy[]" multiple class="form-control">
                            <hr>
                        </div>
                        <div class="col-lg-12">
                            <?php if(!empty($project->contractFiles) && count($project->contractFiles) > 0): ?>
                                <label class="form-label" for="contract_copy">Current Contract Files</label>
                                <div class="row">
                                    <?php $__currentLoopData = $project->contractFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $filePath = asset('/public/storage/' . $file->file_path);
                                            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                        ?>
                                        <?php if(in_array($extension, $imageExtensions)): ?>
                                            <div class="col-lg-6">
                                                <div
                                                    class="p-3 overflow-hidden text-center border shadow-sm flex-column h-100 position-relative card d-flex justify-content-center align-items-center bg-light">
                                                    <button type="button"
                                                        onclick="deleteProjectFile(<?php echo e($project->id); ?>, <?php echo e($file->id); ?>)"
                                                        class="top-0 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute end-0">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                    <div
                                                        class="top-0 text-white badge position-absolute start-0 bg-primary">
                                                        <?php echo e($extension); ?>

                                                    </div>
                                                    <img style="width: 100px;" src="<?php echo e($filePath); ?>"
                                                        class="card-img-top img-fluid" alt="Contract Image">
                                                    <p class="mt-3 mb-1 text-dark"><?php echo e($file->file_name); ?></p>
                                                    <div class="text-center card-body">
                                                        <a href="<?php echo e($filePath); ?>" target="_blank"
                                                            class="px-3 py-2 btn fs-6 btn-sm btn-primary">View Full</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-lg-6">
                                                <div
                                                    class="p-3 overflow-hidden text-center border shadow-sm flex-column h-100 position-relative card d-flex justify-content-center align-items-center bg-light">
                                                    <button type="button"
                                                        onclick="deleteProjectFile(<?php echo e($project->id); ?>, <?php echo e($file->id); ?>)"
                                                        class="top-0 remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute end-0">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                    <div
                                                        class="top-0 text-white badge position-absolute start-0 bg-primary">
                                                        <?php echo e($extension); ?>

                                                    </div>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('/images/doc.png')); ?>" width="50"
                                                            height="50" alt="<?php echo e($extension); ?> File">
                                                    </div>
                                                    <p class="mb-1 text-dark"><?php echo e($file->file_name); ?></p>
                                                    <div class="gap-2 d-flex align-items-center">
                                                        <a href="<?php echo e($filePath); ?>" target="_blank"
                                                            class="px-3 py-2 btn fs-6 btn-sm btn-primary">View</a>
                                                        <a href="<?php echo e($filePath); ?>" download
                                                            class="px-3 py-2 btn fs-6 btn-sm btn-success">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-danger">No contract files attached.</p>
                            <?php endif; ?>
                            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                <?php if(!empty($project->contractFiles) && count($project->contractFiles) > 0): ?>
                                    <button class="mt-3 btn btn-sm btn-danger" type="button"
                                        id="deleteAllAttachmentsBtn">
                                        Delete All Attachments
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="mt-3 btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#projectAttachmentsModal">
                                    Add
                                    <?php echo e(!empty($project->contractFiles) && count($project->contractFiles) ? 'More' : ''); ?>

                                    Project Files
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="handover_notes">Handover Notes</label>
                            <textarea required class="form-control" name="handover_notes" id="handover_notes" cols="30" rows="10"><?php echo e($project->handover_notes); ?></textarea>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="project_type">Project Type</label>
                            <select class="form-control" id="project_type" name="project_type" required>
                                <option selected disabled value="">Select Project Type</option>
                                <option value="SEO" <?php echo e($project->project_type == 'SEO' ? 'selected' : ''); ?>>SEO
                                </option>
                                <option value="Google Ads"
                                    <?php echo e($project->project_type == 'Google Ads' ? 'selected' : ''); ?>>
                                    Google Ads</option>
                                <option value="Social Media"
                                    <?php echo e($project->project_type == 'Social Media' ? 'selected' : ''); ?>>Social Media
                                </option>
                                <option value="Web Development"
                                    <?php echo e($project->project_type == 'Web Development' ? 'selected' : ''); ?>>Web Development
                                </option>
                                <option value="Web Maintenance"
                                    <?php echo e($project->project_type == 'Web Maintenance' ? 'selected' : ''); ?>>Web Maintenance
                                </option>
                                <option value="Hosting" <?php echo e($project->project_type == 'Hosting' ? 'selected' : ''); ?>>
                                    Hosting</option>
                                <option value="AI" <?php echo e($project->project_type == 'AI' ? 'selected' : ''); ?>>
                                    AI</option>
                                <option value="Other" <?php echo e($project->project_type == 'Other' ? 'selected' : ''); ?>>Other
                                </option>
                            </select>
                        </div>
                        <div class="row" id="extra-fields-container">
                            
                            <?php if($project->project_type == 'SEO'): ?>
                                <div class="col-lg-6">
                                    <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                                    <input required class="form-control" type="number" id="number_of_keywords"
                                        name="number_of_keywords"
                                        value="<?php echo e($project->seoProject->number_of_keywords ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_1_percentage">Guarantee for 1st Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_1_percentage"
                                        name="milestone_1_percentage"
                                        value="<?php echo e($project->seoProject->milestone_1_percentage ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_1_date">Guarantee for 1st Milestone
                                        Date</label>
                                    <input required class="form-control date-picker" type="date"
                                        id="milestone_1_date" name="milestone_1_date"
                                        value="<?php echo e($project->seoProject->milestone_1_date ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_2_percentage"
                                        name="milestone_2_percentage"
                                        value="<?php echo e($project->seoProject->milestone_2_percentage ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone
                                        Date</label>
                                    <input required class="form-control date-picker" type="date"
                                        id="milestone_2_date" name="milestone_2_date"
                                        value="<?php echo e($project->seoProject->milestone_2_date ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_3_percentage"
                                        name="milestone_3_percentage"
                                        value="<?php echo e($project->seoProject->milestone_3_percentage ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone
                                        Date</label>
                                    <input required class="form-control date-picker" type="date"
                                        id="milestone_3_date" name="milestone_3_date"
                                        value="<?php echo e($project->seoProject->milestone_3_date ?? ''); ?>">
                                </div>
                            <?php elseif($project->project_type == 'Social Media'): ?>
                                <div class="col-lg-6">
                                    <label class="form-label" for="channels">Channels</label>
                                    <select class="form-control" id="channels" name="channels[]" multiple required>
                                        <option value="Facebook">Facebook</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="LinkedIn">LinkedIn</option>
                                        <option value="Twitter">Twitter</option>
                                        <option value="YouTube">YouTube</option>
                                        <option value="TikTok">TikTok</option>
                                        <option value="Snapchat">Snapchat</option>
                                        <option value="Pinterest">Pinterest</option>
                                        <option value="Google My Business">Google My Business</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="video_shoots_per_month">Video shoots Per
                                        Month</label>
                                    <input class="form-control" type="number" id="video_shoots_per_month"
                                        name="video_shoots_per_month"
                                        value="<?php echo e($project->socialMediaProject->video_shoots_per_month ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="static_posts">Static Posts Per Month</label>
                                    <input class="form-control" type="number" id="static_posts" name="static_posts"
                                        value="<?php echo e($project->socialMediaProject->static_posts ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="reels">Reels Per Month</label>
                                    <input class="form-control" type="text" id="reels" name="reels"
                                        value="<?php echo e($project->socialMediaProject->reels ?? ''); ?>">
                                </div>
                            <?php elseif($project->project_type == 'Web Development' && $project->webDevProject): ?>
                                <div class="col-lg-6">
                                    <label class="form-label" for="payment_type">Payment Type</label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value="Partial"
                                            <?php echo e($project->webDevProject->payment_type == 'Partial' ? 'selected' : ''); ?>>
                                            Partial</option>
                                        <option value="Full"
                                            <?php echo e($project->webDevProject->payment_type == 'Full' ? 'selected' : ''); ?>>
                                            Full
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="remaining_payment">Remaining Payment</label>
                                    <input class="form-control" type="number" id="remaining_payment"
                                        name="remaining_payment"
                                        value="<?php echo e($project->webDevProject->remaining_payment ?? ''); ?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="site_condition">Site Condition</label>
                                    <select class="form-control" id="site_condition" name="site_condition" required>
                                        <option value="New Project"
                                            <?php echo e($project->webDevProject->site_condition == 'New Project' ? 'selected' : ''); ?>>
                                            New Project</option>
                                        <option value="Maintenance Project"
                                            <?php echo e($project->webDevProject->site_condition == 'Maintenance Project' ? 'selected' : ''); ?>>
                                            Maintenance Project</option>
                                    </select>
                                </div>
                            <?php else: ?>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3 col-lg-6">
                            <button class="btn btn-primary" type="submit">Save Project</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="projectAttachmentsModal" tabindex="-1"
            aria-labelledby="projectAttachmentsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projectAttachmentsModalLabel">Choose Project
                            Attachments
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="file" id="fileInput">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="uploadProjectFiles(<?php echo e($project->id); ?>)"
                            class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                $('#project_type').change(function() {
                    var projectType = $(this).val();
                    if (projectType) {
                        $.ajax({
                            url: '<?php echo e(route('projects-get-extra-fields')); ?>',
                            method: 'GET',
                            data: {
                                project_type: projectType
                            },
                            success: function(response) {
                                $('#extra-fields-container').html(
                                    response);
                            }
                        });
                    } else {
                        $('#extra-fields-container').html('');
                    }
                });

                $('#sales_manager_ids').select2({
                    placeholder: 'Select Sales Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
                $('#account_manager_ids').select2({
                    placeholder: 'Select Account Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
                $('#tech_manager_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
                $('#seo_admin_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
                $('#content_writers_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
            });
        </script>
        <script>
            function deleteProjectFile(projectId, fileId) {
                if (confirm("Are you sure you want to delete this file?")) {
                    fetch(`<?php echo e(route('projects.deleteFile', ['project' => '__PROJECT_ID__', 'file' => '__FILE_ID__'])); ?>`
                            .replace('__PROJECT_ID__', projectId).replace('__FILE_ID__', fileId), {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                    "Content-Type": "application/json",
                                }
                            })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.info("File deleted successfully!", "Info");
                                location.reload();
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            }

            function uploadProjectFiles(projectId) {
                let fileInput = document.getElementById("fileInput");
                if (fileInput.files.length === 0) {
                    toastr.info("Please select at least one file!", "Info");
                    return;
                }

                let formData = new FormData();
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append("contract_copy[]", fileInput.files[i]);
                }
                formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

                fetch(`<?php echo e(route('projects.uploadFiles', ['project' => '__PROJECT_ID__'])); ?>`.replace('__PROJECT_ID__',
                        projectId), {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success("File uploaded successfully!", "Success");
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

            document.getElementById('deleteAllAttachmentsBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to delete all attachments?')) {
                    // Send DELETE request using AJAX
                    fetch("<?php echo e(route('projects.deleteAllFiles', ['project' => $project->id])); ?>", {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('All attachments deleted successfully!');
                                location.reload(); // Refresh the page to reflect the changes
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('There was an error processing your request.');
                        });
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#channels').select2({
                    placeholder: 'Select Channels',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: true
                });
                var selectedChannels = <?php echo json_encode($project->socialMediaProject ? $project->socialMediaProject->channels : [], 15, 512) ?>;
                if (selectedChannels.length > 0) {
                    $('#channels').val(selectedChannels).trigger('change');
                }
            });
        </script>
    <?php else: ?>
        <?php echo $__env->make('vendor.not-allowed', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/projects/edit.blade.php ENDPATH**/ ?>