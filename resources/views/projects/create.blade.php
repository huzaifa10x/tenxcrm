<x-app-layout>
    <x-slot name="header">
        {{ __('Create Project') }}
    </x-slot>
    @if ($userPermissions->contains('can add projects'))

        <form enctype="multipart/form-data" action="{{ route('projects.store') }}" method="POST" id="create-project-form">
            @csrf
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">
                    <div class="col-lg-3">
                        <label class="form-label" for="company_id">Company</label>
                        <select class="form-control w-100" required name="company_id" id="company_id">
                            @foreach ($companies as $company)
                                <option data-domain="{{ $company->website }}" value="{{ $company->id }}">
                                    {{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="project_type">Service Type</label>
                        <select class="form-control" id="project_type" name="project_type" required>
                            <option selected disabled value="">Select Project Type</option>
                            <option value="SEO">SEO</option>
                            <option value="Google Ads">Google Ads</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Hosting">Hosting</option>
                            <option value="Hosting">Other</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option selected disabled value="">Select Category</option>
                            <option value="PLATINUM">PLATINUM</option>
                            <option value="GOLD">GOLD</option>
                            <option value="SILVER">SILVER</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="industry">Industry</label>
                        <select class="form-control" id="industry_id" name="industry_id" required>
                            <option selected disabled value="">Select Industry</option>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->industry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">

                    <input type="hidden" value="Current" name="status">
                    <input type="text" name="title" id="title" class="form-control d-none" required>

                    <div class="col-lg-3">
                        <label class="form-label" for="contract_value">Total Contract Value <span class="fs-7">(w/o
                                VAT - in AED)</span></label>
                        <input type="number" class="form-control" id="contract_value" name="contract_value" required>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label" for="contract_term">contract term <span class="fs-7">(in
                                months)</span></label>
                        <input type="number" class="form-control" id="contract_term" name="contract_term" required>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label" for="free_months">free months</label>
                        <input type="number" value="0" class="form-control" id="free_months" name="free_months"
                            required>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label" for="payment_method_id">Payment Method</label>
                        <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                            <option selected disabled value="">Select Payment Method</option>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="payment_method_id">Contract Start Date</label>
                        <input type="date" class="form-control date-picker" id="contract_start_date"
                            name="contract_start_date" value="" required>
                    </div>
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">
                    {{-- <div class="col-lg-12">
                        <label class="form-label" for="contract_copy">Contract Copy</label>
                        <input type="file" name="contract_copy[]" multiple class="form-control">
                    </div> --}}
                    <div class="col-lg-12">
                        <label class="form-label" for="contract_copy">Contract Copy</label>
                        <div id="contractDropzone" class="dropzone"></div>
                    </div>
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">
                    <div class="col-lg-4">
                        <label class="form-label" for="sales_manager_ids">Sales Managers</label>
                        <select class="form-control w-100" required name="sales_manager_ids[]"
                            id="sales_manager_ids">
                            @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SALES MANAGER')) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="account_manager_ids">Account Managers</label>
                        <select class="form-control w-100" required name="account_manager_ids[]"
                            id="account_manager_ids">
                            @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('AM')) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="tech_manager_ids">Tech Managers</label>
                        <select required class="form-control w-100" name="tech_manager_ids[]" id="tech_manager_ids">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="seo_admin_ids">Seo Admin</label>
                        <select class="form-control w-100" required name="seo_admin_ids[]" id="seo_admin_ids">
                            @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SEO ADMIN')) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label" for="content_writers_ids">Content Writer</label>
                        <select class="form-control w-100" required name="content_writers_ids[]"
                            id="content_writers_ids">
                            @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('CONTENT')) as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body" id="extra-fields-container">
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">
                    <input type="hidden" value="{{ auth()->user()->id }}" name="created_by">
                    <div class="col-lg-6">
                        <label class="form-label fs-6" for="project_desc">(DESCRIPTION FOR BACK OFFICE – NO SENSITIVE
                            INFORMATION – DO NOT DUPLICATE)</label>
                        <span class="d-block fs-7 text-bg-warning text-secondary-dark p-1 rounded mb-3">
                            (Use this section to give the Back Office team a clear project overview. Include project
                            scope, general timeline, deliverables, or any special instructions. Avoid sensitive data or
                            repeating information from the AM Notes.)
                        </span>
                        <textarea class="form-control" name="description" id="project_desc" cols="30" rows="10">Give the Back Office team a clear project overview. Include project scope, 
