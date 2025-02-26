<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Task') }}
    </x-slot>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row gy-3">
                    <div class="col-lg-12">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $task->title }}" required>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ $task->description }}</textarea>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="project_id">Project</label>
                        <select class="form-control" id="project_id" name="project_id" required>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

                    <div class="col-lg-6">
                        <label class="form-label" for="assigned_to">Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $task->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ $task->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Todo" {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                            <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                            <option value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="CONTENT" {{ $task->type == 'CONTENT' ? 'selected' : '' }}>CONTENT</option>
                            <option value="ONSITE" {{ $task->type == 'ONSITE' ? 'selected' : '' }}>ONSITE</option>
                            <option value="OFFSITE" {{ $task->type == 'OFFSITE' ? 'selected' : '' }}>OFFSITE</option>
                            <option value="DESIGN" {{ $task->type == 'DESIGN' ? 'selected' : '' }}>DESIGN</option>
                            <option value="DEVELOPMENT" {{ $task->type == 'DEVELOPMENT' ? 'selected' : '' }}>
                                DEVELOPMENT</option>
                            <option value="MANAGER" {{ $task->type == 'MANAGER' ? 'selected' : '' }}>MANAGER</option>
                            <option value="ADMIN" {{ $task->type == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                            <option value="SALES" {{ $task->type == 'SALES' ? 'selected' : '' }}>SALES</option>
                            <option value="ADHOC" {{ $task->type == 'ADHOC' ? 'selected' : '' }}>ADHOC</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="due_date">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date"
                            value="{{ $task->due_date }}">
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="department_id">Department</label>
                        <select name="department_id" class="form-control" id="department_id">
                            <option value="" disabled>Select Department</option>
                            @foreach ($departments as $depart)
                                <option value="{{ $depart->id }}"
                                    {{ $task->department_id == $depart->id ? 'selected' : '' }}>{{ $depart->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="attachments">Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">You can upload multiple files.</small>
                    </div>

                    <div class="col-lg-12">
                        @if (!empty($task->attachments) && count($task->attachments) > 0)
                            <label class="form-label" for="contract_copy">Current Contract Files</label>
                            <div class="row">
                                @foreach ($task->attachments as $file)
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
                                                <button type="button" data-attachment-id="{{ $attachment->id }}"
                                                    data-delete-url="{{ route('tasks.attachments.destroy', $attachment->id) }}"
                                                    class="delete-attachment remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center position-absolute top-0 end-0">
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
                            <button type="button" class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                                data-bs-target="#projectAttachmentsModal">
                                Add
                                {{ !empty($task->attachments) && count($task->attachments) ? 'More' : '' }}
                                Task Files
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success mt-3">Update Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
