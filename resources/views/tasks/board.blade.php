<x-app-layout>
    <x-slot name="header">
        {{ __('Tasks Board') }}
    </x-slot>
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
                                            <h6 class="kanban-title text-lg fw-semibold mb-8">{{ $task->title }}</h6>
                                            <p class="kanban-desc text-secondary-light mb-10">{{ $task->description }}
                                            </p>
                                            <span
                                                class="badge text-capitalize bg-body fs-7 text-dark mb-10 fw-light">created
                                                by: {{ $task->createdBy->name }}</span>
                                            @if ($userPermissions->contains('can create tasks'))
                                                <span
                                                    class="badge text-capitalize position-absolute top-0 end-0 bg-base fs-7 text-dark mb-10 fw-light">assigned
                                                    to: {{ $task->assignedTo->name }}</span>
                                            @endif
                                            <button type="button"
                                                class="btn btn-sm text-primary-600 rounded bg-hover-primary-600 text-hover-white d-flex align-items-center gap-2 p-1">
                                                <iconify-icon icon="lucide:tag" class="icon"></iconify-icon>
                                                <span
                                                    class="kanban-tag fw-semibold fs-7">{{ $task->type }} -> {{ $task->sub_type }}</span>
                                            </button>
                                            <div class="mt-12 d-flex align-items-center justify-content-between gap-10">
                                                <div class="d-flex align-items-center justify-content-between gap-10">
                                                    <iconify-icon icon="solar:calendar-outline"
                                                        class="text-primary-light"></iconify-icon>
                                                    <span
                                                        class="start-date text-secondary-light">{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d-m-Y h:i A') : 'Not Set' }}</span>
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
                                <!-- Add Task Button -->
                                @if ($userPermissions->contains('can create tasks'))
                                    <button type="button"
                                        class="d-flex fs-7 align-items-center gap-2 fw-medium w-100 text-primary-600 justify-content-center text-hover-primary-800 add-task-button"
                                        data-bs-toggle="modal" data-bs-target="#taskCreateModal">
                                        <iconify-icon icon="ph:plus-circle" class="icon text-xl"></iconify-icon>
                                        Add {{ $status }} Task
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    @if ($tasks->count() > 0)
        {{ $tasks->links('vendor.pagination.custom') }}
        @include('vendor.task-script', ['tasks' => $tasks, 'projects' => $projects])
    @endif

</x-app-layout>
