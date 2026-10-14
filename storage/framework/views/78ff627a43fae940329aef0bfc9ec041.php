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
        <?php echo e(__('Edit Task')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('tasks.update', $task->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>
                <div class="row gy-3">
                    <div class="col-lg-12">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="<?php echo e($task->title); ?>" required>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="10"><?php echo e($task->description); ?></textarea>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="project_id">Project</label>
                        <select class="form-control" id="project_id" name="project_id" required>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>"
                                    <?php echo e($task->project_id == $project->id ? 'selected' : ''); ?>><?php echo e($project->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <input type="hidden" name="created_by" value="<?php echo e(auth()->user()->id); ?>">

                    <div class="col-lg-6">
                        <label class="form-label" for="assigned_to">Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"
                                    <?php echo e($task->assigned_to == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="Low" <?php echo e($task->priority == 'Low' ? 'selected' : ''); ?>>Low</option>
                            <option value="Medium" <?php echo e($task->priority == 'Medium' ? 'selected' : ''); ?>>Medium</option>
                            <option value="High" <?php echo e($task->priority == 'High' ? 'selected' : ''); ?>>High</option>
                            <option value="Urgent" <?php echo e($task->priority == 'Urgent' ? 'selected' : ''); ?>>Urgent</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Todo" <?php echo e($task->status == 'Todo' ? 'selected' : ''); ?>>Todo</option>
                            <option value="In Progress" <?php echo e($task->status == 'In Progress' ? 'selected' : ''); ?>>In
                                Progress</option>
                            <option value="Review" <?php echo e($task->status == 'Review' ? 'selected' : ''); ?>>Review</option>
                            <option value="Blocked" <?php echo e($task->status == 'Blocked' ? 'selected' : ''); ?>>Blocked</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="CONTENT" <?php echo e($task->type == 'CONTENT' ? 'selected' : ''); ?>>CONTENT</option>
                            <option value="ONSITE" <?php echo e($task->type == 'ONSITE' ? 'selected' : ''); ?>>ONSITE</option>
                            <option value="OFFSITE" <?php echo e($task->type == 'OFFSITE' ? 'selected' : ''); ?>>OFFSITE</option>
                            <option value="DESIGN" <?php echo e($task->type == 'DESIGN' ? 'selected' : ''); ?>>DESIGN</option>
                            <option value="DEVELOPMENT" <?php echo e($task->type == 'DEVELOPMENT' ? 'selected' : ''); ?>>
                                DEVELOPMENT</option>
                            <option value="MANAGER" <?php echo e($task->type == 'MANAGER' ? 'selected' : ''); ?>>MANAGER</option>
                            <option value="ADMIN" <?php echo e($task->type == 'ADMIN' ? 'selected' : ''); ?>>ADMIN</option>
                            <option value="SALES" <?php echo e($task->type == 'SALES' ? 'selected' : ''); ?>>SALES</option>
                            <option value="ADHOC" <?php echo e($task->type == 'ADHOC' ? 'selected' : ''); ?>>ADHOC</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="due_date">Due Date</label>
                        <input type="date" class="form-control date-picker" id="due_date" name="due_date"
                            value="<?php echo e($task->due_date); ?>">
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="department_id">Department</label>
                        <select name="department_id" class="form-control" id="department_id">
                            <option value="" disabled>Select Department</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($depart->id); ?>"
                                    <?php echo e($task->department_id == $depart->id ? 'selected' : ''); ?>><?php echo e($depart->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="attachments">Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">You can upload multiple files.</small>
                    </div>

                    <div class="col-lg-12">
                        <?php if(!empty($task->attachments) && count($task->attachments) > 0): ?>
                            <label class="form-label" for="contract_copy">Current Contract Files</label>
                            <div class="row">
                                <?php $__currentLoopData = $task->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $filePath = asset('/public/storage/' . $file->file_path);
                                        $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    ?>
                                    <?php if(in_array($extension, $imageExtensions)): ?>
                                        <div class="col-lg-6">
                                            <div
                                                class="flex-column h-100 position-relative card overflow-hidden
                                             d-flex justify-content-center align-items-center bg-light border 
                                             shadow-sm text-center p-3">
                                                <button type="button" data-attachment-id="<?php echo e($attachment->id); ?>"
                                                    data-delete-url="<?php echo e(route('tasks.attachments.destroy', $attachment->id)); ?>"
                                                    class="delete-attachment remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button>
                                                <div
                                                    class="badge position-absolute top-0 start-0 bg-primary text-white">
                                                    <?php echo e($extension); ?>

                                                </div>
                                                <img style="width: 100px;" src="<?php echo e($filePath); ?>"
                                                    class="card-img-top img-fluid" alt="Contract Image">
                                                <p class="mb-1 mt-3 text-dark"><?php echo e($file->file_name); ?></p>
                                                <div class="card-body text-center">
                                                    <a href="<?php echo e($filePath); ?>" target="_blank"
                                                        class="btn fs-6 px-3 py-2 btn-sm btn-primary">View Full</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-lg-6">
                                            <div
                                                class="flex-column h-100 position-relative card overflow-hidden d-flex justify-content-center align-items-center bg-light border  shadow-sm text-center p-3">
                                                <button type="button"
                                                    onclick="deleteProjectFile(<?php echo e($project->id); ?>, <?php echo e($file->id); ?>)"
                                                    class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
                                                    <iconify-icon icon="fluent:delete-24-regular"
                                                        class="menu-icon"></iconify-icon>
                                                </button>
                                                <div
                                                    class="badge position-absolute top-0 start-0 bg-primary text-white">
                                                    <?php echo e($extension); ?>

                                                </div>
                                                <div class="mb-2">
                                                    <img src="<?php echo e(asset('/images/doc.png')); ?>" width="50"
                                                        height="50" alt="<?php echo e($extension); ?> File">
                                                </div>
                                                <p class="mb-1 text-dark"><?php echo e($file->file_name); ?></p>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <a href="<?php echo e($filePath); ?>" target="_blank"
                                                        class="btn fs-6 px-3 py-2 btn-sm btn-primary">View</a>
                                                    <a href="<?php echo e($filePath); ?>" download
                                                        class="btn fs-6 px-3 py-2 btn-sm btn-success">Download</a>
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
                            <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                                data-bs-target="#projectAttachmentsModal">
                                Add
                                <?php echo e(!empty($task->attachments) && count($task->attachments) ? 'More' : ''); ?>

                                Task Files
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success mt-3">Update Task</button>
                    </div>
                </div>
            </form>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/tasks/edit.blade.php ENDPATH**/ ?>