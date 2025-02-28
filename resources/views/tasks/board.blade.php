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
    <div class="card mb-3 basic-data-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            @if ($userPermissions->contains('can create tasks'))
                <button data-bs-toggle="modal" data-bs-target="#taskCreateModal" class="btn btn-outline-primary">
                    Create Task
                </button>
            @endif
        </div>
    </div>
    <div class="overflow-x-auto scroll-sm pb-8">
        <div class="kanban-wrapper">
            <div class="d-flex align-items-start gap-24" id="sortable-wrapper">
                @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $status)
                    <div class="w-25 kanban-item radius-12 progress-card kanban-column scroll-sm">
                        <div class="card p-0 radius-12 overflow-hidden shadow-none">
                            <div class="card-body p-0 pb-24">
                                <div class="d-flex align-items-center gap-2 justify-content-between ps-24 pt-24 pe-24">
                                    <h6 class="text-lg fw-semibold mb-0">{{ $status }}</h6>
                                </div>
                                <div class="connectedSortable ps-24 pt-24 pe-24 kanban-items"
                                    id="{{ strtolower(str_replace(' ', '-', $status)) }}"
                                    data-status="{{ $status }}">
                                    @foreach ($tasks->where('status', $status) as $task)
                                        <div data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight"
                                            onclick="loadTaskDetails({{ $task->id }})"
                                            class="kanban-item kanban-card bg-neutral-50 p-16 radius-8 mb-24 position-relative"
                                            data-task-id="{{ $task->id }}" data-status="{{ $status }}">
                                            <span
                                                class="fs-7 mb-10 bg-primary-600 badge rounded text-light">#{{ $task->id }}</span>
                                            @if ($userPermissions->contains('can create tasks'))
                                                <small class="w-100 d-block fs-7">
                                                    {{ $task->repeatingTask ? 'Recurring Task' : '' }}
                                                    {{ $task->repeatingTask ? ($task->repeat_task ? ' - Enabled' : ' - Disabled') : '' }}
                                                </small>
                                            @endif
                                            <h6 class="kanban-title text-lg fw-semibold mb-8">{{ $task->title }}</h6>
                                            <p class="kanban-desc text-secondary-light mb-10">{{ $task->description }}
                                            </p>
                                            <span
                                                class="badge text-capitalize bg-danger-surface fs-7 text-dark mb-10 fw-light">{{ $task->status }}</span>
                                            <span
                                                class="badge text-capitalize bg-body fs-7 text-dark mb-10 fw-light">created
                                                by: {{ $task->createdBy->name }}</span>
                                            @if ($userPermissions->contains('can create tasks'))
                                                <span
                                                    class="badge text-capitalize position-absolute top-0 end-0 bg-base fs-7 text-black-50 mb-10 fw-light">assigned
                                                    to:
                                                    {{ $task->assignedTo ? $task->assignedTo->name : 'n/a' }}</span>
                                            @endif
                                            <button type="button"
                                                class="btn btn-sm text-primary-600 rounded bg-hover-primary-600 text-hover-white d-flex align-items-center gap-2 p-1">
                                                <iconify-icon icon="lucide:tag" class="icon"></iconify-icon>
                                                <span class="kanban-tag fw-semibold fs-7">{{ $task->type }} ->
                                                    {{ $task->sub_type }}</span>
                                            </button>
                                            <div class="mt-12 d-flex align-items-center justify-content-between gap-10">
                                                <div class="d-flex align-items-center justify-content-between gap-10">
                                                    <iconify-icon icon="solar:calendar-outline"
                                                        class="text-primary-light"></iconify-icon>
                                                    <span class="start-date text-secondary-light">
                                                        {{ $task->start_date
                                                            ? \Carbon\Carbon::parse($task->start_date)->setTimezone('Asia/Dubai')->format('d-m-Y h:i A')
                                                            : 'Not Set' }}
                                                    </span>

                                                </div>
                                                <div class="d-flex align-items-center justify-content-between gap-10">
                                                    <form action="{{ route('tasks.destroy', $task->id) }}"
                                                        method="POST" style="display: inline-block;"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="card-delete-button text-danger-600">
                                                            <iconify-icon icon="fluent:delete-24-regular"
                                                                class="icon text-lg line-height-1">
                                                            </iconify-icon>
                                                        </button>
                                                    </form>
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
                        @include('tasks.create', ['projects' => $projects])
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
                    $('#taskDetails').html('<p class="text-danger">Error fetching task details.</p>');
                }
            });
        }
    </script>

</x-app-layout>
