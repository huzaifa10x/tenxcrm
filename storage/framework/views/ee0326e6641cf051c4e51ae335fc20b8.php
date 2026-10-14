

<?php
    $isAjax = request()->headers->get('X-Requested-With') === 'XMLHttpRequest'
              || request()->expectsJson();
    $action = route('crm.activities.update', $activity);
?>

<div class="modal-header border-bottom py-3 px-4">
    <h5 class="modal-title fw-semibold">Edit Activity</h5>
    <?php if($isAjax): ?>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    <?php endif; ?>
</div>

<form method="POST" action="<?php echo e($action); ?>" id="editActivityForm">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div class="modal-body p-0">
        <div class="row g-0">

            
            <div class="col-md-8 p-4">

                <div class="mb-3">
                    <input type="text" name="subject"
                           class="form-control form-control-lg fw-semibold border-0 border-bottom rounded-0 px-0"
                           value="<?php echo e(old('subject', $activity->subject)); ?>" required
                           style="font-size:1.1rem;box-shadow:none">
                </div>

                
                <div class="d-flex gap-2 mb-4">
                    <?php $__currentLoopData = \App\Models\Activity::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button"
                                class="type-btn <?php echo e(old('type', $activity->type) === $t ? 'active' : ''); ?>"
                                data-type="<?php echo e($t); ?>"
                                title="<?php echo e($t); ?>"
                                onclick="selectTypeEdit(this)">
                            <i class="bi <?php echo e(\App\Models\Activity::TYPE_ICONS[$t]); ?>"></i>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="type" id="editModalType" value="<?php echo e(old('type', $activity->type)); ?>">
                </div>

                <style>
                .type-btn{width:40px;height:40px;border-radius:8px;border:1.5px solid #dee2e6;
                background:#f8f9fa;display:flex;align-items:center;justify-content:center;
                cursor:pointer;font-size:1.1rem;transition:all .15s}
                .type-btn:hover,.type-btn.active{background:#0d6efd;border-color:#0d6efd;color:#fff}
                .notes-textarea{background:#fefce8;border:1px solid #fef08a}
                .notes-textarea:focus{background:#fefce8;border-color:#eab308;box-shadow:none}
                </style>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-clock text-muted"></i>
                    <input type="date" name="due_date"
                           class="form-control form-control-sm"
                           value="<?php echo e(old('due_date', $activity->due_date?->format('Y-m-d'))); ?>"
                           style="width:150px">
                    <input type="time" name="due_time"
                           class="form-control form-control-sm"
                           value="<?php echo e(old('due_time', $activity->due_time ? substr($activity->due_time,0,5) : '')); ?>"
                           style="width:120px">
                    <span class="text-muted">–</span>
                    <input type="time" name="end_time"
                           class="form-control form-control-sm"
                           value="<?php echo e(old('end_time', $activity->end_time ? substr($activity->end_time,0,5) : '')); ?>"
                           style="width:120px">
                </div>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-flag text-muted"></i>
                    <select name="priority" class="form-select form-select-sm w-auto">
                        <option value="Normal" <?php if(old('priority',$activity->priority)==='Normal'): echo 'selected'; endif; ?>>Normal</option>
                        <option value="High"   <?php if(old('priority',$activity->priority)==='High'): echo 'selected'; endif; ?>>High</option>
                    </select>
                </div>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-geo-alt text-muted"></i>
                    <input type="text" name="location" class="form-control form-control-sm"
                           placeholder="Location"
                           value="<?php echo e(old('location', $activity->location)); ?>">
                </div>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-camera-video text-muted"></i>
                    <input type="url" name="video_call_url" class="form-control form-control-sm"
                           placeholder="Video call URL"
                           value="<?php echo e(old('video_call_url', $activity->video_call_url)); ?>">
                </div>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-card-text text-muted"></i>
                    <input type="text" name="description" class="form-control form-control-sm"
                           placeholder="Description"
                           value="<?php echo e(old('description', $activity->description)); ?>">
                </div>

                
                <div class="d-flex gap-2 mb-3">
                    <i class="bi bi-sticky text-muted mt-1"></i>
                    <textarea name="note" rows="4"
                              class="form-control form-control-sm notes-textarea flex-grow-1"
                              placeholder="Notes…"><?php echo e(old('note', $activity->note)); ?></textarea>
                </div>

                
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-person-circle text-muted"></i>
                    <input type="text" name="assigned_to" class="form-control form-control-sm"
                           style="max-width:260px"
                           placeholder="Assigned to"
                           value="<?php echo e(old('assigned_to', $activity->assigned_to)); ?>">
                </div>

                
                <div class="d-flex gap-2 mb-3">
                    <i class="bi bi-link-45deg text-muted mt-1"></i>
                    <div class="flex-grow-1 d-flex flex-column gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-currency-dollar text-muted"></i>
                            </span>
                            <select name="deal_id" class="form-select form-select-sm border-start-0">
                                <option value="">Deal or Lead</option>
                                <?php $__currentLoopData = \App\Models\Deal::orderBy('title')->get(['id','title']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d->id); ?>"
                                        <?php if(old('deal_id',$activity->deal_id)==$d->id): echo 'selected'; endif; ?>>
                                        <?php echo e($d->title); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="contact_person"
                                   class="form-control form-control-sm border-start-0"
                                   placeholder="People"
                                   value="<?php echo e(old('contact_person', $activity->contact_person)); ?>">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-building text-muted"></i>
                            </span>
                            <input type="text" name="organization"
                                   class="form-control form-control-sm border-start-0"
                                   placeholder="Organization"
                                   value="<?php echo e(old('organization', $activity->organization)); ?>">
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4 p-4 border-start">
                <h6 class="fw-semibold mb-3">Status</h6>
                <select name="done" class="form-select form-select-sm mb-3">
                    <option value="To do" <?php if(old('done',$activity->done)==='To do'): echo 'selected'; endif; ?>>To do</option>
                    <option value="Done"  <?php if(old('done',$activity->done)==='Done'): echo 'selected'; endif; ?>>Done</option>
                </select>

                <h6 class="fw-semibold mb-2 mt-4">Phone numbers</h6>
                <div class="d-flex flex-column gap-2">
                    <input type="text" name="phone_work" class="form-control form-control-sm"
                           placeholder="Work" value="<?php echo e(old('phone_work',$activity->phone_work)); ?>">
                    <input type="text" name="phone_mobile" class="form-control form-control-sm"
                           placeholder="Mobile" value="<?php echo e(old('phone_mobile',$activity->phone_mobile)); ?>">
                    <input type="text" name="phone_home" class="form-control form-control-sm"
                           placeholder="Home" value="<?php echo e(old('phone_home',$activity->phone_home)); ?>">
                    <input type="text" name="phone_other" class="form-control form-control-sm"
                           placeholder="Other" value="<?php echo e(old('phone_other',$activity->phone_other)); ?>">
                </div>

                <h6 class="fw-semibold mb-2 mt-4">Email addresses</h6>
                <div class="d-flex flex-column gap-2">
                    <input type="email" name="email_work" class="form-control form-control-sm"
                           placeholder="Work" value="<?php echo e(old('email_work',$activity->email_work)); ?>">
                    <input type="email" name="email_home" class="form-control form-control-sm"
                           placeholder="Home" value="<?php echo e(old('email_home',$activity->email_home)); ?>">
                    <input type="email" name="email_other" class="form-control form-control-sm"
                           placeholder="Other" value="<?php echo e(old('email_other',$activity->email_other)); ?>">
                </div>
            </div>

        </div>
    </div>

    <div class="modal-footer border-top px-4 py-3 d-flex justify-content-end gap-2">
        <?php if($isAjax): ?>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <?php else: ?>
            <a href="<?php echo e(route('crm.activities.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-success px-4">Save Changes</button>
    </div>
</form>

<script>
function selectTypeEdit(btn) {
    document.querySelectorAll('#editActivityForm .type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('editModalType').value = btn.dataset.type;
}
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/activities/_form.blade.php ENDPATH**/ ?>