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
        <?php echo e(__('Companies')); ?>

     <?php $__env->endSlot(); ?>

    <div class="card basic-data-table">
        <?php if($userPermissions->contains('can manage companies')): ?>
            <div class="card-header d-flex align-items-center gap-10">
                <a class="btn btn-outline-primary" href="<?php echo e(route('companies.create')); ?>">Create New Company</a>
                <a class="btn btn-outline-primary-600"
                    href="<?php echo e(route('companies.index', ['sort_by' => 'name', 'sort_order' => 'asc'])); ?>">(Asc)</a>
                <a class="btn btn-outline-primary-800"
                    href="<?php echo e(route('companies.index', ['sort_by' => 'name', 'sort_order' => 'desc'])); ?>">(Desc)</a>
                <div>
                    <input type="text" id="searchCompany" class="form-control" placeholder="Search Company..."
                        autocomplete="off">
                    <ul id="searchResults" class="dropdown-menu show" style="display: none;"></ul>
                </div>
            </div>
        <?php endif; ?>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Website</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><a href="javascript:void(0)" class="text-primary-600"><?php echo e($company->id); ?></a></td>
                            <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        <?php if($company->logo): ?>
                                            <img src="<?php echo e(asset('/public/storage/')); ?>/<?php echo e($company->logo); ?>"
                                                class="img-fluid" width="36" alt="Logo" width="50">
                                        <?php endif; ?>
                                        <?php echo e($company->name); ?>

                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="text-decoration-underline" href="<?php echo e($company->website); ?>">Website</a>
                            </td>
                            <td><?php echo e($company->city); ?></td>
                            <td><?php echo e($company->country); ?></td>
                            <td>
                                <a href="<?php echo e(route('companies.show', $company->id)); ?>"
                                    class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                </a>
                                <?php if($userPermissions->contains('can manage companies')): ?>
                                    <a href="<?php echo e(route('companies.edit', $company->id)); ?>"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    <?php if(auth()->user()->hasRole('ADMIN')): ?>
                                        <form action="<?php echo e(route('companies.destroy', $company->id)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" style="margin-top: -5px;"
                                                onclick="return confirm('Are you sure you want to delete this company?')"
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($companies->links('vendor.pagination.custom')); ?>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#searchCompany').on('input', function() {
                let query = $(this).val();

                if (query.length < 2) {
                    $('#searchResults').hide();
                    return;
                }

                $.ajax({
                    url: "<?php echo e(route('company.search')); ?>",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(response) {
                        let dropdown = $('#searchResults');
                        dropdown.empty();

                        if (response.length > 0) {
                            response.forEach(function(company) {
                                dropdown.append(`
                                <li class="dropdown-item">
                                    <a href="<?php echo e(url('/companies/${company.id}')); ?>" class="text-dark d-block">
                                        ${company.name}
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/companies/index.blade.php ENDPATH**/ ?>