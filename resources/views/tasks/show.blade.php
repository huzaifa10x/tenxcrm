<div class="card">
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-lg-12">
                <label class="form-label" for="title">Title</label>
                <input type="text" class="form-control task-update-field" id="title" name="title"
                    value="{{ $task->title }}" required>
            </div>
            <div class="col-lg-12">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control task-update-field" name="description" id="description" cols="30" rows="10">{{ $task->description }}</textarea>
            </div>

            <input type="hidden" id="task_id" value="{{ $task->id }}">
            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

            <div class="col-lg-6">
                <label class="form-label" for="assigned_to">Assigned To</label>
                <select class="form-control task-update-field" id="assigned_to" name="assigned_to">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="priority">Priority</label>
                <select class="form-control task-update-field" id="priority" name="priority">
                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ $task->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="status">Status</label>
                <select class="form-control task-update-field" id="status" name="status">
                    <option value="Todo" {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                        Progress</option>
                    <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="status">Status</label>
                <select class="form-control task-status" data-task-id="{{ $task->id }}" required name="status">
                    <option value="Todo" {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                        Progress</option>
                    <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="type">Type</label>
                <select class="form-control task-update-field" id="type" name="type">
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
                <input type="date" class="form-control task-update-field" id="due_date" name="due_date"
                    value="{{ $task->due_date }}">
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="department_id">Department</label>
                <select name="department_id" class="form-control task-update-field" id="department_id">
                    <option value="" disabled>Select Department</option>
                    @foreach ($departments as $depart)
                        <option value="{{ $depart->id }}"
                            {{ $task->department_id == $depart->id ? 'selected' : '' }}>{{ $depart->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-12">
                <label class="form-label" for="task_attachments">Current Task Files</label>
                <div class="row" id="taskAttachmentsContainer">
                    <!-- Attachments Will Load Here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>
