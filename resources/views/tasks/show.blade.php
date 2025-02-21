{{-- COMPLETION FORM --}}
<form id="taskForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="task_id" value="{{ $task->id }}">

    @if ($task->title === 'Handover Meet with The Sales Manager')
        <input type="hidden" name="fields[0][name]" value="handover_meeting">
        <label class="form-label" for="handover_meeting">Have you done Handover Meet with The Sales Manager</label>
        <input id="handover_meeting" class="form-check-input" type="checkbox" required name="fields[0][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title === 'Connect the GA, GSC, GTM, and GMB')
        <span class="d-block fw-bold">Connect the GA, GSC, GTM, and GMB</span>

        <label for="GA" form="label">GA</label>
        <input type="hidden" name="fields[0][name]" value="GA">
        <input type="checkbox" id="GA" class="form-check-input" required name="fields[0][value]">
        <label for="GSC" form="label">GSC</label>
        <input type="hidden" name="fields[1][name]" value="GSC">
        <input type="checkbox" id="GSC" class="form-check-input" required name="fields[1][value]">
        <label for="GTM" form="label">GTM</label>
        <input type="hidden" name="fields[2][name]" value="GTM">
        <input type="checkbox" id="GTM" class="form-check-input" required name="fields[2][value]">
        <label for="GMB" form="label">GMB</label>
        <input type="hidden" name="fields[3][name]" value="GMB">
        <input type="checkbox" id="GMB" class="form-check-input" required name="fields[3][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title === 'Keywords Approval')
        <span class="d-block fw-bold">Keywords Approval</span>

        <label for="seo_admin_approval" form="label">SEO ADMIN APPROVAL</label>
        <input type="hidden" name="fields[0][name]" value="seo_admin_approval">
        <input type="checkbox" class="form-check-input" id="seo_admin_approval" required name="fields[0][value]">

        <label for="client_approval" form="label">CLIENT APPROVAL</label>
        <input type="hidden" name="fields[1][name]" value="client_approval">
        <input type="checkbox" class="form-check-input" id="client_approval" required name="fields[1][value]">

        <button type="submit">Submit</button>
    @endif

    @if ($task->title === 'Intro Call')
        <input type="hidden" name="fields[0][name]" value="intro_call">
        <label class="form-label" for="intro_call">Have you done Intro Call</label>
        <input id="intro_call" class="form-check-input" type="checkbox" required name="fields[0][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title === 'Onboarding Email')
        <input type="hidden" name="fields[0][name]" value="onboarding_email">
        <label class="form-label" for="onboarding_email">Have you done Onboarding Email</label>
        <input id="onboarding_email" class="form-check-input" type="checkbox" required name="fields[0][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title === 'Send SEO Benchmark Report to client')
        <input type="hidden" name="fields[0][name]" value="benchmark_report_to_client">
        <label class="form-label" for="benchmark_report_to_client">Have you set benchmark report to client</label>
        <input id="benchmark_report_to_client" class="form-check-input" type="checkbox" required
            name="fields[0][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'First Client Meeting')
        <label class="form-label">Location</label>
        <input type="text" name="fields[0][name]" value="location" hidden>
        <select name="fields[0][value]">
            <option value="Zoom">Zoom</option>
            <option value="10X Office">10X Office</option>
            <option value="Client Office">Client Office</option>
        </select>
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Upload the Content Intake Form')
        <label class="form-label">Upload the Content Intake Form</label>
        <input type="text" name="fields[0][name]" value="content_intake_form" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Upload the Local Listing Form')
        <label class="form-label">Upload the Local Listing Form</label>
        <input type="text" name="fields[0][name]" value="local_listing_form" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'On-site Audit (from SE ranking)')
        <label class="form-label">On-site Audit (from SE ranking)</label>
        <input type="text" name="fields[0][name]" value="onsite_from_se_ranking" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'PUBLISH CONTENT')
        <label class="form-label" for="publish_content">Have you published content</label>
        <input type="hidden" name="fields[0][name]" value="publish_content">
        <input id="publish_content" class="form-check-input" type="checkbox" required name="fields[0][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Backlink Audit & DISAVOW')
        <label class="form-label">Document of Backlink Audit & DISAVOW</label>
        <input type="text" name="fields[0][name]" value="backlink_audit_and_disavow" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'SEO Content Request')
        <label class="form-label">Upload SEO Content Request Document</label>
        <input type="text" name="fields[0][name]" value="seo_content_request_document" hidden>
        <input type="file" name="file">

        <label class="form-label" for="content_completion_word_count">content completion word count</label>
        <input type="hidden" name="fields[1][name]" value="content_completion_word_count">
        <input id="content_completion_word_count" class="form-control" type="number" required
            name="fields[1][value]">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Content Audit')
        <label class="form-label">Content Audit</label>
        <input type="text" name="fields[0][name]" value="content_audit" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'First On-site')
        <label class="form-label">First On-site Report</label>
        <input type="text" name="fields[0][name]" value="first_onsite_report" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Request Local Listing')
        <label class="form-label">Upload Request Local Listing Doc</label>
        <input type="text" name="fields[0][name]" value="request_local_listing" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Create the Project on SE Ranking')
        <label class="form-label">Attach Benchmark Report</label>
        <input type="text" name="fields[0][name]" value="benchmark_report" hidden>
        <input type="file" name="file">
        <button type="submit">Submit</button>
    @endif

    @if ($task->title == 'Performance Report')
        <label class="form-label">Performance</label>
        <input type="text" name="fields[0][name]" value="Performance" hidden>
        <select name="fields[0][value]">
            <option value="Excellent">Excellent</option>
            <option value="Needs Improvement">Needs Improvement</option>
            <option value="Unsatisfactory">Unsatisfactory</option>
        </select>
        <button type="submit">Submit</button>
    @endif

