<div class="d-flex justify-content-end mb-10">
    <button type="button" class="btn-sm btn btn-success">Complete Task</button>
</div>
<div class="card">
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

    });
</script>
