div<div class="card">
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-lg-12">
                <label class="form-label" for="title">Title</label>
                <input type="text"
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="title" name="title" value="{{ $task->title }}" required>
            </div>
            <div class="col-lg-12">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    name="description" id="description" cols="30" rows="10">{{ $task->description }}</textarea>
            </div>

            <input type="hidden" id="task_id" value="{{ $task->id }}">
            <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

            <div class="col-lg-6">
                <label class="form-label" for="assigned_to">Assigned To</label>
                <select
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="assigned_to" name="assigned_to">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="priority">Priority</label>
                <select
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="priority" name="priority">
                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ $task->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="status">Status</label>
                <select
                    class="form-control {{ auth()->user()->hasPermission('can change task status') ? 'task-status' : '' }}"
                    data-task-id="{{ $task->id }}" required name="status">
                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                        value="Todo" {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                        value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                        Progress</option>
                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                        value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                        value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed
                    </option>
                    <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                        value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="type">Type</label>
                <select
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="type" name="type">
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
                <input type="date"
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="due_date" name="due_date" value="{{ $task->due_date }}">
            </div>

            <div class="col-lg-6">
                <label class="form-label" for="department_id">Department</label>
                <select name="department_id"
                    class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                    id="department_id">
                    <option value="" disabled>Select Department</option>
                    @foreach ($departments as $depart)
                        <option value="{{ $depart->id }}"
                            {{ $task->department_id == $depart->id ? 'selected' : '' }}>{{ $depart->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-12">
                <div class="row" id="taskAttachmentsContainer">
                    <label class="form-label" for="task_attachments">Current Task Files</label>
                    <!-- Attachments Will Load Here via AJAX -->
                </div>
            </div>

            @if ($task->type == 'CONTENT' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text"
                        class="form-control  {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                        name="sub_type" value="{{ $relatedData->sub_type }}">
                </div>
                @if ($relatedData->number_of_pages)
                    <div class="col-lg-6">
                        <label class="form-label" for="number_of_pages">Number Of Pages</label>
                        <input type="text"
                            class="form-control  {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                            name="number_of_pages" value="{{ $relatedData->number_of_pages }}">
                    </div>
                @endif
                @if ($relatedData->no_of_words)
                    <div class="col-lg-6">
                        <label class="form-label" for="no_of_words">Number Of Words</label>
                        <input type="text"
                            class="form-control  {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                            name="no_of_words" value="{{ $relatedData->no_of_words }}">
                    </div>
                @endif
                @if ($relatedData->notes)
                    <div class="col-lg-6">
                        <label class="form-label" for="notes">Note</label>
                        <textarea class="{{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }} form-control"
                            name="notes" id="notes" cols="30" rows="10">{{ $relatedData->notes }}</textarea>
                    </div>
                @endif
            @endif

            @if ($task->type == 'ONSITE' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
                @if ($relatedData->closing_input)
                    <div class="col-lg-6">
                        <label class="form-label d-block" for="closing_input">Closing Input</label>
                        <img src="{{ asset('storage/') }}/{{ $relatedData->closing_input }}" class="img-fluid"
                            alt="">
                    </div>
                @endif
            @endif


            @if ($task->type == 'OFFSITE' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
                @if ($relatedData && $relatedData->startingKwUrls->count())
                    <span class="d-block text-dark p-0">Assigned Keywords & Urls</span>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>URL</th>
                                <th>Domain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relatedData->startingKwUrls as $kwUrl)
                                <tr>
                                    <td>{{ $kwUrl->keyword }}</td>
                                    <td><a href="{{ $kwUrl->url }}" target="_blank">{{ $kwUrl->url }}</a></td>
                                    <td>{{ $kwUrl->domain }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No Data Found</p>
                @endif
            @endif

            <div class="col-lg-6">

                @if ($task->type == 'DESIGN' && $relatedData)
                    <label class="form-label" for="design_type">Design Type</label>
                    <input type="text" class="form-control" name="design_type"
                        value="{{ $relatedData->design_type }}">
                @endif

                @if ($task->type == 'DEVELOPMENT' && $relatedData)
                    <label class="form-label" for="feature_list">Feature List</label>
                    <textarea class="form-control" name="feature_list">{{ $relatedData->feature_list }}</textarea>
                @endif

                @if ($task->type == 'AM' && $relatedData)
                    <label class="form-label" for="team_size">Team Size</label>
                    <input type="number" class="form-control" name="team_size"
                        value="{{ $relatedData->team_size }}">
                @endif

                @if ($task->type == 'ADMIN' && $relatedData)
                    <label class="form-label" for="admin_notes">Admin Notes</label>
                    <textarea class="form-control" name="admin_notes">{{ $relatedData->admin_notes }}</textarea>
                @endif

                @if ($task->type == 'SALES' && $relatedData)
                    <label class="form-label" for="lead_source">Lead Source</label>
                    <input type="text" class="form-control" name="lead_source"
                        value="{{ $relatedData->lead_source }}">
                @endif

                @if ($task->type == 'ADHOC' && $relatedData)
                    <label class="form-label" for="task_details">Task Details</label>
                    <textarea class="form-control" name="task_details">{{ $relatedData->task_details }}</textarea>
                @endif

            </div>

        </div>
    </div>
</div>