</form>

@if (
    $task->title == 'Add KW Mapping' ||
        $task->title == 'Schedule R1' ||
        $task->title == 'Schedule R2' ||
        $task->title == 'Schedule R3' ||
        $task->title == 'Request Backlinks')
    <form id="keywordForm">
        @csrf
        <div id="keywordFields">
            <div class="keyword-group">
                <input type="text" name="keywords[0][keyword]" placeholder="Keyword" required>
                <input type="text" name="keywords[0][url]" placeholder="URL" required>
                <button type="button" class="remove-btn">Remove</button>
            </div>
        </div>
        <button type="button" id="addKeyword">Add More</button>
        <button type="submit">Save Keywords</button>
    </form>
@endif

@if ($task->title == 'Add the Client Credentials')
    <form id="clientCredentialsForm">
        @csrf
        <input type="hidden" name="project_id" value="{{ $task->project_id }}">
        <div id="credentialsContainer">
            <div class="credentialRow">
                <input type="url" name="credentials[0][url]" placeholder="Website URL" required>
                <input type="text" name="credentials[0][username]" placeholder="Username/Email" required>
                <input type="password" name="credentials[0][password]" placeholder="Password" required>
                <button type="button" class="removeCredential">Remove</button>
            </div>
        </div>
        <button type="button" id="addCredential">Add More</button>
        <button type="submit">Submit</button>
    </form>
@endif

<div id="responseMessage"></div>

