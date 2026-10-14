
<?php $isEdit = isset($deal); ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger py-2" style="font-size:.82rem;">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
<?php endif; ?>

<div class="row">
    
    <div class="col-lg-7">
        <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required
                   value="<?php echo e(old('title', $deal->title ?? '')); ?>" placeholder="e.g. Biryani Walla — SEO + Social">
        </div>

        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold">Value</label>
                <input type="number" step="0.01" min="0" name="value" class="form-control"
                       value="<?php echo e(old('value', $deal->value ?? '')); ?>" placeholder="0.00">
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold">Currency</label>
                <select name="currency" class="form-select">
                    <?php $__currentLoopData = ['AED','USD','PKR','SAR','EUR','GBP']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cur); ?>" <?php if(old('currency', $deal->currency ?? 'AED') === $cur): echo 'selected'; endif; ?>><?php echo e($cur); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Pipeline stage</label>
            <select name="stage_id" class="form-select">
                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($stage->id); ?>" <?php if(old('stage_id', $deal->stage_id ?? $stages->first()->id) == $stage->id): echo 'selected'; endif; ?>>
                        <?php echo e($stage->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Label</label>
                <input type="text" name="label" class="form-control" list="labelOptions"
                       value="<?php echo e(old('label', $deal->label ?? '')); ?>" placeholder="Hot / Warm / Cold">
                <datalist id="labelOptions">
                    <option value="Hot"><option value="Warm"><option value="Cold"><option value="Upsell">
                </datalist>
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Expected close date</label>
                <input type="date" name="expected_close_date" class="form-control"
                       value="<?php echo e(old('expected_close_date', optional($deal->expected_close_date ?? null)->format('Y-m-d'))); ?>">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Source channel</label>
                <input type="text" name="source_channel" class="form-control"
                       value="<?php echo e(old('source_channel', $deal->source_channel ?? '')); ?>" placeholder="Website, Referral…">
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Source channel ID</label>
                <input type="text" name="source_channel_id" class="form-control"
                       value="<?php echo e(old('source_channel_id', $deal->source_channel_id ?? '')); ?>">
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Visible to</label>
                <select name="visible_to" class="form-select">
                    <option value="owner" <?php if(old('visible_to', $deal->visible_to ?? 'owner') === 'owner'): echo 'selected'; endif; ?>>Owner only</option>
                    <option value="everyone" <?php if(old('visible_to', $deal->visible_to ?? '') === 'everyone'): echo 'selected'; endif; ?>>Everyone</option>
                </select>
            </div>
            <?php if(auth()->user()->hasRole('ADMIN')): ?>
                <div class="col-6">
                    <label class="form-label fw-semibold">Owner</label>
                    <select name="owner_id" class="form-select">
                        <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($o->id); ?>" <?php if(old('owner_id', $deal->owner_id ?? auth()->id()) == $o->id): echo 'selected'; endif; ?>><?php echo e($o->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="crm-panel mb-3">
            <div class="crm-panel-head"><i class="bi bi-person me-1"></i>Contact person</div>
            <div class="crm-panel-body">
                <input type="hidden" name="person_id" id="person_id" value="<?php echo e(old('person_id', $deal->person_id ?? '')); ?>">
                <div class="mb-2">
                    <label class="form-label">Name</label>
                    <input type="text" name="person_name" id="person_name" class="form-control" list="personList"
                           value="<?php echo e(old('person_name', $deal->person->name ?? '')); ?>" placeholder="Search or add new…" autocomplete="off">
                    <datalist id="personList"></datalist>
                </div>
                <div class="mb-2">
                    <label class="form-label">Phone</label>
                    <input type="text" name="person_phone" class="form-control"
                           value="<?php echo e(old('person_phone', $deal->person->phone ?? '')); ?>">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="person_email" class="form-control"
                           value="<?php echo e(old('person_email', $deal->person->email ?? '')); ?>">
                </div>
            </div>
        </div>

        <div class="crm-panel">
            <div class="crm-panel-head"><i class="bi bi-building me-1"></i>Organization</div>
            <div class="crm-panel-body">
                <input type="hidden" name="organization_id" id="organization_id" value="<?php echo e(old('organization_id', $deal->organization_id ?? '')); ?>">
                <label class="form-label">Name</label>
                <input type="text" name="organization_name" id="organization_name" class="form-control" list="orgList"
                       value="<?php echo e(old('organization_name', $deal->organization->name ?? '')); ?>" placeholder="Search or add new…" autocomplete="off">
                <datalist id="orgList"></datalist>
            </div>
        </div>
    </div>
</div>

<script>
    // Lightweight typeahead: fill datalists + capture an existing record's id
    function wireTypeahead(inputId, listId, hiddenId, url) {
        const input = document.getElementById(inputId);
        const list  = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);
        let cache = [];
        input.addEventListener('input', function () {
            hidden.value = ''; // typing means "maybe new"
            if (this.value.length < 2) return;
            fetch(`${url}?q=${encodeURIComponent(this.value)}`, { headers: { 'Accept': 'application/json' }})
                .then(r => r.json())
                .then(rows => {
                    cache = rows;
                    list.innerHTML = rows.map(r => `<option data-id="${r.id}" value="${r.name}">`).join('');
                    const match = rows.find(r => r.name === input.value);
                    if (match) hidden.value = match.id;
                });
        });
        input.addEventListener('change', function () {
            const match = cache.find(r => r.name === input.value);
            hidden.value = match ? match.id : '';
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        wireTypeahead('person_name', 'personList', 'person_id', '<?php echo e(route('pipedrive.persons.search')); ?>');
        wireTypeahead('organization_name', 'orgList', 'organization_id', '<?php echo e(route('pipedrive.organizations.search')); ?>');
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/deals/_form.blade.php ENDPATH**/ ?>