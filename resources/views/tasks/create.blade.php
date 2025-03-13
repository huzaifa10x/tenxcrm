<div class="row gy-3">
    <div class="col-lg-6">
        <label class="form-label" for="department_id">Department</label>
        <select name="department_id" class="form-control" id="department_id">
            <option value="" disabled selected>Select Department</option>
            @foreach ($departments as $depart)
                <option value="{{ $depart->id }}">{{ $depart->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 assigned_to_wrapper" style="display: none;">
        <label class="form-label" for="assigned_to">Assigned To</label>
        <select class="form-control" id="assigned_to" name="assigned_to">
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <input type="hidden" name="type" id="type">

    <div class="col-lg-6" id="subtype-container" style="display: none;">
        <label class="form-label" for="sub_type">Type</label>
        <select class="form-control" id="sub_type" name="sub_type"></select>
    </div>

    <div id="extra-fields" class="row gy-3 mt-3" style="display: none;">
        <!-- CONTENT Task Fields -->
        <div class="col-lg-6 content-fields">
            <label class="form-label" for="number_of_pages">Number of Pages</label>
            <input type="number" class="form-control" id="number_of_pages" name="number_of_pages">
        </div>

        <!-- ONSITE Task Fields -->
        <div class="col-lg-12 onsite-fields">
        </div>

        <!-- OFFSITE Task Fields -->
        <div class="col-lg-12 offsite-fields">
            <input type="hidden" name="oflinktype" value="starting">
            <div id="keywordFields">
                <div class="mb-10 keyword-group gap-10 d-flex align-items-center justify-content-start">
                    <input class="form-control" type="text" name="keywords[0][keyword]" placeholder="Keyword">
                    <input class="form-control" type="text" name="keywords[0][url]" placeholder="URL">
                    <button type="button"
                        class="remove-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded px-10">
                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                    </button>
                </div>
            </div>
            <button class="btn bnt-sm btn-outline-info-900 addKeyword" type="button">Add
                More</button>
        </div>

        <!-- DESIGN Task Fields -->
        <div class="col-lg-6 design-fields">
            <label class="form-label" for="input_number">Input Number</label>
            <input type="number" class="form-control" id="input_number" name="input_number">
        </div>
    </div>

    <input type="hidden" id="start_date" name="start_date">

    <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

    <div class="col-lg-12 task-title-wrapper" style="display: none">
        <label class="form-label" for="title">Title</label>
        <input type="text" class="form-control" id="createTaskTitle" name="title" required>
    </div>

    <div class="col-lg-12">
        <label class="form-label" for="title">Description</label>
        <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
    </div>

    @if (isset($projects) && $projects->count() > 0)
        <div class="col-lg-6">
            <label class="form-label" for="project_id">Project</label>
            <select class="form-control" id="project_id" name="project_id" required>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                @endforeach
            </select>
        </div>
    @elseif (isset($projectId))
        <input type="hidden" name="project_id" value="{{ $projectId }}">
    @endif

    <div class="col-lg-6">
        <label class="form-label" for="priority">Priority</label>
        <select class="form-control" id="priority" name="priority">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
            <option value="Urgent">Urgent</option>
        </select>
    </div>

    <input type="hidden" value="Todo" name="status">

    <div class="col-lg-6">
        <label class="form-label" for="due_date">Due Date</label>
        <input type="date" class="form-control date-picker" id="due_date" name="due_date">
    </div>

    {{-- <div class="col-lg-6">
        <label class="form-label" for="attachments">Attachments</label>
        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
        <small class="form-text text-muted">You can upload multiple files.</small>
    </div> --}}
    <div class="col-lg-12">
        <label class="form-label" for="contract_copy">Attachments</label>
        <div id="taskAttachmentsDropzone" class="dropzone"></div>
    </div>

    <!-- Repeat Task Checkbox -->
    <div class="form-group">
        <select name="repeat_task" id="repeat_task" class="form-control">
            <option selected value="0">Not a recurring task</option>
            <option value="1">Recurring task</option>
        </select>
    </div>

    <!-- Repeat Every Dropdown -->
    <div class="row" id="repeat_every_group" style="display: none;">
        <div class="form-group">
            <label for="repeat_every">Repeat Every</label>
            <select id="repeat_every" name="repeat_every" class="form-control">
                <option value="1">Every Hour</option>
                <option value="24">Every Day</option>
                <option value="168">Every Week</option>
                <option value="720">Every Month</option>
                <option value="8760">Every Year</option>
            </select>
        </div>
        <div class="form-group">
            <label for="repeat_count">Repeat Count <span class="fs-7">(leave blank if its
                    infinite)</span></label>
            <input type="number" name="repeat_count" class="form-control">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                timeZone: 'Asia/Dubai'
            };
            let now = new Date().toLocaleString('en-US', options);
            let dateTime = new Date(now);
            let year = dateTime.getFullYear();
            let month = String(dateTime.getMonth() + 1).padStart(2, '0'); // Month (0-based index)
            let day = String(dateTime.getDate()).padStart(2, '0');
            let hours = String(dateTime.getHours()).padStart(2, '0');
            let minutes = String(dateTime.getMinutes()).padStart(2, '0');
            let seconds = String(dateTime.getSeconds()).padStart(2, '0');
            let formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            document.getElementById('start_date').value = formattedDateTime;
        });
    </script>

    <script>
        const subTypeOptions = {
            "CONTENT": ["SEO CONTENT", "BLOG CONTENT", "REVISION", "ADHOC"],
            "ONSITE": ["RE-ONSITE", "CHANGES IN CONTENT", "CREATE NEW PAGE", "REPLACE/UPLOAD CONTENT", "ONSITE AUDIT",
                "ADHOC"
            ],
            "OFFSITE": ["MANUAL LINKS", "IH LINKS", "OH LINKS", "ADHOC"],
            "DESIGN": ["LP DESIGN", "BANNERS", "VIDEO", "REVISIONS", "ADHOC"],
            "DEVELOPMENT": ["WEB DESIGN IMPLEMENTATION", "SITE SPEED AND RENDERING", "SEO IMPLEMENTATION",
                "WEBSITE MAINTENANCE", "DEPLOYMENT & HOSTING", "ADHOC"
            ],
            "AM": ["CONTENT APPROVAL", "REQUEST MEETING", "PENDING PAYMENTS", "ADHOC"],
            "SEO ADMIN": ["ADHOC"],
            "ADMIN": ["ADHOC"],
            "SALES": ["ADHOC"]
        };
        $(document).on('change', '#sub_type', function() {
            let selectedSubType = $(this).val();
            var TitleValue = $('#createTaskTitle').val();

            // Check if there's already a hyphen to prevent multiple hyphens
            if (selectedSubType && !TitleValue.includes(" - ")) {
                $('#createTaskTitle').val(TitleValue + " - " + selectedSubType + " TASK");
                $('#taskCreateModalLabel').text(TitleValue + " - " + selectedSubType + " TASK");
            } else if (selectedSubType && TitleValue.includes(" - ")) {
                $('#createTaskTitle').val(TitleValue.split(" - ")[0] + " - " + selectedSubType + " Task");
                $('#taskCreateModalLabel').text(TitleValue.split(" - ")[0] + " - " + selectedSubType + " Task");
            }
            $('#extra-fields').slideDown();
            $('.content-fields, .onsite-fields, .offsite-fields, .design-fields, .development-fields, .revision-fields, .adhoc-fields')
                .hide();

            if (selectedSubType === "SEO CONTENT" || selectedSubType === "BLOG CONTENT") {
                $('.content-fields').slideDown();
            } else if (selectedSubType === "REVISION" || selectedSubType === "ADHOC") {
                $('.revision-fields, .adhoc-fields').slideDown();
            } else if (["RE-ONSITE", "CHANGES IN CONTENT", "CREATE NEW PAGE", "REPLACE/UPLOAD CONTENT",
                    "ONSITE AUDIT"
                ].includes(selectedSubType)) {
                $('.onsite-fields').slideDown();
            } else if (["MANUAL LINKS", "IH LINKS", "OH LINKS"].includes(selectedSubType)) {
                $('.offsite-fields').slideDown();
            } else if (["LP DESIGN", "BANNERS", "VIDEO"].includes(selectedSubType)) {
                $('.design-fields').slideDown();
            }

        });

        $(document).ready(function() {
            let index = 1;

            $(".addKeyword").click(function() {
                $("#keywordFields").append(`
                        <div class="mb-10 keyword-group gap-10 d-flex align-items-center justify-content-start">
                            <input class="form-control" type="text" name="keywords[${index}][keyword]" placeholder="Keyword"
                                required>
                            <input class="form-control" type="text" name="keywords[${index}][url]" placeholder="URL" required>
                            <button type="button"
                                class="remove-btn remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded px-10">
                                <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                            </button>
                        </div>
                    `);
                index++;
            });

            $(document).on("click", ".remove-btn", function() {
                $(this).parent().remove();
            });
        });

        $('#department_id').change(function() {
            let departmentName = $("#department_id option:selected").text();
            if (departmentName) {
                $('#createTaskTitle').val(departmentName);
                if (['CONTENT', 'ONSITE', 'OFFSITE', 'DESIGN', 'DEVELOPMENT', 'AM', 'SEO ADMIN', 'ADMIN', 'SALES',
                        'ADHOC'
                    ].includes(departmentName)) {
                    $('#type').val(departmentName);
                    $('.task-title-wrapper').slideUp();
                    $('#taskCreateModalLabel').text(`${departmentName} TASK`);
                    $('#sub_type').empty();
                } else {
                    $('#subtype-container').slideDown();
                    $('#createTaskTitle').val(departmentName + " - ADHOC TASK");
                    $('#type').val("ADHOC");
                    $('.task-title-wrapper').slideDown();
                    $('#sub_type').empty();
                    $('#sub_type').append('<option value="ADHOC" selected>ADHOC</option>');
                    $('#taskCreateModalLabel').text("ADHOC TASK");
                }

                // $('#subtype-container').hide();
                // $('#sub_type').empty();
                $('#extra-fields').hide();
                $('.content-fields, .onsite-fields, .offsite-fields, .design-fields, .development-fields, .revision-fields, .adhoc-fields')
                    .hide();

                if (subTypeOptions[departmentName]) {
                    $('#subtype-container').slideDown();
                    $('#sub_type').append('<option value="-" disabled selected>Select Sub Type</option>');
                    subTypeOptions[departmentName].forEach(subType => {
                        $('#sub_type').append(`<option value="${subType}">${subType}</option>`);
                    });
                }
                // TYPE WORK
                $.ajax({
                    url: "{{ route('users.by.department', '') }}/" + encodeURIComponent(departmentName),
                    type: "GET",
                    data: {
                        "role": departmentName
                    },
                    success: function(response) {
                        let options = "";
                        response.forEach(user => {
                            options += `<option value="${user.id}">${user.name}</option>`;
                        });
                        $(".assigned_to_wrapper").slideDown();
                        $("#assigned_to").html(options);
                        if (response.length < 1) {
                            $("#assigned_to").html('<option value="">No Users Found</option>');
                        }
                    }
                });
            }
        });
    </script>