{{-- COMPLETION FORM --}}
<div class="d-flex w-100 justify-content-between align-items-center mb-10">
    <div>
        @if ($userPermissions->contains('can create tasks'))
            @if ($task->repeatingTask && $task->repeat_task)
                <button class="btn btn-sm fs-7 btn-danger disable-repeating"
                    data-task-id="{{ $task->id }}">Disable
                    Repeat</button>
            @endif
            @if ($task->repeatingTask && !$task->repeat_task)
                <button class="btn btn-sm fs-7 btn-success enable-repeating" data-task-id="{{ $task->id }}">Enable
                    Repeat</button>
            @endif
        @endif
    </div>
    @if ($task->status === 'Completed')
        <button type="button" class="btn-sm fs-7 btn btn-neutral-900 complete-task-btn" disabled>Completed</button>
    @else
        <form id="task-complete-form-{{ $task->id }}" action="{{ route('task.markAsCompleted', $task->id) }}"
            method="POST" style="display: inline-block;">
            @csrf
            @method('PATCH')
            <button type="button" class="btn-sm fs-7 btn btn-neutral-900 complete-task-btn"
                data-task-id="{{ $task->id }}">Complete Task</button>
        </form>
    @endif

</div>

<div class="card">
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-lg-12">
                <label class="form-label" for="title">Title</label>
                @if ($task->is_automated)
                    <input type="text" class="form-control" id="title" name="title" readonly
                        value="{{ $task->title }}" required>
                @else
                    <input type="text"
                        class="form-control {{ auth()->user()->hasPermission('can edit task') ? 'task-update-field' : '' }}"
                        id="title" name="title" value="{{ $task->title }}" required>
                @endif
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
                                @if ($userPermissions->contains('can remove offsite links'))
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="startingKwUrlsTable">
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                    </table>

                    @if ($userPermissions->contains('can add offsite links'))
                        <div class="d-flex justify-content-end align-items-center">
                            <button class="btn btn-primary btn-sm open-add-more-links-wrapper">
                                Add New Links
                            </button>
                        </div>
                        <div class="col-lg-12 add-more-links-wrapper" style="display: none">
                            <form id="addOffsiteLinkForm">
                                @csrf

                                <input type="hidden" id="offsite_task_id" name="offsite_task_id"
                                    value="{{ $relatedData->id }}">

                                <div class="col-lg-12 offsite-fields">
                                    <div id="kw-url-container">
                                        <div class="row mb-2 kw-url-input">
                                            <div class="col">
                                                <input type="text" class="form-control keyword"
                                                    placeholder="Keyword">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control url" placeholder="URL">
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-danger remove-kw-url">X</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 mt-20 d-flex align-items-center justify-content-between">
                                        <button type="button" id="add-kw-url" class="btn-sm btn btn-primary">+
                                            Add More</button>
                                        <button type="submit" class="btn-sm btn btn-success">Save Links</button>
                                    </div>
                                    <input type="hidden" name="kw_url" id="kw_url">
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    <p>No Data Found</p>
                @endif
            @endif

            @if ($task->type == 'DESIGN' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
                @if ($relatedData->closing_input)
                    <div class="col-lg-6">
                        <label class="form-label" for="input_number">Number Of Designs</label>
                        <input type="text" class="form-control" name="input_number"
                            value="{{ $relatedData->input_number }}">
                    </div>
                @endif
                @if ($relatedData->closing_input)
                    <div class="col-lg-6">
                        <label class="form-label d-block" for="closing_input">Closing Input</label>
                        <img src="{{ asset('storage/') }}/{{ $relatedData->closing_input }}" class="img-fluid"
                            alt="">
                    </div>
                @endif
            @endif

            @if ($task->type == 'DEVELOPMENT' && $relatedData)
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

            @if ($task->type == 'AM' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
            @endif

            @if ($task->type == 'ADMIN' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
            @endif

            @if ($task->type == 'SALES' && $relatedData)
                <div class="col-lg-6">
                    <label class="form-label" for="sub_type">Sub Type</label>
                    <input type="text" class="form-control" name="sub_type"
                        value="{{ $relatedData->sub_type }}">
                </div>
            @endif

        </div>
    </div>

    {{-- COMMENTS --}}
    <div class="col-lg-12">
        <div class="card h-100 p-0 email-card overflow-x-auto d-block">
            <div class="min-w-450-px d-flex flex-column justify-content-between h-100">
                <div
                    class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center gap-3 justify-content-between flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <button class="text-secondary-light d-flex me-8"><iconify-icon icon="mingcute:arrow-left-line"
                                class="icon fs-3 line-height-1"></iconify-icon></button>
                        <h6 class="mb-0 text-lg">Task Comments</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul id="comment-list" class="list-group mb-3"></ul>
                </div>
                <div class="card-footer py-16 px-24 bg-base shadow-top">
                    <form action="#">
                        <div class="d-flex align-items-center justify-content-between">
                            <textarea id="comment-text" class="textarea-max-height form-control p-0 border-0 py-8 pe-16 resize-none scroll-sm"
                                oninput="adjustHeight(this)" placeholder="Write massage"></textarea>
                            <div class="d-flex align-items-center gap-4 ms-16">
                                <button type="button" id="add-comment"
                                    class="btn btn-primary text-sm btn-sm px-12 py-12 w-100 radius-8 d-flex align-items-center justify-content-center gap-1 h-44-px">
                                    <iconify-icon icon="ion:paper-plane-outline"
                                        class="icon text-lg line-height-1"></iconify-icon>
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        var deleteUrlTemplate = '{{ route('starting_kw_url.delete', ['id' => ':id']) }}';

        function loadStartingKwUrls() {
            $.ajax({
                url: '{{ route('offsite.tasks.starting_kw_urls', ['task_id' => $task->id]) }}',
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
                task_id: "{{ $task->id }}",
                project_id: "{{ $task->project_id }}",
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
</script>
<script>
    $('.complete-task-btn').on('click', function() {
        let button = $(this);
        let taskId = button.data('task-id');
        let form = $(`#task-complete-form-${taskId}`);
        let url = form.attr('action');

        if (!confirm('Are you sure you want to complete this task?')) return;

        $.ajax({
            url: url,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    button.text("Completed")
                        .removeClass("btn-neutral-900")
                        .addClass("btn-success")
                        .prop("disabled", true);
                } else {
                    alert(response.message || "Something went wrong!");
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert("Something went wrong!");
            }
        });
    });
    $(document).ready(function() {
        $("#taskForm").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('tasks.complete') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#responseMessage").html("<p style='color:green;'>" + response
                        .message + "</p>");
                },
                error: function(xhr) {
                    $("#responseMessage").html("<p style='color:red;'>Error: " + xhr
                        .responseText + "</p>");
                }
            });
        });
    });

    $(document).ready(function() {
        let credentialIndex = 1;

        // Add more credentials
        $("#addCredential").click(function() {
            $("#credentialsContainer").append(`
                <div class="credentialRow">
                    <input type="url" name="credentials[${credentialIndex}][url]" placeholder="Website URL" required>
                    <input type="text" name="credentials[${credentialIndex}][username]" placeholder="Username/Email" required>
                    <input type="password" name="credentials[${credentialIndex}][password]" placeholder="Password" required>
                    <button type="button" class="removeCredential">Remove</button>
                </div>
            `);
            credentialIndex++;
        });

        // Remove credential row
        $(document).on("click", ".removeCredential", function() {
            $(this).closest(".credentialRow").remove();
        });

        // Submit form via AJAX
        $("#clientCredentialsForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });

        });
    });
    $(document).ready(function() {
        $("#keywordForm").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append("task_id", "{{ $task->id }}");

            $.ajax({
                url: "{{ route('projects.storeKeywords', $task->project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });
    $(document).ready(function() {
        let index = 1;

        $("#addKeyword").click(function() {
            $("#keywordFields").append(`
        <div class="keyword-group">
            <input type="text" name="keywords[${index}][keyword]" placeholder="Keyword" required>
            <input type="text" name="keywords[${index}][url]" placeholder="URL" required>
            <button type="button" class="remove-btn">Remove</button>
        </div>
    `);
            index++;
        });

        $(document).on("click", ".remove-btn", function() {
            $(this).parent().remove();
        });
    });
</script>
