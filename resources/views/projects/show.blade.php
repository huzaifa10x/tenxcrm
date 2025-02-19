<x-app-layout>
    <x-slot name="header">
        {{ __('Project - ' . $project->title) }}
    </x-slot>
    @if ($userPermissions->contains('can view all projects') || $userPermissions->contains('can view own projects'))
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @if ($userPermissions->contains('can create tasks'))
                    <button data-bs-toggle="modal" data-bs-target="#taskCreateModal" class="btn btn-outline-primary">
                        Create Task
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="row gy-3">

                    <div class="col-lg-12">
                        <label class="form-label" for="title">Project Title</label>
                        <input readonly type="text" name="title" class="form-control"
                            value="{{ $project->title }}">
                    </div>

                    <div class="col-lg-12">
                        <label class="form-label" for="description">Project Description</label>
                        <textarea readonly class="form-control" name="description" id="description" cols="30" rows="10">{{ $project->description }}</textarea>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label class="form-label" for="account_manager_ids">Company</label>
                            <ul>
                                <li class="badge bg-dark px-3 py-2 mb-2">{{ $project->companies->name }}</li>
                            </ul>
                        </div>
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
                        @if ($userPermissions->contains('can update project status'))
                            <label class="form-label">Project Status</label>
                            <select class="form-control project-status" data-project-id="{{ $project->id }}" required>
                                <option selected disabled value="">Select status</option>
                                <option value="Current" {{ $project->status == 'Current' ? 'selected' : '' }}>
                                    Current
                                </option>
                                <option value="Paused" {{ $project->status == 'Paused' ? 'selected' : '' }}>
                                    Paused
                                </option>
                                <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>WFF
                                </option>
                                <option value="Expired" {{ $project->status == 'Expired' ? 'selected' : '' }}>
                                    Expired
                                </option>
                                <option value="Debt Collection"
                                    {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt
                                    Collection
                                </option>
                                <option value="Cancelled" {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                                <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        @else
                            <label class="form-label">Project Status</label>
                            <input type="text" readonly class="form-control" value="{{ $project->status }}">
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="industry">Industry</label>
                        <input type="text" class="form-control" readonly value="{{ $project->industry->industry }}">
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="payment_method_id">Payment Method</label>
                        <input type="text" class="form-control" readonly
                            value="{{ $project->paymentMethod->name }}">
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
                                                @if ($userPermissions->contains('can remove project files'))
                                                    <button type="button"
                                                        onclick="deleteProjectFile({{ $project->id }}, {{ $file->id }})"
                                                        class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                @endif
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
                            @if ($userPermissions->contains('can remove project files'))
                                @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
                                    <button class="btn btn-sm btn-danger mt-3" type="button"
                                        id="deleteAllAttachmentsBtn">
                                        Delete All Attachments
                                    </button>
                                @endif
                            @endif
                            @if ($userPermissions->contains('can add project files'))
                                <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                                    data-bs-target="#projectAttachmentsModal">
                                    Add
                                    {{ !empty($project->contractFiles) && count($project->contractFiles) ? 'More' : '' }}
                                    Project Files
                                </button>
                            @endif
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
                                <input readonly required class="form-control" type="text"
                                    id="milestone_1_percentage" name="milestone_1_percentage"
                                    value="{{ $project->seoProject->milestone_1_percentage ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="milestone_1_date">Guarantee for 1st Milestone
                                    Date</label>
                                <input readonly required class="form-control" type="date" id="milestone_1_date"
                                    name="milestone_1_date"
                                    value="{{ $project->seoProject->milestone_1_date ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="milestone_2_percentage">Guarantee for 2nd Milestone
                                    %</label>
                                <input readonly required class="form-control" type="text"
                                    id="milestone_2_percentage" name="milestone_2_percentage"
                                    value="{{ $project->seoProject->milestone_2_percentage ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="milestone_2_date">Guarantee for 2nd Milestone
                                    Date</label>
                                <input readonly required class="form-control" type="date" id="milestone_2_date"
                                    name="milestone_2_date"
                                    value="{{ $project->seoProject->milestone_2_date ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="milestone_3_percentage">Guarantee for 3rd Milestone
                                    %</label>
                                <input readonly required class="form-control" type="text"
                                    id="milestone_3_percentage" name="milestone_3_percentage"
                                    value="{{ $project->seoProject->milestone_3_percentage ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="milestone_3_date">Guarantee for 3rd Milestone
                                    Date</label>
                                <input readonly required class="form-control" type="date" id="milestone_3_date"
                                    name="milestone_3_date"
                                    value="{{ $project->seoProject->milestone_3_date ?? '' }}">
                            </div>
                        @elseif ($project->project_type == 'Social Media')
                            <div class="col-lg-6">
                                <label class="form-label" for="channels">Channels</label>
                                <ul>
                                    @foreach ($project->socialMediaProject->channels as $channel)
                                        <li class="badge bg-dark py-1 px-3 text-light d-inline-block">
                                            {{ $channel }}</li>
                                    @endforeach
                                </ul>
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
                                    name="static_posts"
                                    value="{{ $project->socialMediaProject->static_posts ?? '' }}">
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
                                    name="remaining_payment"
                                    value="{{ $project->webDevProject->payment_type ?? '' }}">
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
                                    name="remaining_payment"
                                    value="{{ $project->webDevProject->site_condition ?? '' }}">
                            </div>
                        @else
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- TASKS --}}
        <span class="my-10 fw-bold">Project Tasks</span>
        <div class="card-body table-responsive" style="max-height: 500px; overflow-y: auto;" id="taskContainer">
            <table class="table bordered-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Assignee</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="taskList">
                    @foreach ($project->tasks as $task)
                        <tr data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                            aria-controls="offcanvasRight" onclick="loadTaskDetails({{ $task->id }})">
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                            <td>{{ $task->start_date }}</td>
                            <td>{{ $task->due_date }}</td>
                            <td>{{ $task->status }}</td>
                            <td>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="card-delete-button text-danger-600">
                                        <iconify-icon icon="fluent:delete-24-regular"
                                            class="icon text-lg line-height-1">
                                        </iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <input type="hidden" id="task_page" value="1">

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <span class="offcanvas-title" id="offcanvasRightLabel">Task Detail</span>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" id="taskDetails">
                ...
            </div>
        </div>

        <!-- Task Attachments Modal -->
        <div class="modal fade" id="taskAttachmentsModal" tabindex="-1" aria-labelledby="taskAttachmentsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="taskAttachmentsModalLabel">Upload Task Attachments</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadTaskAttachmentsForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="">
                            <div class="mb-3">
                                <label for="taskAttachments" class="form-label">Select Files</label>
                                <input type="file" name="attachments[]" id="taskAttachments" class="form-control"
                                    multiple required>
                            </div>

                            <button type="submit" class="btn btn-primary">Upload Files</button>
                        </form>
                        <div id="taskUploadStatus"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            <div class="modal fade" id="taskCreateModal" tabindex="-1" aria-labelledby="taskCreateModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="taskCreateModalLabel">Create Task</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            @include('tasks.create', [
                                'projectId' => $project->id,
                                'departments' => $departments,
                            ])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <script>
            $(document).on("change", ".task-status", function() {
                let taskId = $(this).attr("data-task-id");
                let updateUrl = "{{ route('tasks.updateStatus', ':id') }}".replace(':id', taskId); // Laravel Route Fix
                let newStatus = $(this).val();

                $.ajax({
                    url: updateUrl, // Now using the correct Laravel route
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"), // CSRF Token
                        status: newStatus,
                        task_id: taskId,
                    },
                    success: function(response) {
                        if (response) {
                            toastr.success(response.message);
                        } else {
                            toastr.error("Task status can't change!", "Error");
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr);
                        toastr.error("Task status can't change!", "Error");
                    }
                });
            });

            function loadTaskDetails(taskId) {
                $("input[name='task_id']").val(taskId);
                $('#taskDetails').html(
                    '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
                $.ajax({
                    url: '{{ route('tasks.show', ':id') }}'.replace(':id', taskId),
                    method: 'GET',
                    data: {
                        is_ajax: true
                    },
                    success: function(response) {
                        $('#taskDetails').html(response);
                        loadTaskAttachments();
                        loadComments()
                    },
                    error: function() {
                        $('#taskDetails').html('<p class="text-danger">Error fetching task details.</p>');
                    }
                });
            }

            function loadTaskAttachments() {
                let taskId = $("input[name='task_id']").val();
                $.ajax({
                    url: "{{ route('tasks.attachments.index', ':id') }}".replace(':id', taskId),
                    type: "GET",
                    success: function(response) {
                        renderAttachments(response.attachments);
                    }
                });
            }

            function renderAttachments(attachments) {
                let html = '';
                attachments.forEach(file => {
                    let filePath = "{{ asset('storage/') }}/" + file.file_path;
                    let fileName = file.file_path.slice(-10);
                    let extension = file.file_path.split('.').pop();
                    let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (imageExtensions.includes(extension)) {
                        html += `
        <div class="col-lg-4">
            <div class="overflow-hidden card mb-3 p-3 position-relative text-center shadow d-flex align-items-center justify-content-center flex-column">
                <button type="button" class="m-2 delete-attachment bg-light text-white position-absolute top-0 end-0 rounded-pills rounded p-2 border"
                    data-attachment-id="${file.id}"
                    data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)">
                    ❌
                </button>
                <img src="${filePath}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                <p class="mt-2 mb-2">${fileName}</p>
                <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">View Full</a>
            </div>
        </div>`;
                    } else {
                        html += `
        <div class="col-lg-4">
            <div class="overflow-hidden card mb-3 p-3 position-relative text-center shadow d-flex align-items-center justify-content-center flex-column">
                <button type="button" class="m-2 delete-attachment bg-light text-white position-absolute top-0 end-0 rounded-pills rounded p-2 border"
                    data-attachment-id="${file.id}"
                    data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)">
                    ❌
                </button>
                <img src="{{ asset('/images/doc.png') }}" width="50" height="50">
                <p class="mt-2 mb-2">${fileName}</p>
                <div>
                <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">View</a>
                <a href="${filePath}" download class="btn btn-sm btn-success">Download</a>    
                </div>
            </div>
        </div>`;
                    }
                });
                html += `
        @if ($userPermissions->contains('can add task files'))
        <div class="col-lg-4">
            <div style="height: 200px !important" class="p-3 d-flex align-items-center justify-content-center shadow card border w-100 h-100">
                <button type="button" class="text-center bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal"
                data-bs-target="#taskAttachmentsModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                  <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                </svg>
                <small class="tex-center text-dark w-100 d-block">Add Attachments</small>
                </button>    
            </div>
        </div>
        @endif
        `
                $("#taskAttachmentsContainer").html(html);
            }
            $("#uploadTaskAttachmentsForm").submit(function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                let taskId = $("input[name='task_id']").val();
                let uploadUrl = "{{ route('tasks.attachments.store', ':id') }}".replace(':id', taskId);

                $.ajax({
                    url: uploadUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $("#taskUploadStatus").html("<p class='text-info'>Uploading...</p>");
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#taskUploadStatus").html(
                                "<p class='text-success'>Files uploaded successfully!</p>");
                            loadTaskAttachments();
                            $('#taskAttachmentsModal').modal('hide');
                            toastr.success("Task attachment added!", "Success");
                        } else {
                            $("#taskUploadStatus").html("<p class='text-danger'>Error: " +
                                response.message + "</p>");
                        }
                    },
                    error: function(xhr) {
                        console.error("Upload Error:", xhr);
                        $("#taskUploadStatus").html(
                            "<p class='text-danger'>Something went wrong!</p>");
                    }
                });
            });
            $(document).on("click", ".delete-attachment", function() {
                let attachmentId = $(this).attr("data-attachment-id");

                if (confirm("Are you sure you want to delete this attachment?")) {
                    $.ajax({
                        url: "{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', attachmentId),
                        type: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content") // CSRF Token
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#attachment-" + attachmentId).remove();
                                toastr.info("Task attachment deleted!", "Info");
                                loadTaskAttachments()
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr);
                            alert("Something went wrong!");
                        }
                    });
                }
            });
            $(document).on("change", ".task-update-field", function() {
                let taskId = $("input[name='task_id']").val();
                let fieldName = $(this).attr("name");
                let fieldValue = $(this).val();
                setTimeout(() => {
                    $.ajax({
                        url: "{{ route('tasks.update.json', ':id') }}".replace(':id', taskId),
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            field: fieldName,
                            value: fieldValue
                        },
                        success: function(response) {
                            toastr.success(`${response.message}`, "Success");
                        },
                        error: function(xhr) {
                            toastr.error("Update failed. Please try again.");
                        }
                    });
                }, 500);
            });
            $(document).ready(function() {
                var deleteUrlTemplate = '{{ route('starting_kw_url.delete', ['id' => ':id']) }}';

                function loadStartingKwUrls() {
                    let taskId = $("input[name='task_id']").val();
                    $.ajax({
                        url: '{{ route('offsite.tasks.starting_kw_urls', ['task_id' => 'taskId']) }}',
                        type: "GET",
                        success: function(data) {
                            var tableBody = $("#startingKwUrlsTable");
                            tableBody.empty();

                            var dataArray = Array.isArray(data) ? data : Object.values(data);

                            if (dataArray.length === 0) {
                                tableBody.append('<tr><td colspan="4">No Data Found</td></tr>');
                            } else {
                                dataArray.forEach(function(kwUrl) {
                                    tableBody.append(`
                                <tr id="row-${kwUrl.id}">
                                    <td>${kwUrl.keyword}</td>
                                    <td><a href="${kwUrl.url}" target="_blank">${kwUrl.url}</a></td>
                                    <td>${kwUrl.domain}</td>
                                    @if ($userPermissions->contains('can remove offsite links'))
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${kwUrl.id}">Delete</button>
                                    </td>
                                    @endif
                                </tr>
                            `);
                                });
                            }
                        },
                        error: function() {
                            $("#startingKwUrlsTable").html(
                                '<tr><td colspan="4">Error Loading Data</td></tr>');
                        }
                    });
                }

                loadStartingKwUrls();

                $("#addNewLinkRow").click(function() {
                    $("#linksTableBody").append(`
                <tr>
                    <td><input type="text" class="form-control keyword" required></td>
                    <td><input type="url" class="form-control url" required></td>
                    <td><button type="button" class="btn btn-danger remove-link">X</button></td>
                </tr>
            `);
                });

                $(document).on("click", ".remove-link", function() {
                    $(this).closest("tr").remove();
                });

                $("#addOffsiteLinkForm").submit(function(event) {
                    event.preventDefault();
                    let taskId = $("input[name='task_id']").val();
                    let offsite_task_id = $('#offsite_task_id').val()

                    let links = [];
                    $('.kw-url-input').each(function() {
                        let keyword = $(this).find('.keyword').val();
                        let url = $(this).find('.url').val();

                        if (keyword && url) {
                            links.push({
                                keyword,
                                url
                            });
                        }
                    });

                    $('#kw_url').val(JSON.stringify(links));

                    $("#linksTableBody tr").each(function() {
                        var keyword = $(this).find(".keyword").val();
                        var url = $(this).find(".url").val();

                        if (keyword && url) {
                            links.push({
                                keyword: keyword,
                                url: url,
                            });
                        }
                    });

                    if (links.length === 0) {
                        alert("Please add at least one valid link.");
                        return;
                    }

                    var formData = {
                        _token: "{{ csrf_token() }}",
                        kw_url: JSON.stringify(links),
                        task_id: taskId,
                        project_id: "{{ $project->id }}",
                        offsite_task_id: offsite_task_id
                    };

                    $.ajax({
                        url: '{{ route('starting_kw_url.store') }}',
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            $("#addOffsiteLinkModal").modal("hide");
                            $("#addOffsiteLinkForm")[0].reset();
                            $("#linksTableBody").html(`
                        <tr>
                            <td><input type="text" class="form-control keyword" required></td>
                            <td><input type="url" class="form-control url" required></td>
                            <td><button type="button" class="btn btn-danger remove-link">X</button></td>
                        </tr>
                    `);
                            loadStartingKwUrls();
                            $('.add-more-links-wrapper').slideUp();
                            toastr.success("Links Added!", "Success");
                        },
                        error: function() {
                            alert("Failed to add links!");
                        }
                    });
                });

                $(document).on("click", ".delete-btn", function(event) {
                    event.preventDefault();

                    var id = $(this).data("id");
                    var deleteUrl = deleteUrlTemplate.replace(":id", id);

                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        success: function() {
                            $("#row-" + id).fadeOut();
                        },
                        error: function() {
                            alert("Failed to delete record!");
                        }
                    });
                });

                $(document).on('click', '#add-kw-url', function() {
                    $('#kw-url-container').append(`
            <div class="row mb-2 kw-url-input">
                <div class="col">
                    <input type="text" class="form-control keyword" placeholder="Keyword">
                </div>
                <div class="col">
                    <input type="text" class="form-control url" placeholder="URL">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-kw-url">X</button>
                </div>
            </div>
        `);
                });

                $(document).on('click', '.remove-kw-url', function() {
                    $(this).closest('.kw-url-input').remove();
                });

                $(document).on('click', '.open-add-more-links-wrapper', function() {
                    $('.add-more-links-wrapper').slideDown()
                });

                function toggleRepeatStatus(taskId, action) {
                    $.ajax({
                        url: "{{ url('/tasks') }}/" + taskId + "/" + action,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.message);
                            location.reload();
                        },
                        error: function() {
                            alert('Something went wrong. Try again.');
                        }
                    });
                }

                $(document).on('click', '.disable-repeating', function() {
                    let taskId = $(this).data('task-id');
                    toggleRepeatStatus(taskId, "disable-repeating");
                });

                $(document).on('click', '.enable-repeating', function() {
                    let taskId = $(this).data('task-id');
                    toggleRepeatStatus(taskId, "enable-repeating");
                });
            });

            function loadComments() {
        let taskId = $("input[name='task_id']").val();
        $.get("{{ route('comments.index', ['task' => ':taskId']) }}".replace(':taskId', taskId), function(data) {
            $('#comment-list').empty();
            data.forEach(comment => {
                $('#comment-list').append(
                    `
  <div class="py-16 px-24 border-bottom ${comment.user_id === {{ auth()->id() }} ? 'bg-primary-50' : ''}">
    <div class="d-flex align-items-start gap-3">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="" class="w-40-px h-40-px rounded-pill">
        <div class="w-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fs-6">${comment.user.name}</h6>
                    ${comment.user_id === {{ auth()->id() }} ? `<span class="badge fs-7 bg-success-600 text-white text-xs">You</span>` : ''}
                </div>
                ${comment.user_id === {{ auth()->id() }} ? `
                <button class="text-danger-600 delete-comment" data-id="${comment.id}">
                    <iconify-icon icon="fluent:delete-24-regular" class="icon text-lg line-height-1"></iconify-icon>
                </button>` : ''}
            </div>
            <div class="mt-10">
                <p class="mb-0 text-primary-light">${comment.comment}</p>
            </div>
        </div>
    </div>
</div>

                    `
                );
            });
        });
    }

    $(document).on('click', '#add-comment', function() {
        let taskId = $("input[name='task_id']").val();
        let commentText = $('#comment-text').val();
        $.post("{{ route('comments.store') }}", {
            task_id: taskId,
            comment: commentText,
            _token: "{{ csrf_token() }}"
        }, function() {
            $('#comment-text').val('');
            loadComments();
        });
    });

    $(document).on('click', '.delete-comment', function() {
        let commentId = $(this).data('id');
        $.ajax({
            url: "{{ route('comments.destroy', ['id' => ':id']) }}".replace(':id', commentId),
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                loadComments();
            }
        });
    });
        </script>

        {{-- TASKS --}}

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
            $(document).ready(function() {
                let projectId = "{{ $project->id }}";
                let taskPage = 1;
                let isLoading = false;

                $('#taskContainer').on('scroll', function() {
                    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 10) {
                        if (!isLoading) {
                            isLoading = true;
                            taskPage++;
                            loadMoreTasks(taskPage, projectId);
                        }
                    }
                });

                function loadMoreTasks(page, projectId) {
                    $.ajax({
                        url: "{{ route('tasks.loadMore') }}",
                        type: "GET",
                        data: {
                            page: page,
                            project_id: projectId
                        },
                        beforeSend: function() {
                            $('#taskList').append(
                                '<tr id="loading"><td colspan="6" class="text-center">Loading...</td></tr>'
                            );
                        },
                        success: function(response) {
                            $('#loading').remove();
                            if (response.tasks.length > 0) {
                                response.tasks.forEach(task => {
                                    let row = `
                                        <tr>
                                            <td>${task.id}</td>
                                            <td>${task.title}</td>
                                            <td>${task.assigned_to_name ?? 'Unassigned'}</td>
                                            <td>${task.start_date ?? ''}</td>
                                            <td>${task.due_date ?? ''}</td>
                                            <td>${task.status}</td>
                                        </tr>`;
                                    $('#taskList').append(row);
                                });
                                isLoading = false;
                            } else {
                                $('#taskContainer').off('scroll');
                            }
                        },
                        error: function() {
                            $('#loading').remove();
                            alert("Failed to load more tasks.");
                        }
                    });
                }
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
                                toastr.info("File deleted successfully!", "Info");
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
                    toastr.info("Please select at least one file!", "Info");
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
                            toastr.success("File Uploaded Successfully!", "Success");
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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".project-status").forEach(function(select) {
                    select.addEventListener("change", function() {
                        let projectId = this.getAttribute("data-project-id");
                        let newStatus = this.value;

                        fetch("{{ route('projects.updateStatus', ':id') }}".replace(':id',
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
                                    toastr.success("Project Status Updated!", "Success");
                                } else {
                                    alert("Error: " + data.message);
                                }
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
