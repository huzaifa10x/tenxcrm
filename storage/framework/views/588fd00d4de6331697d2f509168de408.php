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
        <?php echo e(__('Create Company')); ?>

     <?php $__env->endSlot(); ?>
    <?php if($userPermissions->contains('can manage companies')): ?>
        <form id="companyForm" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="card mb-10">
                <div class="card-header">
                    <span class="fw-bold">Company Details</span>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control">
                            <span class="text-danger error-name"></span>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Website</label>
                            <input type="text" name="website" class="form-control">
                            <span class="text-danger error-website"></span>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Country</label>
                            <select name="country" id="country" class="form-control">
                                <option value="">Select Country</option>
                                <option value="UAE">UAE</option>
                                <option value="SAUDI ARABIA">SAUDI ARABIA</option>
                                <option value="USA">USA</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                            <span class="text-danger error-country"></span>
                        </div>
                        <div class="col-lg-6 city-wrapper" style="display: none">
                            <label class="form-label">Company City</label>
                            <select name="city" id="city" class="form-control">
                                <option value="">Select City</option>
                            </select>
                            <span class="text-danger error-city"></span>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-control">
                            <span class="text-danger error-logo"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-10">
                <div class="card-header">
                    <span class="fw-bold">Contact Person Details</span>
                </div>
                <div class="card-body">
                    <div id="companyDetailsContainer">
                        <div class="row gy-3 company-detail">
                            <div class="col-lg-6">
                                <label class="form-label">Name</label>
                                <input name="detail_name[]" type="text" class="form-control">
                                <span class="text-danger error-detail_name"></span>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Email</label>
                                <input name="detail_email[]" type="text" class="form-control">
                                <span class="text-danger error-detail_email"></span>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Phone Number</label>
                                <input name="detail_phone[]" type="text" class="form-control">
                                <span class="text-danger error-detail_phone"></span>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">City</label>
                                <input name="detail_city[]" type="text" class="form-control">
                                <span class="text-danger error-detail_city"></span>
                            </div>
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-danger remove-detail mt-3">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <button type="button" id="addMoreDetails" class="btn btn-success btn-sm">Add More</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </div>
        </form>

        <div id="successMessage" class="alert alert-success mt-3 d-none"></div>

        <script>
            $(document).ready(function() {
                let cities = {
                    "UAE": [
                        "Abu Dhabi",
                        "Dubai",
                        "Sharjah",
                        "Ajman",
                        "Umm Al Quwain",
                        "Ras Al Khaimah",
                        "Fujairah",
                    ],
                    "SAUDI ARABIA": ["Other"],
                    "USA": ["Other"],
                    "OTHER": ["Other"]
                };
                $(document).on('change', '#country', function() {
                    let country = $(this).val();
                    let cityDropdown = $("#city");
                    $('.city-wrapper').slideDown();
                    cityDropdown.empty().append('<option value="">Select City</option>');

                    if (country && cities[country]) {
                        $.each(cities[country], function(index, city) {
                            cityDropdown.append('<option value="' + city.toLowerCase() + '">' + city +
                                '</option>');
                        });
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                // Handle Adding More Contact Person Details
                $("#addMoreDetails").click(function() {
                    var newDetail = $(".company-detail:first").clone();
                    newDetail.find("input").val(""); // Clear input fields
                    $("#companyDetailsContainer").append(newDetail);
                });

                // Handle Removing Contact Person Details
                $(document).on("click", ".remove-detail", function() {
                    if ($(".company-detail").length > 1) {
                        $(this).closest(".company-detail").remove();
                    } else {
                        alert("At least one contact person is required!");
                    }
                });

                // AJAX Form Submission
                $("#companyForm").submit(function(e) {
                    e.preventDefault(); // Prevent default form submission

                    let formData = new FormData(this); // Get form data
                    let submitButton = $(this).find("button[type=submit]");
                    submitButton.prop("disabled", true).text("Saving...");

                    $.ajax({
                        url: "<?php echo e(route('companies.store')); ?>", // Laravel route
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            toastr.success(response.message)
                            $("#successMessage").removeClass("d-none").text(response.message);
                            $("#errorMessages").addClass("d-none").text("");
                            submitButton.prop("disabled", false).text("Save");
                            $("#companyForm")[0].reset(); // Reset form
                            setTimeout(() => {
                                window.location.href = "<?php echo e(route('companies.index')); ?>"
                            }, 2000);
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                            $(".text-danger").text(""); // Clear previous errors

                            $.each(errors, function(key, value) {
                                let formattedKey = key.replace(".",
                                    "_"); // Handle array input names
                                $(".error-" + formattedKey).text(value[
                                    0]); // Show error under respective field

                                // Show toastr error message
                                toastr.error(value[0]);
                            });

                            $("#successMessage").addClass("d-none").text("");
                            submitButton.prop("disabled", false).text("Save");
                        }
                    });
                });
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/companies/create.blade.php ENDPATH**/ ?>