general timeline, deliverables, or any special instructions. 
Avoid sensitive data or repeating information from the AM Notes.</textarea>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fs-6" for="handover_notes">(HANDOVER NOTES FOR AM)</label>
                        <span class="d-block fs-7 text-bg-warning text-secondary-dark p-1 rounded mb-3">
                            (Use this section to provide the Account Manager with key client-related info such as
                            payment
                            terms, cheque details, contract start/end dates, and primary contact info. Please ensure
                            accuracy and relevance.)
                        </span>
                        <textarea required class="form-control" name="handover_notes" id="handover_notes" cols="30" rows="10">Provide the Account Manager with key client-related info such as payment terms, 
cheque details, contract start/end dates, and primary contact info.
Please ensure accuracy and relevance.</textarea>
                    </div>
                </div>
            </div>
            <div class="card mb-10 rounded border">
                <div class="row gy-3 card-body">
                    <div class="col-lg-6 mt-3">
                        <button class="btn btn-primary submit" type="button">Save Project</button>
                    </div>
                </div>
            </div>
        </form>

        <script type="text/javascript">
            var uploadedDocumentMap = {};
            Dropzone.autoDiscover = false;

            var myDropzone = new Dropzone("#contractDropzone", {
                url: "{{ route('projects.store') }}", // Laravel route for form submission
                autoProcessQueue: false,
                uploadMultiple: true,
                paramName: "contract_copy", // Ensure Laravel handles it as an array
                maxFiles: 10,
                maxFilesize: 5, // 5MB per file
                acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp",
                addRemoveLinks: true,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                init: function() {
                    var dropzoneInstance = this;
                    dropzoneInstance.on("sending", function(file, xhr, formData) {
                        var form = document.querySelector("#create-project-form");
                        var formDataEntries = new FormData(form);
                        formDataEntries.forEach((value, key) => {
                            formData.append(key, value);
                        });
                        formData.append("contract_copy[]", file);
                    });
                    dropzoneInstance.on("success", function(file, response) {
                        uploadedDocumentMap[file.name] = response.name;
                    });
                    dropzoneInstance.on("removedfile", function(file) {
                        if (uploadedDocumentMap[file.name]) {
                            delete uploadedDocumentMap[file.name];
                        }
                    });
                }
            });
            $('.submit').click(function(e) {
                e.preventDefault();
                var formData = new FormData($("#create-project-form")[0]);
                myDropzone.files.forEach(function(file) {
                    formData.append("contract_copy[]", file);
                });
                $.ajax({
                    url: "{{ route('projects.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        window.location.href = "{{ route('projects.index') }}"
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert("Error in submission!");
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#company_id, #project_type').on('change', function() {
                    var fullDomain = $('#company_id option:selected').data('domain');
                    var projectType = $('#project_type').val();

                    if (fullDomain && projectType) {
                        var domainName = fullDomain.replace(/(https?:\/\/)?(www\.)?/i,
                            ''); // Remove https:// and www.
                        $('#title').val(domainName + ' - ' + projectType);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#project_type').change(function() {
                    var projectType = $(this).val(); // Get selected project type
                    if (projectType) {
                        $.ajax({
                            url: '{{ route('projects-get-extra-fields') }}', // Correctly use route name
                            method: 'GET',
                            data: {
                                project_type: projectType
                            },
                            success: function(response) {
                                $('#extra-fields-container').html(response);
                                $('#channels').select2({
                                    placeholder: 'Select Channels',
                                    allowClear: true,
                                    width: '100%',
                                    closeOnSelect: true
                                });
                            }
                        });
                    } else {
                        $('#extra-fields-container').html('');
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#sales_manager_ids').select2({
                    placeholder: 'Select Sales Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#account_manager_ids').select2({
                    placeholder: 'Select Account Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#tech_manager_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#seo_tech_ids').select2({
                    placeholder: 'Select Seo Tech',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#seo_admin_ids').select2({
                    placeholder: 'Select Seo Admin',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
                $('#content_writers_ids').select2({
                    placeholder: 'Select content Writers',
                    allowClear: true,
                    width: '100%', // Full width
                    closeOnSelect: true // Prevent closing on select
                });
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
