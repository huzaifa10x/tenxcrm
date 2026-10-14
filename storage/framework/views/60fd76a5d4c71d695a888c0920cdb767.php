<!-- resources/views/companies/edit.blade.php -->
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
        <?php echo e(__('EDIT COMPANY - ') . $company->name); ?>

     <?php $__env->endSlot(); ?>
    <?php if($userPermissions->contains('can manage companies')): ?>
        <form method="POST" action="<?php echo e(route('companies.update', $company->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label">Company Name</label>
                            <input value="<?php echo e($company->name); ?>" type="text" name="name" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Website</label>
                            <input value="<?php echo e($company->website); ?>" type="text" name="website" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Country</label>
                            <select required id="country" name="country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="UAE" <?php echo e($company->country == 'UAE' ? 'selected' : ''); ?>>UAE</option>
                                <option value="SAUDI ARABIA"
                                    <?php echo e($company->country == 'SAUDI ARABIA' ? 'selected' : ''); ?>>SAUDI ARABIA</option>
                                <option value="USA" <?php echo e($company->country == 'USA' ? 'selected' : ''); ?>>USA</option>
                                <option value="OTHER" <?php echo e($company->country == 'OTHER' ? 'selected' : ''); ?>>OTHER
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6 city-wrapper">
                            <label class="form-label">Company City</label>
                            <select required id="city" name="city" class="form-control">
                                <option value="<?php echo e($company->city); ?>" selected><?php echo e($company->city); ?></option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="logo">
                                <img src="<?php echo e(asset('/public/storage/')); ?>/<?php echo e($company->logo); ?>" class="img-fluid"
                                    alt="Logo" width="50"> <?php echo e($company->name); ?>

                            </label>
                            <input type="file" name="logo" id="logo" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary-600 radius-8">Update Company</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                let cities = {
                    "UAE": ["Dubai", "Abu Dhabi", "Sharjah", "Other"],
                    "SAUDI ARABIA": ["Riyadh", "Jeddah", "Dammam", "Other"],
                    "USA": ["New York", "Los Angeles", "Chicago", "Other"],
                    "OTHER": ["Other"]
                };

                function updateCities() {
                    let country = $("#country").val();
                    let cityDropdown = $("#city");
                    cityDropdown.empty().append('<option value="">Select City</option>');

                    if (country && cities[country]) {
                        $.each(cities[country], function(index, city) {
                            let selected = (city.toLowerCase() === "<?php echo e(strtolower($company->city)); ?>") ?
                                "selected" : "";
                            cityDropdown.append('<option value="' + city + '" ' + selected + '>' + city +
                                '</option>');
                        });
                    }
                }
                $("#country").change(updateCities);
                updateCities();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/companies/edit.blade.php ENDPATH**/ ?>