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
    @if (auth()->user()->hasRole('ADMIN') && route('tasks.board.index') == url()->current())
        <div class="row mb-16 gy-3">
            @foreach ($progressData as $department => $progress)
                <div class="col-lg-2">
                    <div class="card p-0 overflow-hidden position-relative radius-12">
                        <div class="card-header p-10 bg-base border border-end-0 border-start-0 border-top-0">
                            <span class="fw-bold fs-7">{{ $department }}</span>
                        </div>
                        <div class="card-body p-10">
                            <div class="progressBar w-100-px h-44-px position-relative text-primary-light fw-semibold">
                                <div class="barOverflow">
                                    <div class="circleBar border-width-6-px"></div>
                                </div>
                                <div
                                    class="position-absolute start-50 translate-middle top-50 line-height-1 mt-8 w-100 text-center">
                                    <div class="d-flex align-items-center justify-content-center line-height-1 text-sm">
                                        <span class="fs-7 barNumber line-height-1">{{ $progress }} </span>
                                        <span class="fs-7">%</span>
                                    </div>
                                    <span
                                        class="line-height-1 text-xs text-secondary-light fs-7">{{ $department }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="d-flex align-items-center justify-content-between">
        <button data-bs-toggle="modal" data-bs-target="#taskCreateModal" class="btn btn-sm mb-3 btn-outline-primary">
            Create Task
        </button>
        <div>
            <select class="form-control" id="view_type">
                <option value="list" selected>List View</option>
                <option value="board">Board View</option>
            </select>
        </div>
    </div>
    <div class="card mb-3 basic-data-table">
        <div class="card-header d-flex justify-content-start flex-wrap gap-10 align-items-center">
            <div class="row mb-10 gy-3">
                <div class="col-lg-4">
                    <input type="date" id="start_date_search" name="start_date_search" class="form-control"
                        placeholder="Select Start Date">
                </div>
                <div class="col-lg-4">
                    <input type="date" id="end_date_search" name="end_date_search" class="form-control"
                        placeholder="Select End Date">
                </div>
                <div class="col-lg-4 d-flex align-items-center">
                    <button id="search_tasks" class="btn btn-primary me-2">Search</button>
                    <button id="reset_filters" class="btn btn-secondary">Reset</button>
                </div>
            </div>
            <div class="row mb-10 gy-3">
                @if (auth()->user()->hasRole('ADMIN') || route('tasks.assigned.index') == url()->current())
                    <div class="col-lg-4">
                        <select id="taskType" class="form-select">
                            <option value="" disabled selected>Filter By Type</option>
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
                            <option value="" selected disabled>Filter By User</option>
                            <option value="ALL">ALL</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
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

    <div class="row gy-3 mb-10">
        <div class="col-lg-12">
            <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                <div
                    class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                    <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                        @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $index => $status)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center {{ $index === 0 ? 'active' : '' }}"
                                    id="{{ strtolower(str_replace(' ', '-', $status)) }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#{{ strtolower(str_replace(' ', '-', $status)) }}-task"
                                    type="button" role="tab">
                                    {{ $status }} Tasks
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">
                                        ({{ $tasks->where('status', $status)->count() }})
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="task-tabContent">
                        @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $index => $status)
                            <div style="max-height: 500px; overflow-y: auto;"
                                class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                id="{{ strtolower(str_replace(' ', '-', $status)) }}-task" role="tabpanel">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Project</th>
                                            <th>Title</th>
                                            @if (route('tasks.assigned.index') == url()->current() || auth()->user()->hasRole('ADMIN'))
                                                <th>Assigned To</th>
                                            @endif
                                            <th>Created By</th>
                                            <th>Start Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks->where('status', $status) as $task)
                                            <tr onclick="loadTaskDetails({{ $task->id }})">
                                                <td>{{ $task->id }}</td>
                                                <td><a target="_blank"
                                                        href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title ?? 'N/A' }}</a>
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                        aria-controls="offcanvasRight"
                                                        onclick="loadTaskDetails({{ $task->id }})"
                                                        href="#">
                                                        {{ $task->title }}
                                                    </a>
                                                </td>
                                                @if (route('tasks.assigned.index') == url()->current() || auth()->user()->hasRole('ADMIN'))
                                                    <td>{{ $task->assignedTo->name ?? 'N/A' }}</td>
                                                @endif
                                                <td>
                                                    {{ $task->createdBy->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $task->start_date
                                                        ? \Carbon\Carbon::parse($task->start_date)->setTimezone('Asia/Dubai')->format('d-m-Y h:i A')
                                                        : 'Not Set' }}
                                                </td>
                                                <td>
                                                    <a target="_blank" href="{{ route('tasks.show', $task->id) }}"
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

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header bg-base">
            <h6 class="text-md mb-0 fw-medium">Task Detail</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body bg-base" id="taskDetails">
            ...
        </div>
    </div>
    <div class="modal fade" id="taskAttachmentsModal" tabindex="-1" aria-labelledby="taskAttachmentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="taskAttachmentsModalLabel">Upload Task Attachments</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp",
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
            $(this).attr('disabled', true);
            $(this).text('Saving...');
            setTimeout(() => {
                $(this).attr('disabled', false);
                $(this).text('Submit');
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
                    window.location.href = "{{ route('tasks.board.index') }}"
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
            $('#repeat_task').change(function() {
                if ($(this).val() == 1) {
                    $('#repeat_every_group').slideDown();
                } else {
                    $('#repeat_every_group').slideUp();
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
                },
                error: function() {
                    $('#taskDetails').html(
                        '<p class="text-danger">This task may have been deleted or is no longer accessible. Please reach out to the admin for further details.</p>'
                    );
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            let type = new URLSearchParams(window.location.search).get('type');
            if (type) {
                $('#taskType').val(type);
            }
            $('#taskType').on('change', function() {
                let selectedType = $(this).val();
                let urlParams = new URLSearchParams(window.location.search);
                if (selectedType && selectedType !== "ALL") {
                    urlParams.set('type', selectedType);
                } else {
                    urlParams.delete('type');
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
                let baseUrl = "{{ route('tasks.board.index') }}";

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

            flatpickr("#start_date_search", {
                enableTime: false,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                allowInput: true,
                defaultDate: startDate ? startDate : null // Set date from URL if exists
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

                if (startDate) urlParams.set('start_date', startDate);
                else urlParams.delete('start_date');

                if (endDate) urlParams.set('end_date', endDate);
                else urlParams.delete('end_date');

                window.location.search = urlParams.toString(); // Reload with updated params
            });

            $('#reset_filters').on('click', function() {
                $('#start_date_search').flatpickr().clear(); // Clear start date
                $('#end_date_search').flatpickr().clear(); // Clear end date
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
                                `<a target="_blank" href="{{ url('tasks') }}/${task.id}" class="list-group-item list-group-item-action">${task.id} - ${task.title}</a>`
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
