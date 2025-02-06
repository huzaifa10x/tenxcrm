<x-app-layout>
    <x-slot name="header">
        {{ __('Create Task') }}
    </x-slot>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-3">
                    <div class="col-lg-12">
                        <label class="form-label" for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label" for="title">Description</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="project_id">Project</label>
                        <select class="form-control" id="project_id" name="project_id" required>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

                    <div class="col-lg-6">
                        <label class="form-label" for="assigned_to">Assigned To</label>
                        <select class="form-control" id="assigned_to" name="assigned_to">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Todo">Todo</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Review">Review</option>
                            <option value="Completed">Completed</option>
                            <option value="Blocked">Blocked</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="type">type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="CONTENT">CONTENT</option>
                            <option value="ONSITE">ONSITE</option>
                            <option value="OFFSITE">OFFSITE</option>
                            <option value="DESIGN">DESIGN</option>
                            <option value="DEVELOPMENT">DEVELOPMENT</option>
                            <option value="MANAGER">MANAGER</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="SALES">SALES</option>
                            <option value="ADHOC">ADHOC</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="due_date">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date">
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="department_id">Department</label>
                        <select name="department_id" class="form-control" id="department_id">
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $depart)
                                <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="attachments">Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="form-text text-muted">You can upload multiple files.</small>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-success mt-3">Create Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
