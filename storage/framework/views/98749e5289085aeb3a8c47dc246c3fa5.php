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
        <?php echo e(__('COMPANY - ') . $company->name); ?>

     <?php $__env->endSlot(); ?>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <?php if($company->logo): ?>
                    <div class="col-lg-12">
                        <img src="<?php echo e(asset('/public/storage/' . $company->logo)); ?>" class="img-fluid" alt="Logo"
                            width="50">
                    </div>
                <?php endif; ?>
                <div class="col-lg-12">
                    <label class="form-label">Company Name</label>
                    <input readonly value="<?php echo e($company->name); ?>" type="text" name="name" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company Website</label>
                    <input readonly value="<?php echo e($company->website); ?>" type="text" name="website" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company City</label>
                    <input readonly value="<?php echo e($company->city); ?>" type="text" name="city" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company Country</label>
                    <input readonly value="<?php echo e($company->country); ?>" type="text" name="country" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <span class="my-10 text-dark-1">Contact Details</span>
    <div class="card-body table-responsive">
        <table class="table bordered-table mb-0" id="dataTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="company-details-list">
                <!-- AJAX Data Will Be Loaded Here -->
            </tbody>
        </table>
    </div>

    <button class="btn btn-sm btn-cyan-600 my-20 add-new-company-detail">Add New Detail</button>

    <div class="card-body add-company-details" style="display: none;">
        <form id="company-detail-form">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <input type="hidden" id="detail_id" name="id"> <!-- Hidden Field for Update -->
                        <div class="col-lg-6">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Email</label>
                            <input name="email" type="text" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Phone Number</label>
                            <input name="phone" type="text" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">City</label>
                            <input name="city" type="text" class="form-control">
                        </div>
                        <input type="hidden" value="<?php echo e($company->id); ?>" name="company_id">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary mt-3">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            let companyId = "<?php echo e($company->id); ?>";

            function fetchCompanyDetails() {
                $.ajax({
                    url: "<?php echo e(route('company.details.index', ['company' => $company->id])); ?>",
                    type: "GET",
                    success: function(data) {
                        let rows = '';
                        data.forEach(detail => {
                            rows += `
                    <tr data-id="${detail.id}" class="hidden-row">
                        <td>${detail.name}</td>
                        <td>${detail.email}</td>
                        <td>${detail.phone}</td>
                        <td>${detail.city}</td>
                        <td class="d-flex align-items-center gap-10">
                            <button class="edit-detail bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" 
                                data-id="${detail.id}" 
                                data-name="${detail.name}" 
                                data-email="${detail.email}" 
                                data-phone="${detail.phone}" 
                                data-city="${detail.city}">
                               <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                            </button>
                            <button class="delete-detail remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle" data-id="${detail.id}">
                                <iconify-icon icon="fluent:delete-24-regular"
                                class="menu-icon"></iconify-icon>
                            </button>
                        </td>
                    </tr>
                `;
                        });

                        $('#company-details-list').html(rows);

                        // Animation: Show each row with a fade-in effect
                        $(".hidden-row").hide().each(function(index) {
                            $(this).delay(100 * index).fadeIn(300);
                        });
                    }
                });
            }
            fetchCompanyDetails();

            // Show Form on Button Click
            $(document).on("click", ".add-new-company-detail", function() {
                $('#company-detail-form')[0].reset();
                $('#detail_id').val('');
                $('.add-company-details').slideDown();
            });

            // Handle Form Submission (Insert & Update)
            $('#company-detail-form').submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let actionUrl = $('#detail_id').val() ?
                    "<?php echo e(route('company.details.update', ':id')); ?>".replace(':id', $('#detail_id').val()) :
                    "<?php echo e(route('company.details.store')); ?>";

                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        toastr.success("Detail saved successfully!");
                        $('.add-company-details').slideUp();
                        fetchCompanyDetails();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        alert(Object.values(errors).flat().join("\n"));
                    }
                });
            });

            // Edit Button Click
            $(document).on("click", ".edit-detail", function() {
                $('#detail_id').val($(this).data('id'));
                $('input[name="name"]').val($(this).data('name'));
                $('input[name="email"]').val($(this).data('email'));
                $('input[name="phone"]').val($(this).data('phone'));
                $('input[name="city"]').val($(this).data('city'));
                $('input[name="website"]').val($(this).data('website') || '');
                $('.add-company-details').slideDown();
            });

            $(document).on("click", ".delete-detail", function() {
                let detailId = $(this).data("id");

                if (confirm("Are you sure you want to delete this detail?")) {
                    $.ajax({
                        url: "<?php echo e(route('company.details.destroy', ':id')); ?>".replace(':id',
                            detailId),
                        type: "DELETE",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function(response) {
                            toastr.info("Detail deleted successfully!");
                            fetchCompanyDetails();
                        },
                        error: function(xhr) {
                            alert("Error deleting detail.");
                        }
                    });
                }
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/companies/show.blade.php ENDPATH**/ ?>