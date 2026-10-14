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
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('PipeDrive — Deals (List)')); ?> <?php $__env->endSlot(); ?>

    <?php echo $__env->make('pipedrive._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="crm-page">
        <?php if(session('success')): ?>
            <div class="alert alert-success py-2" style="font-size:.85rem;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="crm-toolbar">
            <h1><i class="bi bi-list-ul me-2"></i>Deals</h1>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-group crm-view-toggle">
                    <a href="<?php echo e(route('pipedrive.deals.index', ['view' => 'index'])); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-kanban me-1"></i>Board</a>
                    <a href="<?php echo e(route('pipedrive.deals.index', ['view' => 'list'])); ?>" class="btn btn-sm btn-primary"><i class="bi bi-list-ul me-1"></i>List</a>
                </div>
                <a href="<?php echo e(route('pipedrive.deals.export')); ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-download me-1"></i>Export</a>
                <a href="<?php echo e(route('pipedrive.deals.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Deal</a>
            </div>
        </div>

        <div class="crm-filter-bar">
            <div class="row g-2">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label"><i class="bi bi-search me-1"></i>Search</label>
                    <input type="text" id="fSearch" class="form-control" placeholder="Deal, person, org…">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                    <select id="fStatus" class="form-select">
                        <option value="">All</option>
                        <option value="open">Open</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label"><i class="bi bi-person-badge me-1"></i>Owner</label>
                    <select id="fOwner" class="form-select">
                        <option value="">All owners</option>
                        <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($o->id); ?>"><?php echo e($o->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100" id="fApply"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                </div>
            </div>
        </div>

        <div class="crm-panel">
            <div class="crm-panel-head d-flex justify-content-between">
                <span>Deals (<span id="dealCount"><?php echo e($deals->flatten()->count()); ?></span>)</span>
                <span>Total: AED <span id="dealTotal">0.00</span></span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th>Title</th><th>Stage</th><th>Status</th><th>Person</th>
                            <th>Organization</th><th>Owner</th><th>Value</th><th>Close</th><th></th>
                        </tr>
                    </thead>
                    <tbody id="dealsBody">
                        <tr><td colspan="9" class="crm-empty"><i class="bi bi-hourglass"></i>Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.getElementById('dealsBody');
            const badge = { open: 'secondary', won: 'success', lost: 'danger' };

            function load() {
                const params = new URLSearchParams({
                    search: fSearch.value, status: fStatus.value, owner_id: fOwner.value
                });
                fetch(`<?php echo e(route('pipedrive.deals.list')); ?>?${params}`, { headers: { 'Accept': 'application/json' }})
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('dealCount').textContent = data.total;
                        document.getElementById('dealTotal').textContent = data.totalValue;
                        if (!data.deals.length) {
                            body.innerHTML = `<tr><td colspan="9" class="crm-empty"><i class="bi bi-inbox"></i>No deals found</td></tr>`;
                            return;
                        }
                        body.innerHTML = data.deals.map(d => `
                            <tr style="cursor:pointer" onclick="location.href='${d.url}'">
                                <td class="fw-semibold">${d.title}</td>
                                <td><span class="badge" style="background:${d.stage_color||'#ccc'};color:#fff;">${d.stage||'—'}</span></td>
                                <td><span class="badge bg-${badge[d.status]||'secondary'}">${d.status}</span></td>
                                <td>${d.person||'—'}</td>
                                <td>${d.organization||'—'}</td>
                                <td>${d.owner||'—'}</td>
                                <td class="text-success fw-semibold">${d.currency} ${d.value}</td>
                                <td>${d.expected_close||'—'}</td>
                                <td><a href="${d.url}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()"><i class="bi bi-eye"></i></a></td>
                            </tr>`).join('');
                    });
            }
            document.getElementById('fApply').addEventListener('click', load);
            [fSearch, fStatus, fOwner].forEach(el => el.addEventListener('keyup', e => { if (e.key === 'Enter') load(); }));
            load();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/deals/list.blade.php ENDPATH**/ ?>