<x-app-layout>
    <x-slot name="header">
        {{ __('Project - ' . $project->title) }}
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="row gy-3">

                <div class="col-lg-12">
                    <label class="form-label" for="title">Project Title</label>
                    <input readonly type="text" name="title" class="form-control" value="{{ $project->title }}">
                </div>

                <div class="col-lg-12">
                    <label class="form-label" for="description">Project Description</label>
                    <textarea readonly class="form-control" name="description" id="description" cols="30" rows="10">{{ $project->description }}</textarea>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-4">
                        <label class="form-label" for="sales_manager_ids">Sales Managers</label>
                        <ul>
                            @foreach ($project->salesManagers as $salesManager)
                                <li class="badge bg-dark px-3 py-2 mb-2">{{ $salesManager->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="account_manager_ids">Account Managers</label>
                        <ul>
                            @foreach ($project->accountManagers as $accountManager)
                                <li class="badge bg-dark px-3 py-2 mb-2">{{ $accountManager->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label" for="tech_manager_ids">Tech Managers</label>
                        <ul>
                            @foreach ($project->techManagers as $techManager)
                                <li class="badge bg-dark px-3 py-2 mb-2">{{ $techManager->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" class="form-control" readonly value="{{ $project->category }}">
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="status">Status</label>
                    <input type="text" class="form-control" readonly value="{{ $project->status }}">
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="industry">Industry</label>
                    <input type="text" class="form-control" readonly value="{{ $project->industry->industry }}">
                    </select>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="payment_method_id">Payment Method</label>
                    <input type="text" class="form-control" readonly value="{{ $project->paymentMethod->name }}">
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_start_date">Contract Start Date</label>
                    <input readonly type="date" class="form-control" id="contract_start_date"
                        name="contract_start_date" value="{{ $project->contract_start_date }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_value">Contract Value</label>
                    <input readonly type="text" class="form-control" id="contract_value" name="contract_value"
                        value="{{ $project->contract_value }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_term">Contract Term</label>
                    <input readonly type="text" class="form-control" id="contract_term" name="contract_term"
                        value="{{ $project->contract_term }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="free_months">Free Months</label>
                    <input readonly type="text" class="form-control" id="free_months" name="free_months"
                        value="{{ $project->free_months }}" required>
                </div>

                <div class="col-lg-12">
                    @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
                        <label class="form-label" for="contract_copy">Contract Files</label>
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
                                            <div class="badge position-absolute top-0 start-0 bg-primary text-white">
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
                                            <div class="badge position-absolute top-0 start-0 bg-primary text-white">
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
                            <button class="btn btn-sm btn-danger mt-3" type="button" id="deleteAllAttachmentsBtn">
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
                    <textarea readonly required class="form-control" name="handover_notes" id="handover_notes" cols="30"
                        rows="10">{{ $project->handover_notes }}</textarea>
                </div>

                <div class="col-lg-12">
                    <label class="form-label" for="project_type">Project Type</label>
                    <input type="text" class="form-control" readonly value="{{ $project->project_type }}">
                </div>
                <div class="row" id="extra-fields-container">
                    {{-- CONDITIONAL Fields --}}
                    @if ($project->project_type == 'SEO')
                        <div class="col-lg-6">
                            <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                            <input readonly required class="form-control" type="text" id="number_of_keywords"
                                name="number_of_keywords"
                                value="{{ $project->seoProject->number_of_keywords ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_1_percentage">Guarantee for 1st Milestone
                                %</label>
                            <input readonly required class="form-control" type="text" id="milestone_1_percentage"
                                name="milestone_1_percentage"
                                value="{{ $project->seoProject->milestone_1_percentage ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_1_date">Guarantee for 1st Milestone
                                Date</label>
                            <input readonly required class="form-control" type="date" id="milestone_1_date"
                                name="milestone_1_date" value="{{ $project->seoProject->milestone_1_date ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone
                                %</label>
                            <input readonly required class="form-control" type="text" id="milestone_2_percentage"
                                name="milestone_2_percentage"
                                value="{{ $project->seoProject->milestone_2_percentage ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone
                                Date</label>
                            <input readonly required class="form-control" type="date" id="milestone_2_date"
                                name="milestone_2_date" value="{{ $project->seoProject->milestone_2_date ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone
                                %</label>
                            <input readonly required class="form-control" type="text" id="milestone_3_percentage"
                                name="milestone_3_percentage"
                                value="{{ $project->seoProject->milestone_3_percentage ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone
                                Date</label>
                            <input readonly required class="form-control" type="date" id="milestone_3_date"
                                name="milestone_3_date" value="{{ $project->seoProject->milestone_3_date ?? '' }}">
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
                                    {{ $project->socialMediaProject->channels == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="video_shoots_per_month">Video shoots Per Month</label>
                            <input readonly class="form-control" type="text" id="video_shoots_per_month"
                                name="video_shoots_per_month"
                                value="{{ $project->socialMediaProject->video_shoots_per_month ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="static_posts">Static Posts Per Month</label>
                            <input readonly class="form-control" type="text" id="static_posts"
                                name="static_posts" value="{{ $project->socialMediaProject->static_posts ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="reels">Reels Per Month</label>
                            <input readonly class="form-control" type="text" id="reels" name="reels"
                                value="{{ $project->socialMediaProject->reels ?? '' }}">
                        </div>
                    @elseif ($project->project_type == 'Web Development')
                        <div class="col-lg-6">
                            <label class="form-label" for="payment_type">Payment Type</label>
                            <input readonly class="form-control" type="text" id="remaining_payment"
                                name="remaining_payment" value="{{ $project->webDevProject->payment_type ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="remaining_payment">Remaining Payment</label>
                            <input readonly class="form-control" type="text" id="remaining_payment"
                                name="remaining_payment"
                                value="{{ $project->webDevProject->remaining_payment ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="site_condition">Site Condition</label>
                            <input readonly class="form-control" type="text" id="remaining_payment"
                                name="remaining_payment" value="{{ $project->webDevProject->site_condition ?? '' }}">
                        </div>
                    @else
                    @endif
                </div>
            </div>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input readonly class="form-control" type="file" id="fileInput" multiple>
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

</x-app-layout>
