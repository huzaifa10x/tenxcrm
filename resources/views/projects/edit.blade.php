<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Project') }}
    </x-slot>

    @if ($userPermissions->contains('can edit projects'))
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('projects.update', $project->id) }}" method="POST"
                    id="edit-project-form">
                    @csrf
                    @method('PUT')
                    <div class="row gy-3">

                        <div class="col-lg-12">
                            <label class="form-label" for="title">Project Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $project->title }}">
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="description">Project Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ $project->description }}</textarea>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-4">
                                <label class="form-label" for="sales_manager_ids">Sales Managers</label>
                                <select class="form-control w-100" name="sales_manager_ids[]" id="sales_manager_ids"
                                    multiple>
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('SALES MANAGER')) as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, $project->salesManagers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="account_manager_ids">Account Managers</label>
                                <select class="form-control w-100" name="account_manager_ids[]" id="account_manager_ids"
                                    multiple>
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('ACCOUNT MANAGER')) as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, $project->accountManagers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label" for="tech_manager_ids">Tech Managers</label>
                                <select class="form-control w-100" name="tech_manager_ids[]" id="tech_manager_ids"
                                    multiple>
                                    @foreach ($users->filter(fn($user) => $user->roles->pluck('name')->contains('TECH MANAGER')) as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, $project->techManagers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option selected disabled value="">Select Category</option>
                                <option value="PLATINUM" {{ $project->category == 'PLATINUM' ? 'selected' : '' }}>
                                    PLATINUM
                                </option>
                                <option value="GOLD" {{ $project->category == 'GOLD' ? 'selected' : '' }}>GOLD
                                </option>
                                <option value="SILVER" {{ $project->category == 'SILVER' ? 'selected' : '' }}>SILVER
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option selected disabled value="">Select status</option>
                                <option value="Current" {{ $project->status == 'Current' ? 'selected' : '' }}>Current
                                </option>
                                <option value="Paused" {{ $project->status == 'Paused' ? 'selected' : '' }}>Paused
                                </option>
                                <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>WFF</option>
                                <option value="Expired" {{ $project->status == 'Expired' ? 'selected' : '' }}>Expired
                                </option>
                                <option value="Debt Collection"
                                    {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt Collection
                                </option>
                                <option value="Cancelled" {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="industry">Industry</label>
                            <select class="form-control" id="industry_id" name="industry_id" required>
                                <option selected disabled value="">Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}"
                                        {{ $project->industry_id == $industry->id ? 'selected' : '' }}>
                                        {{ $industry->industry }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method_id">Payment Method</label>
                            <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                                <option selected disabled value="">Select Payment Method</option>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}"
                                        {{ $project->payment_method_id == $paymentMethod->id ? 'selected' : '' }}>
                                        {{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_start_date">Contract Start Date</label>
                            <input type="date" class="form-control" id="contract_start_date"
                                name="contract_start_date" value="{{ $project->contract_start_date }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">Contract Value</label>
                            <input type="number" class="form-control" id="contract_value" name="contract_value"
                                value="{{ $project->contract_value }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">Contract Term</label>
                            <input type="number" class="form-control" id="contract_term" name="contract_term"
                                value="{{ $project->contract_term }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="free_months">Free Months</label>
                            <input type="number" class="form-control" id="free_months" name="free_months"
                                value="{{ $project->free_months }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_copy">Contract Copy</label>
                            <input type="file" name="contract_copy[]" multiple class="form-control">
                            <hr>
                        </div>
                        <div class="col-lg-12">
                            @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
                                <label class="form-label" for="contract_copy">Current Contract Files</label>
                                <div class="row">
                                    @foreach ($project->contractFiles as $file)
                                        @php
                                            $filePath = asset('storage/' . $file->file_path);
                                            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                        @endphp
                                        @if (in_array($extension, $imageExtensions))
                                            <div class="col-lg-6">
                                                <div
                                                    class="flex-column h-100 position-relative card overflow-hidden
                                                 d-flex justify-content-center align-items-center bg-light border 
                                                 shadow-sm text-center p-3">
                                                    <button type="button"
                                                        onclick="deleteProjectFile({{ $project->id }}, {{ $file->id }})"
                                                        class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                    <div
                                                        class="badge position-absolute top-0 start-0 bg-primary text-white">
                                                        {{ $extension }}
                                                    </div>
                                                    <img style="width: 100px;" src="{{ $filePath }}"
                                                        class="card-img-top img-fluid" alt="Contract Image">
                                                    <p class="mb-1 mt-3 text-dark">{{ $file->file_name }}</p>
                                                    <div class="card-body text-center">
                                                        <a href="{{ $filePath }}" target="_blank"
                                                            class="btn fs-6 px-3 py-2 btn-sm btn-primary">View Full</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-6">
                                                <div
                                                    class="flex-column h-100 position-relative card overflow-hidden d-flex justify-content-center align-items-center bg-light border  shadow-sm text-center p-3">
                                                    <button type="button"
                                                        onclick="deleteProjectFile({{ $project->id }}, {{ $file->id }})"
                                                        class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                    <div
                                                        class="badge position-absolute top-0 start-0 bg-primary text-white">
                                                        {{ $extension }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <img src="{{ asset('/images/doc.png') }}" width="50"
                                                            height="50" alt="{{ $extension }} File">
                                                    </div>
                                                    <p class="mb-1 text-dark">{{ $file->file_name }}</p>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <a href="{{ $filePath }}" target="_blank"
                                                            class="btn fs-6 px-3 py-2 btn-sm btn-primary">View</a>
                                                        <a href="{{ $filePath }}" download
                                                            class="btn fs-6 px-3 py-2 btn-sm btn-success">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-danger">No contract files attached.</p>
                            @endif
                            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
                                    <button class="btn btn-sm btn-danger mt-3" type="button"
                                        id="deleteAllAttachmentsBtn">
                                        Delete All Attachments
                                    </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                                    data-bs-target="#projectAttachmentsModal">
                                    Add
                                    {{ !empty($project->contractFiles) && count($project->contractFiles) ? 'More' : '' }}
                                    Project Files
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="handover_notes">Handover Notes</label>
                            <textarea required class="form-control" name="handover_notes" id="handover_notes" cols="30" rows="10">{{ $project->handover_notes }}</textarea>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="project_type">Project Type</label>
                            <select class="form-control" id="project_type" name="project_type" required>
                                <option selected disabled value="">Select Project Type</option>
                                <option value="SEO" {{ $project->project_type == 'SEO' ? 'selected' : '' }}>SEO
                                </option>
                                <option value="Google Ads"
                                    {{ $project->project_type == 'Google Ads' ? 'selected' : '' }}>
                                    Google Ads</option>
                                <option value="Social Media"
                                    {{ $project->project_type == 'Social Media' ? 'selected' : '' }}>Social Media
                                </option>
                                <option value="Web Development"
                                    {{ $project->project_type == 'Web Development' ? 'selected' : '' }}>Web Development
                                </option>
                                <option value="Hosting" {{ $project->project_type == 'Hosting' ? 'selected' : '' }}>
                                    Hosting</option>
                                <option value="Other" {{ $project->project_type == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>
                        <div class="row" id="extra-fields-container">
                            {{-- CONDITIONAL Fields --}}
                            @if ($project->project_type == 'SEO')
                                <div class="col-lg-6">
                                    <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                                    <input required class="form-control" type="number" id="number_of_keywords"
                                        name="number_of_keywords"
                                        value="{{ $project->seoProject->number_of_keywords ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_1_percentage">Guarantee for 1st Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_1_percentage"
                                        name="milestone_1_percentage"
                                        value="{{ $project->seoProject->milestone_1_percentage ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_1_date">Guarantee for 1st Milestone
                                        Date</label>
                                    <input required class="form-control" type="date" id="milestone_1_date"
                                        name="milestone_1_date"
                                        value="{{ $project->seoProject->milestone_1_date ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_2_percentage"
                                        name="milestone_2_percentage"
                                        value="{{ $project->seoProject->milestone_2_percentage ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone
                                        Date</label>
                                    <input required class="form-control" type="date" id="milestone_2_date"
                                        name="milestone_2_date"
                                        value="{{ $project->seoProject->milestone_2_date ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone
                                        %</label>
                                    <input required class="form-control" type="text" id="milestone_3_percentage"
                                        name="milestone_3_percentage"
                                        value="{{ $project->seoProject->milestone_3_percentage ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone
                                        Date</label>
                                    <input required class="form-control" type="date" id="milestone_3_date"
                                        name="milestone_3_date"
                                        value="{{ $project->seoProject->milestone_3_date ?? '' }}">
                                </div>
                            @elseif ($project->project_type == 'Social Media')
                                <div class="col-lg-6">
                                    <label class="form-label" for="channels">Channels</label>
                                    <select class="form-control" id="channels" name="channels" required>
                                        <option value="Facebook"
                                            {{ $project->socialMediaProject->channels == 'Facebook' ? 'selected' : '' }}>
                                            Facebook</option>
                                        <option value="Instagram"
                                            {{ $project->socialMediaProject->channels == 'Instagram' ? 'selected' : '' }}>
                                            Instagram</option>
                                        <option value="LinkedIn"
                                            {{ $project->socialMediaProject->channels == 'LinkedIn' ? 'selected' : '' }}>
                                            LinkedIn</option>
                                        <option value="Twitter"
                                            {{ $project->socialMediaProject->channels == 'Twitter' ? 'selected' : '' }}>
                                            Twitter</option>
                                        <option value="YouTube"
                                            {{ $project->socialMediaProject->channels == 'YouTube' ? 'selected' : '' }}>
                                            YouTube</option>
                                        <option value="TikTok"
                                            {{ $project->socialMediaProject->channels == 'TikTok' ? 'selected' : '' }}>
                                            TikTok</option>
                                        <option value="Snapchat"
                                            {{ $project->socialMediaProject->channels == 'Snapchat' ? 'selected' : '' }}>
                                            Snapchat</option>
                                        <option value="Pinterest"
                                            {{ $project->socialMediaProject->channels == 'Pinterest' ? 'selected' : '' }}>
                                            Pinterest</option>
                                        <option value="Google My Business"
                                            {{ $project->socialMediaProject->channels == 'Google My Business' ? 'selected' : '' }}>
                                            Google My Business</option>
                                        <option value="Other"
                                            {{ $project->socialMediaProject->channels == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="video_shoots_per_month">Video shoots Per
                                        Month</label>
                                    <input class="form-control" type="number" id="video_shoots_per_month"
                                        name="video_shoots_per_month"
                                        value="{{ $project->socialMediaProject->video_shoots_per_month ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="static_posts">Static Posts Per Month</label>
                                    <input class="form-control" type="number" id="static_posts" name="static_posts"
                                        value="{{ $project->socialMediaProject->static_posts ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="reels">Reels Per Month</label>
                                    <input class="form-control" type="text" id="reels" name="reels"
                                        value="{{ $project->socialMediaProject->reels ?? '' }}">
                                </div>
                            @elseif ($project->project_type == 'Web Development')
                                <div class="col-lg-6">
                                    <label class="form-label" for="payment_type">Payment Type</label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value="Partial"
                                            {{ $project->webDevProject->payment_type == 'Partial' ? 'selected' : '' }}>
                                            Partial</option>
                                        <option value="Full"
                                            {{ $project->webDevProject->payment_type == 'Full' ? 'selected' : '' }}>
                                            Full
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="remaining_payment">Remaining Payment</label>
                                    <input class="form-control" type="number" id="remaining_payment"
                                        name="remaining_payment"
                                        value="{{ $project->webDevProject->remaining_payment ?? '' }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label" for="site_condition">Site Condition</label>
                                    <select class="form-control" id="site_condition" name="site_condition" required>
                                        <option value="New Project"
                                            {{ $project->webDevProject->site_condition == 'New Project' ? 'selected' : '' }}>
                                            New Project</option>
                                        <option value="Maintenance Project"
                                            {{ $project->webDevProject->site_condition == 'Maintenance Project' ? 'selected' : '' }}>
                                            Maintenance Project</option>
                                    </select>
                                </div>
                            @else
                            @endif
                        </div>

                        <div class="col-lg-6 mt-3">
                            <button class="btn btn-primary" type="submit">Save Project</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="projectAttachmentsModal" tabindex="-1"
            aria-labelledby="projectAttachmentsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="projectAttachmentsModalLabel">Choose Project
                            Attachments
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="file" id="fileInput" multiple>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="uploadProjectFiles({{ $project->id }})"
                            class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                $('#project_type').change(function() {
                    var projectType = $(this).val();
                    if (projectType) {
                        $.ajax({
                            url: '{{ route('projects-get-extra-fields') }}',
                            method: 'GET',
                            data: {
                                project_type: projectType
                            },
                            success: function(response) {
                                $('#extra-fields-container').html(
                                    response);
                            }
                        });
                    } else {
                        $('#extra-fields-container').html('');
                    }
                });

                $('#sales_manager_ids').select2({
                    placeholder: 'Select Sales Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: false
                });
                $('#account_manager_ids').select2({
                    placeholder: 'Select Account Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: false
                });
                $('#tech_manager_ids').select2({
                    placeholder: 'Select Tech Managers',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: false
                });
            });
        </script>
        <script>
            function deleteProjectFile(projectId, fileId) {
                if (confirm("Are you sure you want to delete this file?")) {
                    fetch(`{{ route('projects.deleteFile', ['project' => '__PROJECT_ID__', 'file' => '__FILE_ID__']) }}`
                            .replace('__PROJECT_ID__', projectId).replace('__FILE_ID__', fileId), {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                    "Content-Type": "application/json",
                                }
                            })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("File deleted successfully!");
                                location.reload();
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            }

            function uploadProjectFiles(projectId) {
                let fileInput = document.getElementById("fileInput");
                if (fileInput.files.length === 0) {
                    alert("Please select at least one file to upload.");
                    return;
                }

                let formData = new FormData();
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append("contract_copy[]", fileInput.files[i]);
                }
                formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

                fetch(`{{ route('projects.uploadFiles', ['project' => '__PROJECT_ID__']) }}`.replace('__PROJECT_ID__',
                        projectId), {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Files uploaded successfully!");
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

            document.getElementById('deleteAllAttachmentsBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to delete all attachments?')) {
                    // Send DELETE request using AJAX
                    fetch("{{ route('projects.deleteAllFiles', ['project' => $project->id]) }}", {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('All attachments deleted successfully!');
                                location.reload(); // Refresh the page to reflect the changes
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('There was an error processing your request.');
                        });
                }
            });
        </script>
    @else
        @include('vendor.not-allowed')
    @endif

</x-app-layout>
