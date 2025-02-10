<x-app-layout>
    <x-slot name="header">
        {{ __('Tasks') }}
    </x-slot>
    <div class="card mb-3 basic-data-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary">
                Create Task
            </a>
            <a href="{{ route('tasks.board.index') }}" class="btn btn-outline-primary">
                Board View
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Created By</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>
                                <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                    aria-controls="offcanvasRight" onclick="loadTaskDetails({{ $task->id }})"
                                    href="javascript:void(0)">{{ $task->title }}</a>
                            </td>
                            <td>{{ $task->project->title }}</td>
                            <td>
                                <select data-task-id="{{ $task->id }}"
                                    class="form-control {{ auth()->user()->hasPermission('can change task status') ? 'task-status' : '' }}"
                                    id="status" name="status">
                                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Todo" {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>
                                        In
                                        Progress</option>
                                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review
                                    </option>
                                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked
                                    </option>
                                </select>
                            </td>
                            <td>{{ $task->department->name }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>{{ $task->assignedTo->name ?? 'N/A' }}</td>
                            <td>{{ $task->createdBy->name ?? 'N/A' }}</td>
                            <td>{{ $task->due_date }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="javascript:void(0)" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                        onclick="loadTaskDetails({{ $task->id }})">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @if ($tasks->count() > 0)
        {{ $tasks->links('vendor.pagination.custom') }}
        @include('vendor.task-script', ['tasks' => $tasks])
    @endif

</x-app-layout>
