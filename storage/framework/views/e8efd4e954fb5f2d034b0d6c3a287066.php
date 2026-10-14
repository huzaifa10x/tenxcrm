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
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('PipeDrive — Deals')); ?> <?php $__env->endSlot(); ?>

    <?php echo $__env->make('pipedrive._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="crm-page">
        <?php if(session('success')): ?>
            <div class="alert alert-success py-2" style="font-size:.85rem;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="crm-toolbar">
            <h4><i class="bi bi-kanban me-2"></i><?php echo e($pipeline->name); ?></h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if($pipelines->count() > 1): ?>
                    <select class="form-select form-select-sm w-auto" onchange="location.href='?pipeline='+this.value">
                        <?php $__currentLoopData = $pipelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php if($p->id === $pipeline->id): echo 'selected'; endif; ?>><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>

                <div class="btn-group crm-view-toggle">
                    <a href="<?php echo e(route('pipedrive.deals.index', ['view' => 'index', 'pipeline' => $pipeline->id])); ?>"
                       class="btn btn-sm btn-primary"><i class="bi bi-kanban me-1"></i>Board</a>
                    <a href="<?php echo e(route('pipedrive.deals.index', ['view' => 'list', 'pipeline' => $pipeline->id])); ?>"
                       class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul me-1"></i>List</a>
                </div>

                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#crmFilters">
                    <i class="bi bi-funnel me-1"></i>Filters
                </button>

                <form action="<?php echo e(route('pipedrive.deals.import')); ?>" method="POST" enctype="multipart/form-data" class="d-inline" id="importForm">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" class="d-none" onchange="document.getElementById('importForm').submit()">
                    <button type="button" class="btn btn-sm btn-success" onclick="document.getElementById('importFile').click()">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </form>

                <a href="<?php echo e(route('pipedrive.deals.export', request()->query())); ?>" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-download me-1"></i>Export
                </a>

                <a href="<?php echo e(route('pipedrive.deals.create')); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Deal
                </a>
            </div>
        </div>

        
        <div class="collapse <?php echo e(request()->hasAny(['search','status','owner_id','label']) ? 'show' : ''); ?>" id="crmFilters">
            <form method="GET" class="crm-filter-bar">
                <input type="hidden" name="pipeline" value="<?php echo e($pipeline->id); ?>">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label"><i class="bi bi-search me-1"></i>Search</label>
                        <input type="text" name="search" class="form-control" value="<?php echo e(request('search')); ?>" placeholder="Deal, person, org…">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = ['open','won','lost']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($st); ?>" <?php if(request('status')===$st): echo 'selected'; endif; ?>><?php echo e(ucfirst($st)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label"><i class="bi bi-person-badge me-1"></i>Owner</label>
                        <select name="owner_id" class="form-select">
                            <option value="">All owners</option>
                            <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($o->id); ?>" <?php if(request('owner_id')==$o->id): echo 'selected'; endif; ?>><?php echo e($o->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Close from</label>
                        <input type="date" name="close_from" class="form-control" value="<?php echo e(request('close_from')); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Close to</label>
                        <input type="date" name="close_to" class="form-control" value="<?php echo e(request('close_to')); ?>">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-primary btn-sm"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                    <a href="<?php echo e(route('pipedrive.deals.index')); ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                </div>
            </form>
        </div>

        
        <div class="crm-board" id="crmBoard">
            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="crm-col">
                    <div class="crm-col-head" style="border-top-color: <?php echo e($stage->color); ?>;">
                        <div class="col-name"><?php echo e($stage->name); ?></div>
                        <div class="col-meta">
                            AED <?php echo e(number_format($stageTotals[$stage->id]['value'], 0)); ?>

                            · <?php echo e($stageTotals[$stage->id]['count']); ?> <?php echo e(Str::plural('deal', $stageTotals[$stage->id]['count'])); ?>

                        </div>
                    </div>
                    <div class="crm-col-body crm-stage-list" data-stage-id="<?php echo e($stage->id); ?>">
                        <?php $__empty_1 = true; $__currentLoopData = $deals->get($stage->id, collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php echo $__env->make('pipedrive.deals._card', ['deal' => $deal], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted text-center py-3" style="font-size:.75rem;opacity:.6;">Drop deals here</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = '<?php echo e(csrf_token()); ?>';

            document.querySelectorAll('.crm-stage-list').forEach(function (col) {
                new Sortable(col, {
                    group: 'crm-deals',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function (evt) {
                        const card     = evt.item;
                        const dealId   = card.dataset.dealId;
                        const newCol   = evt.to;
                        const stageId  = newCol.dataset.stageId;
                        const orderedIds = [...newCol.querySelectorAll('.crm-card')].map(c => c.dataset.dealId);

                        fetch(`<?php echo e(url('pipedrive/deals')); ?>/${dealId}/move`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ stage_id: stageId, ordered_ids: orderedIds })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success) { alert(data.message || 'Could not move deal'); location.reload(); }
                        })
                        .catch(() => { alert('Network error — reloading.'); location.reload(); });
                    }
                });
            });
        });
    </script>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/deals/index.blade.php ENDPATH**/ ?>