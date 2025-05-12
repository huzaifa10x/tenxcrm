<x-app-layout>
    <x-slot name="header">
        {{ __('Tasks Board') }}
    </x-slot>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @php
        $pendingStatuses = ['Todo', 'In Progress', 'Review'];
        $completedStatuses = ['Completed'];
        $pendingCount = $tasks->whereIn('status', $pendingStatuses)->where('repeat_task', '0')->count();
        $completedCount = $tasks->whereIn('status', $completedStatuses)->where('repeat_task', '0')->count();
    @endphp

    <div class="row gy-3 mb-10">
        <div class="col-lg-6">
            <div class="card h-100 radius-8 border-0 overflow-hidden">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                        <h6 class="mb-2 fw-bold text-lg">Tasks</h6>
                    </div>
                    <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                        <li class="d-flex align-items-center gap-2">
                            <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                            <span class="text-secondary-light text-sm fw-normal">Pending Tasks:
                                <span class="text-primary-light fw-semibold">({{ $pendingCount }})</span>
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                            <span class="text-secondary-light text-sm fw-normal">Completed Tasks:
                                <span class="text-primary-light fw-semibold">({{ $completedCount }})</span>
                            </span>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="d-flex my-10 align-items-center justify-content-between">
        <button data-bs-toggle="modal" data-bs-target="#taskCreateModal" class="btn btn-info-900">
            Create Task
        </button>
        <div>
            <select class="form-control" id="view_type">
                <option value="list" selected>List View</option>
                <option value="board">Board View</option>
            </select>
        </div>
    </div>

    <div class="row mb-10 gy-3">
        <div class="col">
            <input type="date" id="start_date_search" name="start_date_search" class="form-control"
                placeholder="Select Start Date">
        </div>
        <div class="col">
            <input type="date" id="end_date_search" name="end_date_search" class="form-control"
                placeholder="Select End Date">
        </div>
        <div class="col d-flex align-items-center">
            <button id="search_tasks" class="btn btn-primary me-2">Search</button>
            <button id="reset_filters" class="btn btn-secondary">Reset</button>
        </div>
    </div>

    <div class="row mb-10 gy-3">
        @if (auth()->user()->hasRole('ADMIN') ||
                route('tasks.index') == url()->current() ||
                $userPermissions->contains('can view all tasks'))
            <div class="col-lg-4">
                <select id="taskType" class="form-select">
                    <option value="" disabled selected>Filter by task type</option>
                    <option value="ALL">ALL</option>
                    <option value="CONTENT">CONTENT</option>
                    <option value="ONSITE">ONSITE</option>
                    <option value="TECH MANAGER">TECH MANAGER</option>
                    <option value="OFFSITE">OFFSITE</option>
                    <option value="DESIGN">DESIGN</option>
                    <option value="DEVELOPMENT">DEVELOPMENT</option>
                    <option value="AM">AM</option>
                    <option value="SEO ADMIN">SEO ADMIN</option>
                    <option value="ADMIN">ADMIN</option>
                    <option value="SALES MANAGER">SALES MANAGER</option>
                    <option value="SALES">SALES</option>
                    <option value="ADHOC">ADHOC</option>
                    <option value="SOCIAL MEDIA MANAGER">SOCIAL MEDIA MANAGER</option>
                </select>
            </div>
            <div class="col-lg-4">
                <select id="byUser" class="form-select">
                    <option value="" selected disabled>Filter by assigned to</option>
                    <option value="ALL">ALL</option>
                    @foreach ($users->sortBy('name') as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-lg-4">
            <select id="byAssignedFrom" class="form-select">
                <option value="" selected disabled>Filter by assigned from</option>
                <option value="ALL">ALL</option>
                @foreach ($users->sortBy('name') as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <select id="byProject" class="form-select">
                <option value="" selected disabled>Filter by project</option>
                <option value="ALL">ALL</option>
                @foreach ($projects->sortBy('title') as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <input type="date" id="completed_date_start" name="completed_date_start" class="form-control"
                placeholder="Select Completed Date From">
        </div>
        <div class="col-lg-4">
            <input type="date" id="completed_date_end" name="completed_date_end" class="form-control"
                placeholder="Select Completed Date To">
        </div>
        <div class="col-lg-4">
            <button id="showAllAutomated" class="btn btn-outline-primary">All Automated Tasks</button>
        </div>
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" id="taskSearch" class="form-control" placeholder="Search Task by Title...">
                <div id="input2" class="list-group"
                    style="position: absolute;
                top: 44px;
                z-index: 1000;
                left: 1px;
                max-height: 300px;
                overflow-y: auto;
                width: 100%;">
                </div>
            </div>
        </div>
    </div>

    @php
        $statusColors = [
            'Todo' => 'bg-info-600',
            'In Progress' => 'bg-lilac-600',
            'Review' => 'bg-success-600',
            'Completed' => 'bg-warning-600',
            'Blocked' => 'bg-danger-600',
        ];
    @endphp

    {{-- MAIN SCREEN --}}
    @if (request()->query('view_type') === 'board')
        <div class="overflow-x-auto scroll-sm pb-8">
            <div class="kanban-wrapper">
                <div class="d-flex align-items-start gap-24" id="sortable-wrapper">
                    @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $status)
                        <div
                            class="w-25 kanban-item radius-12 progress-card kanban-column scroll-sm position-relative">
                            @php
                                $colorClass = $statusColors[$status] ?? 'bg-secondary-600';
                                $taskCount = $tasks->where('status', $status)->where('repeat_task', '0')->count();
                            @endphp
                            <span
                                class="position-absolute top-0 end-0 m-10 badge text-sm fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle {{ $colorClass }} text-white">
                                {{ $taskCount }}
                            </span>
                            <div class="card p-0 radius-12 overflow-hidden shadow-none">
                                <div class="card-body p-0 pb-24">
                                    <div
                                        class="d-flex align-items-center gap-2 justify-content-between ps-24 pt-24 pe-24">
                                        <h6 class="text-lg fw-semibold mb-0">{{ $status }}</h6>
                                    </div>
                                    <div class="connectedSortable ps-24 pt-24 pe-24 kanban-items"
                                        id="{{ strtolower(str_replace(' ', '-', $status)) }}"
                                        data-status="{{ $status }}">
                                        @foreach ($tasks->where('status', $status)->where('repeat_task', '0') as $task)
                                            <div data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                aria-controls="offcanvasRight"
                                                onclick="loadTaskDetails({{ $task->id }})"
                                                class="kanban-item kanban-card bg-neutral-50 p-16 radius-8 mb-24 position-relative"
                                                data-task-id="{{ $task->id }}"
                                                data-status="{{ $status }}">
                                                <span
                                                    class="fs-7 mb-10 bg-cyan-100 badge rounded fs-8 text-secondary position-absolute top-0 start-0 fw-light">#{{ $task->id }}</span>
                                                <span
                                                    class="fs-7 mb-10 bg-warning-gradient mt-12 badge rounded text-dark">{{ $task->project->title ?? '' }}</span>
                                                <h6 class="kanban-title text-lg fw-semibold fs-6 mb-0">
                                                    {{ $task->title }}
                                                </h6>
                                                @if ($userPermissions->contains('can create tasks'))
                                                    <span
                                                        class="text-capitalize bg-cyan-100 badge rounded fs-8 text-secondary mb-10">assigned
                                                        to:
                                                        {{ $task->assignedTo ? $task->assignedTo->name : 'n/a' }}</span>
                                                @endif
                                                <div class="d-flex align-items-center justify-content-between gap-10">
                                                    <div
                                                        class="d-flex fs-7 align-items-center justify-content-between gap-10">
                                                        Start Date:
                                                        <span class="start-date text-secondary-light">
                                                            {{ $task->start_date
                                                                ? \Carbon\Carbon::parse($task->start_date)->setTimezone('Asia/Dubai')->format('d-m-Y h:i A')
                                                                : 'Not Set' }}
                                                        </span>

                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="row gy-3 mb-10">
            <div class="col-lg-12">
                <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                    <div
                        class="card-header flex-wrap gap-1 p-0 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                            @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $index => $status)
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link d-flex align-items-center {{ $index === 0 ? 'active' : '' }}"
                                        id="{{ strtolower(str_replace(' ', '-', $status)) }}-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#{{ strtolower(str_replace(' ', '-', $status)) }}-task"
                                        type="button" role="tab">
                                        {{ $status }} Tasks
                                        <span
                                            class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">
                                            ({{ $tasks->where('status', $status)->where('repeat_task', '0')->count() }})
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="task-tabContent">
                            @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $index => $status)
                                <div style="max-height: 550px; overflow-y: auto;"
                                    class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="{{ strtolower(str_replace(' ', '-', $status)) }}-task" role="tabpanel">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Project</th>
                                                <th>Title</th>
                                                @if (request('type') == 'task-created-by-me' || auth()->user()->hasRole('ADMIN'))
                                                    <th>Assigned To</th>
                                                @endif
                                                <th>Priority</th>
                                                <th>Created By</th>
                                                <th>Start Date</th>
                                                <th>Completed Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tasks->where('status', $status)->where('repeat_task', '0') as $task)
                                                <tr onclick="loadTaskDetails({{ $task->id }})">
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">
                                                            @php
                                                                $priorityColor = match ($task->priority) {
                                                                    'Low' => 'bg-success-600', // Green
                                                                    'Medium' => 'bg-primary-600', // Blue
                                                                    'High' => 'bg-warning-600', // Yellow
                                                                    'Urgent' => 'bg-danger-600', // Red
                                                                    default => 'bg-secondary-600', // Gray fallback
                                                                };
                                                            @endphp

                                                            <span
                                                                class="translate-middle-y rounded-pill {{ $priorityColor }} border-0 w-8-px h-8-px"></span>

                                                            {{ $task->project->title ?? 'N/A' }}
                                                            @if ($task->is_automated)
                                                                <span
                                                                    class="text-white bg-danger-600 fs-7 border border-danger-600 radius-4 px-8 py-4 text-sm line-height-1 fw-medium">automated</span>
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="loadTaskDetails({{ $task->id }})"
                                                            href="#" class="task-title-class-for-highlight">
                                                            {{ $task->title }}
                                                        </a>
                                                    </td>
                                                    @if (request('type') == 'task-created-by-me' || auth()->user()->hasRole('ADMIN'))
                                                        <td>{{ $task->assignedTo->name ?? 'N/A' }}</td>
                                                    @endif
                                                    <td>
                                                        {{ $task->priority }}
                                                    </td>
                                                    <td>
                                                        {{ $task->createdBy->name ?? 'System or user deleted' }}
                                                    </td>
                                                    <td>
                                                        {{ $task->start_date
                                                            ? \Carbon\Carbon::parse($task->start_date)->setTimezone('Asia/Dubai')->format('d-m-Y h:i A')
                                                            : 'Not Set' }}
                                                    </td>
                                                    <td>
                                                        {{ $task->completed_date
                                                            ? \Carbon\Carbon::parse($task->completed_date)->setTimezone('Asia/Dubai')->format('d-m-Y h:i A')
                                                            : 'Not Set' }}
                                                    </td>
                                                    <td>
                                                        <a target="_blank"
                                                            href="{{ route('tasks.show', $task->id) }}"
                                                            class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                            <iconify-icon icon="majesticons:eye-line"
                                                                class="icon text-xl"></iconify-icon>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN SCREEN --}}

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header bg-base">
            <h6 class="text-md mb-0 fw-medium">Task Detail</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body bg-base" id="taskDetails">
            ...
        </div>
    </div>

    <form action="{{ route('tasks.store') }}" id="task-create-form" method="POST" enctype="multipart/form-data">
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
                        @include('tasks.create', ['projects' => $projects])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary task-submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.tiny.cloud/1/xv2ulo11uhx3jiaj39bykatfcit3wcn9oayhfnkmq96hiypb/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script type="text/javascript">
        var uploadedDocumentMap = {};
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#taskAttachmentsDropzone", {
            url: "{{ route('tasks.store') }}", // Laravel route for form submission
            autoProcessQueue: false,
            uploadMultiple: true,
            paramName: "attachments", // Ensure Laravel handles it as an array
            maxFiles: 10,
            maxFilesize: 5, // 5MB per file
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            init: function() {
                var dropzoneInstance = this;
                dropzoneInstance.on("sending", function(file, xhr, formData) {
                    var form = document.querySelector("#task-create-form");
                    var formDataEntries = new FormData(form);
                    formDataEntries.forEach((value, key) => {
                        formData.append(key, value);
                    });
                    formData.append("attachments[]", file);
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
        $('.task-submit').click(function(e) {
            if ($('#department_id').val() == null) {
                toastr.error('Please select Department!');
                $('#department_id').focus()
                return;
            }
            if ($('#sub_type').val() == null) {
                toastr.error('Please select Task Type!');
                $('#sub_type').focus();
                return;
            }
            $(this).attr('disabled', true);
            $(this).text('Saving...');
            setTimeout(() => {
                $(this).attr('disabled', false);
                $(this).text('submit');
            }, 3000);
            e.preventDefault();
            var formData = new FormData($("#task-create-form")[0]);
            myDropzone.files.forEach(function(file) {
                formData.append("attachments[]", file);
            });
            $.ajax({
                url: "{{ route('tasks.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success('Task created successfully');
                    setTimeout(() => {
                        window.location.href = "{{ route('tasks.index') }}"
                    }, 3000);
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
            $(".kanban-items").sortable({
                connectWith: ".kanban-items",
                placeholder: "ui-state-highlight",
                stop: function(event, ui) {
                    let taskId = ui.item.attr("data-task-id");
                    let newStatus = ui.item.parent().attr("data-status");

                    if (newStatus == "Completed") {
                        toastr.error(
                            "You can't complete the task without attaching the required closing inputs."
                        );
                        setTimeout(() => {
                            toastr.info("Please open the task and complete it properly.");
                        }, 3000);
                        return false;
                    }

                    $.ajax({
                        url: "{{ route('tasks.updateStatus') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            task_id: taskId,
                            status: newStatus
                        },
                        success: function(response) {
                            toastr.success("Task status updated!");
                        },
                        error: function() {
                            toastr.error("Error updating task status");
                        }
                    });
                }
            }).disableSelection();
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
                    loadComments();
                    setTimeout(() => {
                        tinymce.init({
                            selector: '#comment-text',
                            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table directionality emoticons paste textpattern',
                            menubar: 'file edit view insert format tools table help',
                            statusbar: true,
                            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | blockquote | link image media | insertdatetime emoticons charmap | fullscreen code preview',
                            height: 400, // You can adjust the height of the editor as needed
                            width: '100%',
                            automatic_uploads: true,
                            file_picker_types: 'image media',
                            image_advtab: true, // Image advanced tab for better image management
                            content_css: '//www.tiny.cloud/css/codepen.min.css', // Optional, provides a clean code view for editor content
                            toolbar_mode: 'floating',
                        });
                    }, 3000);
                },
                error: function() {
                    $('#taskDetails').html(
                        '<p class="text-danger">This task may have been deleted or is no longer accessible. Please reach out to the admin for further details.</p>'
                    );
                }
            });
        }

        // Get the full path after domain
        let path = window.location.pathname; // Example: "/tasks/31"
        // Split the path into parts
        let segments = path.split('/');
        // Assuming task ID is always at index 2 (/tasks/{id})
        let taskId = segments[2];
        loadTaskDetails(taskId)
    </script>
    <script>
        $(document).ready(function() {
            let type = new URLSearchParams(window.location.search).get('category');
            if (type) {
                $('#taskType').val(type);
            }
            $('#taskType').on('change', function() {
                let selectedType = $(this).val();
                let urlParams = new URLSearchParams(window.location.search);
                if (selectedType && selectedType !== "ALL") {
                    urlParams.set('category', selectedType);
                } else {
                    urlParams.delete('category');
                }

                window.location.search = urlParams.toString();
            });
        });

        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let userId = urlParams.get('user_id');

            if (userId) {
                $('#byUser').val(userId);
            }

            $('#byUser').on('change', function() {
                let selectedUser = $(this).val();
                let baseUrl = "{{ request()->url() }}";

                if (selectedUser === 'ALL') {
                    window.location.href = baseUrl;
                } else {
                    urlParams.set('user_id', selectedUser);
                    window.location.href = baseUrl + "?" + urlParams.toString();
                }
            });
        });

        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let userId = urlParams.get('project_id');

            if (userId) {
                $('#byProject').val(userId);
            }

            $('#byProject').on('change', function() {
                let selectedUser = $(this).val();
                let baseUrl = "{{ request()->url() }}";

                if (selectedUser === 'ALL') {
                    window.location.href = baseUrl;
                } else {
                    urlParams.set('project_id', selectedUser);
                    window.location.href = baseUrl + "?" + urlParams.toString();
                }
            });
        });

        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            $('#showAllAutomated').on('click', function() {
                let baseUrl = "{{ request()->url() }}";
                urlParams.set('all-automated', 1);
                window.location.href = baseUrl + "?" + urlParams.toString();
            });
        });

        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let userId = urlParams.get('assigned_from');

            if (userId) {
                $('#byAssignedFrom').val(userId);
            }

            $('#byAssignedFrom').on('change', function() {
                let selectedUser = $(this).val();
                let baseUrl = "{{ request()->url() }}";

                if (selectedUser === 'ALL') {
                    window.location.href = baseUrl;
                } else {
                    urlParams.set('assigned_from', selectedUser);
                    window.location.href = baseUrl + "?" + urlParams.toString();
                }
            });
        });

        $(document).ready(function() {
            let urlParams = new URLSearchParams(window.location.search);
            let viewType = urlParams.get('view_type');

            if (viewType) {
                $('#view_type').val(viewType);
            }

            $('#view_type').on('change', function() {
                let selectedType = $(this).val();
                let baseUrl = "{{ request()->url() }}";
                urlParams.set('view_type', selectedType);
                window.location.href = baseUrl + "?" + urlParams.toString();
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let urlParams = new URLSearchParams(window.location.search);
            let startDate = urlParams.get('start_date');
            let endDate = urlParams.get('end_date');
            let completedDateStart = urlParams.get('completed_date_start');
            let completedDateEnd = urlParams.get('completed_date_end');

            flatpickr("#start_date_search", {
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: true,
                defaultDate: startDate ? startDate : null // Set date from URL if exists
            });

            flatpickr("#completed_date_start", {
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: true,
                defaultDate: completedDateStart ? completedDateStart : null // Set date from URL if exists
            });

            flatpickr("#completed_date_end", {
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: true,
                defaultDate: completedDateEnd ? completedDateEnd : null // Set date from URL if exists
            });

            flatpickr("#end_date_search", {
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: true,
                defaultDate: endDate ? endDate : null // Set date from URL if exists
            });

            $('#search_tasks').on('click', function() {
                let startDate = $('#start_date_search').val();
                let endDate = $('#end_date_search').val();
                let completedDateStart = $('#completed_date_start').val();
                let completedDateEnd = $('#completed_date_end').val();

                if (startDate) urlParams.set('start_date', startDate);
                else urlParams.delete('start_date');

                if (endDate) urlParams.set('end_date', endDate);
                else urlParams.delete('end_date');


                if (completedDateStart) urlParams.set('completed_date_start', completedDateStart);
                else urlParams.delete('completed_date_start');

                if (completedDateEnd) urlParams.set('completed_date_end', completedDateEnd);
                else urlParams.delete('completed_date_end');

                window.location.search = urlParams.toString(); // Reload with updated params
            });

            $('.task-title-class-for-highlight').on('click', function() {
                $('.task-title-class-for-highlight').css('color', '#000')
                $(this).css('color', '#45B369')
            });

            $('#reset_filters').on('click', function() {
                $('#start_date_search').flatpickr().clear(); // Clear start date
                $('#end_date_search').flatpickr().clear(); // Clear end date
                $('#completed_date_start').flatpickr().clear(); // Clear end date
                $('#completed_date_end').flatpickr().clear(); // Clear end date
                window.location.search = ''; // Reset URL params
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#taskSearch').on('input', function() {
                let query = $(this).val();
                if (query.length < 2) {
                    $('#input2').hide();
                    return;
                }

                $.ajax({
                    url: "{{ route('tasks.search.index') }}",
                    method: "GET",
                    data: {
                        query: query
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            let suggestions = response.map(task =>
                                `<a target="_blank" href="{{ url('tasks') }}/${task.id}" class="list-group-item list-group-item-action">${task.id} - ${task.title} - ${task.project_title} - ${task.status}</a>`
                            ).join('');
                            $('#input2').html(suggestions).show();
                        } else {
                            $('#input2').hide();
                        }
                    }
                });
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#taskSearch, #input2').length) {
                    $('#input2').hide();
                }
            });
        });
    </script>
</x-app-layout>
