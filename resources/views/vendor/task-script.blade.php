<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <span class="offcanvas-title" id="offcanvasRightLabel">Task Detail</span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="taskDetails">
        ...
    </div>
</div>

<!-- Task Attachments Modal -->
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
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <div class="mb-3">
                        <label for="taskAttachments" class="form-label">Select Files</label>
                        <input type="file" name="attachments[]" id="taskAttachments" class="form-control" multiple
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Files</button>
                </form>
                <div id="taskUploadStatus"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="taskCreateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                let newStatus = ui.item.parent().attr(
                    "data-status"); // Get status from parent column

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
    $(document).on("change", ".task-status", function() {
        let taskId = $(this).attr("data-task-id");
        let updateUrl = "{{ route('tasks.updateStatus', ':id') }}".replace(':id', taskId); // Laravel Route Fix
        let newStatus = $(this).val();

        $.ajax({
            url: updateUrl, // Now using the correct Laravel route
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"), // CSRF Token
                status: newStatus,
                task_id: taskId,
            },
            success: function(response) {
                if (response) {
                    toastr.success(response.message);
                } else {
                    toastr.error("Task status can't change!", "Error");
                }
            },
            error: function(xhr) {
                console.error("Error:", xhr);
                toastr.error("Task status can't change!", "Error");
            }
        });
    });
    function loadTaskDetails(taskId) {
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
            },
            error: function() {
                $('#taskDetails').html('<p class="text-danger">Error fetching task details.</p>');
            }
        });
    }
    function loadTaskAttachments() {
        let taskId = "{{ $task->id }}";
        $.ajax({
            url: "{{ route('tasks.attachments.index', ':id') }}".replace(':id', taskId),
            type: "GET",
            success: function(response) {
                renderAttachments(response.attachments);
            }
        });
    }
    function renderAttachments(attachments) {
        let html = '';
        attachments.forEach(file => {
            let filePath = "{{ asset('storage/') }}/" + file.file_path;
            let fileName = file.file_path.slice(-10);
            let extension = file.file_path.split('.').pop();
            let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (imageExtensions.includes(extension)) {
                html += `
        <div class="col-lg-4">
            <div class="overflow-hidden card mb-3 p-3 position-relative text-center shadow d-flex align-items-center justify-content-center flex-column">
                <button type="button" class="m-2 delete-attachment bg-light text-white position-absolute top-0 end-0 rounded-pills rounded p-2 border"
                    data-attachment-id="${file.id}"
                    data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)">
                    ❌
                </button>
                <img src="${filePath}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                <p class="mt-2 mb-2">${fileName}</p>
                <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">View Full</a>
            </div>
        </div>`;
            } else {
                html += `
        <div class="col-lg-4">
            <div class="overflow-hidden card mb-3 p-3 position-relative text-center shadow d-flex align-items-center justify-content-center flex-column">
                <button type="button" class="m-2 delete-attachment bg-light text-white position-absolute top-0 end-0 rounded-pills rounded p-2 border"
                    data-attachment-id="${file.id}"
                    data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)">
                    ❌
                </button>
                <img src="{{ asset('/images/doc.png') }}" width="50" height="50">
                <p class="mt-2 mb-2">${fileName}</p>
                <div>
                <a href="${filePath}" target="_blank" class="btn btn-sm btn-primary">View</a>
                <a href="${filePath}" download class="btn btn-sm btn-success">Download</a>    
                </div>
            </div>
        </div>`;
            }
        });
        html += `
        <div class="col-lg-4">
            <div style="height: 200px !important" class="p-3 d-flex align-items-center justify-content-center shadow card border w-100 h-100">
                <button type="button" class="bg-transparent border-0 shadow-none outline-none" data-bs-toggle="modal"
                    data-bs-target="#taskAttachmentsModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="#0d6efd" class="bi bi-plus-lg" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                </button>    
            </div>
        </div>
        `
        $("#taskAttachmentsContainer").html(html);
    }
    $("#uploadTaskAttachmentsForm").submit(function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        let taskId = $("input[name='task_id']").val();
        let uploadUrl = "{{ route('tasks.attachments.store', ':id') }}".replace(':id', taskId);

        $.ajax({
            url: uploadUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#taskUploadStatus").html("<p class='text-info'>Uploading...</p>");
            },
            success: function(response) {
                if (response.success) {
                    $("#taskUploadStatus").html(
                        "<p class='text-success'>Files uploaded successfully!</p>");
                    loadTaskAttachments();
                    $('#taskAttachmentsModal').modal('hide');
                    toastr.success("Task attachment added!", "Success");
                } else {
                    $("#taskUploadStatus").html("<p class='text-danger'>Error: " +
                        response.message + "</p>");
                }
            },
            error: function(xhr) {
                console.error("Upload Error:", xhr);
                $("#taskUploadStatus").html(
                    "<p class='text-danger'>Something went wrong!</p>");
            }
        });
    });
    $(document).on("click", ".delete-attachment", function() {
        let attachmentId = $(this).attr("data-attachment-id");

        if (confirm("Are you sure you want to delete this attachment?")) {
            $.ajax({
                url: "{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', attachmentId),
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content") // CSRF Token
                },
                success: function(response) {
                    if (response.success) {
                        $("#attachment-" + attachmentId).remove();
                        toastr.info("Task attachment deleted!", "Info");
                        loadTaskAttachments()
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.error("Error:", xhr);
                    alert("Something went wrong!");
                }
            });
        }
    });
    $(document).on("change", ".task-update-field", function() {
        let taskId = $("#task_id").val();
        let fieldName = $(this).attr("name");
        let fieldValue = $(this).val();
        setTimeout(() => {
            $.ajax({
                url: "{{ route('tasks.update.json', ':id') }}".replace(':id', taskId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    field: fieldName,
                    value: fieldValue
                },
                success: function(response) {
                    toastr.success(`${response.message}`, "Success");
                },
                error: function(xhr) {
                    toastr.error("Update failed. Please try again.");
                }
            });
        }, 500);
    });
    $(document).on("click", "#open-create-task-with-status", function() {
        $('#status').val($(this).data('status'));
    })
</script>
