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
        <?php echo e(__('Projects')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card basic-data-table container-fluid">
        <div class="card-header">
            <div class="flex-wrap gap-10 d-flex align-items-center justify-content-start">
                <?php if($userPermissions->contains('can add projects')): ?>
                    <a href="<?php echo e(route('projects.create')); ?>" class="btn btn-neutral-900">
                        Add New Project
                    </a>
                <?php endif; ?>
                <div>
                    <input type="text" id="searchProject" class="form-control" placeholder="Search Project..."
                        autocomplete="off">
                    <ul id="searchResults" class="dropdown-menu show" style="display: none;"></ul>
                </div>
                <?php if(auth()->user()->hasRole('ADMIN')): ?>
                    <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                        
                        

                        <?php $__currentLoopData = request()->except('sales_manager_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <select class="w-auto form-control" name="account_manager_id" onchange="this.form.submit()">
                            <option value="">-- Filter by AM --</option>
                            <?php $__currentLoopData = $accountManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $am): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($am->id); ?>"
                                    <?php echo e(request('account_manager_id') == $am->id ? 'selected' : ''); ?>>
                                    <?php echo e($am->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>
                <?php else: ?>
                    <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                        
                        <?php if(request('sales_manager_id')): ?>
                            <input type="hidden" name="sales_manager_id" value="<?php echo e(request('sales_manager_id')); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="account_manager_id" value="<?php echo e(auth()->user()->id); ?>">
                        <button class="btn btn-info-700 btn-sm" type="submit">Projects I Manage</button>
                    </form>
                <?php endif; ?>
                <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                    
                    

                    <?php $__currentLoopData = request()->except('account_manager_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <select class="w-auto form-control" name="sales_manager_id" onchange="this.form.submit()">
                        <option value="">-- Filter by Sales Manager --</option>
                        <?php $__currentLoopData = $salesManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"
                                <?php echo e(request('sales_manager_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>

                <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                    
                    

                    <?php $__currentLoopData = request()->except('account_manager_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <select class="w-auto form-control" name="tech_manager_id" onchange="this.form.submit()">
                        <option value="">-- Filter by Tech Manager --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"
                                <?php echo e(request('tech_manager_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>

                

                <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                    
                    <?php $__currentLoopData = request()->except('project_status'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <select class="w-auto form-control" name="project_status" onchange="this.form.submit()">
                        <option value="">-- Filter by status --</option>
                        <?php $__currentLoopData = ['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled', 'Completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>"
                                <?php echo e(request('project_status') == $status ? 'selected' : ''); ?>>
                                <?php echo e($status); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>

                <form method="GET" action="<?php echo e(route('projects.index')); ?>">
                    <button class="btn btn-danger-500 btn-sm" type="submit">Reset</button>
                </form>
            </div>
            <?php if(request('account_manager_id')): ?>
                <div class="mt-10 row">
                    <div class="mt-2 row row-cols-lg-5 gy-4">
                        <?php $__currentLoopData = $statusCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mt-1 col">
                                <div class="border shadow-none card bg-gradient-start-1 h-100">
                                    <div class="p-20 card-body">
                                        <div class="flex-wrap gap-3 d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1 fw-medium text-primary-light"><?php echo e($status); ?></p>
                                                <h6 class="mb-0"><?php echo e($count); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- card end -->
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-2 row row-cols-lg-5 gy-4">
                        <?php $__currentLoopData = $typeCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mt-1 col">
                                <div class="border shadow-none card bg-gradient-start-2 h-100">
                                    <div class="p-20 card-body">
                                        <div class="flex-wrap gap-3 d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1 fw-medium text-primary-light"><?php echo e($type); ?></p>
                                                <h6 class="mb-0"><?php echo e($count); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- card end -->
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="my-10 row gy-3">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="p-3 border border-white card shadow-2 radius-8 h-100 gradient-deep-two-1">
                            <div class="p-0 card-body">
                                <div class="flex-wrap gap-1 mb-8 d-flex align-items-center justify-content-between">
                                    <div class="gap-10 d-flex align-items-center">
                                        <span
                                            class="flex-shrink-0 mb-0 text-white w-48-px h-48-px bg-cyan-600 d-flex justify-content-center align-items-center rounded-circle h6">
                                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon1.svg')); ?>"
                                                alt="">
                                        </span>
                                        <div>
                                            <span class="fw-medium text-secondary-light text-md">Current Portfolio /
                                                MO</span>
                                            <h6 class="mt-2 fw-semibold"><?php echo e(number_format($currentPortfolio, 2)); ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="flex-wrap gap-12 mt-12 mb-0 text-sm d-flex align-items-center text-secondary-light">
                                    <span
                                        class="gap-1 px-6 py-2 text-sm bg-success-focus rounded-2 fw-medium text-success-main d-flex align-items-center">
                                        <i class="ri-arrow-right-up-line"></i> 00
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="p-3 border border-white card shadow-2 radius-8 h-100 gradient-deep-two-4">
                            <div class="p-0 card-body">
                                <div class="flex-wrap gap-1 mb-8 d-flex align-items-center justify-content-between">
                                    <div class="gap-10 d-flex align-items-center">
                                        <span
                                            class="flex-shrink-0 mb-0 text-white w-48-px h-48-px bg-success-600 d-flex justify-content-center align-items-center rounded-circle h6">
                                            <img src="<?php echo e(asset('/images/home-eleven/icons/home-eleven-icon4.svg')); ?>"
                                                alt="">
                                        </span>
                                        <div>
                                            <span class="fw-medium text-secondary-light text-md">New / Renew /
                                                UpSell</span>
                                            <h6 class="mt-2 fw-semibold"><?php echo e(number_format($newSell, 2)); ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="flex-wrap gap-12 mt-12 mb-0 text-sm d-flex align-items-center text-secondary-light">
                                    <span
                                        class="gap-1 px-6 py-2 text-sm bg-success-focus rounded-2 fw-medium text-success-main d-flex align-items-center">
                                        <i class="ri-arrow-right-up-line"></i>00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <style>
            th {
                cursor: grab;
                background: #f8f9fa;
            }
        </style>

        <div class="row gy-4">
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $contractEndDate = Carbon\Carbon::parse($project->contract_start_date)->addMonths(
                        $project->contract_term,
                    );
                    $isExpiringSoon =
                        $contractEndDate->isBefore(now()->addMonths(2)) && $contractEndDate->isAfter(now());
                    $pendingTasks = $project->tasks->where('status', 'Todo')->count();
                ?>

                <div class="p-3 col-lg-4">
                    <div class="text-center border shadow-sm shadow card h-100 radius-12">
                        <div class="p-24 card-body">
                            <div
                                class="p-1 mb-16 d-inline-flex align-items-center justify-content-center bg-gradient-danger text-danger-600 radius-12">
                                <img src="<?php echo e(asset('/public/storage/')); ?>/<?php echo e($project->companies->logo ?? ''); ?>"
                                    alt="" class="rounded img-fluid object-fit-cover rounded-end-4"
                                    style="max-width: 150px;">
                            </div>

                            <h6 class="mb-8 fw-semibold">
                                <a href="<?php echo e(route('projects.show', $project->id)); ?>" class="text-decoration-none">
                                    <?php echo e($project->title); ?>

                                </a>
                                <?php if($isExpiringSoon): ?>
                                    <span class="text-white badge bg-danger fs-8 ms-1">Expiring Soon</span>
                                <?php endif; ?>
                            </h6>

                            <hr class="mb-1">

                            <p class="mb-8 card-text text-secondary-light small">
                                <strong>#<?php echo e($project->id); ?></strong> • <?php echo e($project->project_type); ?><br>
                                <strong>Service:</strong> <?php echo e($project->project_type); ?><br>
                                <strong>Account Manager:</strong>
                                <?php echo e($project->accountManagers->pluck('name')->first() ?? 'N/A'); ?><br>
                                <?php if(auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                        auth()->user()->hasRole('ADMIN') ||
                                        ($project->createdBy && auth()->user()->id == $project->createdBy->id)): ?>
                                    <strong>Status:</strong> <?php echo e($project->status); ?><br>
                                    <strong>End Date:</strong> <?php echo e($contractEndDate->format('Y-m-d')); ?><br>
                                    <strong>Monthly Revenue:</strong>
                                    <?php echo e($project->latestRenewal
                                        ? round($project->latestRenewal->contract_value / $project->latestRenewal->contract_term, 2)
                                        : round($project->contract_value / $project->contract_term, 2)); ?>

                                    <br>
                                    <strong>Tech:</strong>
                                    <?php echo e($project->techManagers->pluck('name')->first() ?? 'N/A'); ?><br>
                                    <strong>Created By:</strong> <?php echo e(optional($project->createdBy)->name ?? 'N/A'); ?><br>
                                    <strong>Sales Manager:</strong>
                                    <?php echo e($project->salesManagers->pluck('name')->first() ?? 'N/A'); ?><br>
                                    <strong>Pending Tasks:</strong>
                                    <span class="px-6 py-2 border badge text-danger-600 border-danger-600 radius-4">
                                        <?php echo e($pendingTasks); ?>

                                    </span>
                                <?php endif; ?>
                            </p>

                            <div class="flex-wrap gap-2 mt-3 d-flex justify-content-center align-items-center">
                                <a href="<?php echo e(route('projects.show', $project->id)); ?>"
                                    class="gap-2 px-0 py-10 btn text-danger-600 hover-text-danger d-inline-flex align-items-center">
                                    View <iconify-icon icon="iconamoon:eye-light" class="text-xl"></iconify-icon>
                                </a>

                                <?php if(
                                    $userPermissions->contains('can edit projects') &&
                                        (auth()->user()->hasRole('ADMIN') || optional($project->createdBy)->id == auth()->user()->id)): ?>
                                    <a href="<?php echo e(route('projects.edit', $project->id)); ?>"
                                        class="gap-2 px-0 py-10 btn text-success-600 hover-text-success d-inline-flex align-items-center">
                                        Edit <iconify-icon icon="lucide:edit" class="text-xl"></iconify-icon>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-3">
            <?php echo e($projects->links('vendor.pagination.custom')); ?>

        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".project-status").forEach(function(select) {
                select.addEventListener("change", function() {
                    let projectId = this.getAttribute("data-project-id");
                    let newStatus = this.value;

                    fetch("<?php echo e(route('projects.updateStatus', ':id')); ?>".replace(':id',
                            projectId), {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute("content"),
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success("Project status updated!", "Success");
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.dragtable@2.0.15/jquery.dragtable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.dragtable@2.0.15/dragtable.min.css">
    <script>
        $(document).ready(function() {
            let table = $('#project-data-table');
            let storageKey = 'tableColumnOrder';

            function saveColumnOrder(sortOrder) {
                localStorage.setItem(storageKey, JSON.stringify(sortOrder));
            }

            function loadColumnOrder() {
                let savedOrder = localStorage.getItem(storageKey);
                if (!savedOrder) return;
                savedOrder = JSON.parse(savedOrder);
                let headerRow = table.find('thead tr');
                let bodyRows = table.find('tbody tr');
                let newHeader = [];
                let headerMap = {};
                let headers = headerRow.find('th');
                headers.each(function(index) {
                    let key = $(this).attr('data-key');
                    if (key) headerMap[key] = index;
                });
                savedOrder.forEach((key) => {
                    let th = headers.filter(`[data-key="${key}"]`);
                    if (th.length) newHeader.push(th.detach());
                });
                headerRow.append(newHeader);
                bodyRows.each(function() {
                    let row = $(this);
                    let newCells = new Array(savedOrder.length); // Initialize array to maintain order
                    row.children().each(function(index) {
                        let key = headers.eq(index).attr(
                            'data-key'); // Find key based on original order
                        let newIndex = savedOrder.indexOf(key); // Find where it should be placed
                        if (newIndex !== -1) {
                            newCells[newIndex] = $(this).detach(); // Place at correct index
                        }
                    });
                    row.append(newCells);
                });
            }
            table.dragtable({
                persistState: function(table) {
                    let sortOrder = {};
                    table.el.find('th').each(function(i) {
                        if (this.dataset.key) { // Use 'data-key' instead of 'id'
                            sortOrder[this.dataset.key] = i;
                        }
                    });
                    saveColumnOrder(Object.keys(sortOrder)); // Save order in localStorage
                }
            });
            loadColumnOrder();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#searchProject').on('input', function() {
                let query = $(this).val();

                if (query.length < 2) {
                    $('#searchResults').hide();
                    return;
                }

                $.ajax({
                    url: "<?php echo e(route('projects.search')); ?>",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(response) {
                        let dropdown = $('#searchResults');
                        dropdown.empty();

                        if (response.length > 0) {
                            response.forEach(function(project) {
                                dropdown.append(`
                                <li class="dropdown-item">
                                    <a href="<?php echo e(url('/projects/${project.id}')); ?>" class="text-dark d-block">
                                        ${project.title}
                                    </a>
                                </li>
                            `);
                            });
                            dropdown.show();
                        } else {
                            dropdown.hide();
                        }
                    }
                });
            });

            $(document).on('click', '#searchResults li', function() {
                window.location.href = $(this).find('a').attr('href');
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/projects/index.blade.php ENDPATH**/ ?>