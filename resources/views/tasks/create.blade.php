@if ($userPermissions->contains('can create tasks'))
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

        <div class="col-lg-6">
            <label class="form-label" for="type">Type</label>
            <select class="form-control" id="type" name="type">
                <option value="-" disabled selected>Select Type</option>
                <option value="CONTENT">CONTENT</option>
                <option value="ONSITE">ONSITE</option>
                <option value="OFFSITE">OFFSITE</option>
                <option value="DESIGN">DESIGN</option>
                <option value="DEVELOPMENT">DEVELOPMENT</option>
                <option value="AM">AM</option>
                <option value="ADMIN">ADMIN</option>
                <option value="SEO ADMIN">SEO ADMIN</option>
                <option value="SALES">SALES</option>
                <option value="ADHOC">ADHOC</option>
            </select>
        </div>

        <div class="col-lg-6" id="subtype-container" style="display: none;">
            <label class="form-label" for="sub_type">Sub Type</label>
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
                <div id="kw-url-container">
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
                </div>
                <button type="button" id="add-kw-url" class="btn-sm btn btn-primary">+ Add More</button>
                <button type="button" id="save-kw-url" class="btn-sm btn btn-info">Save Links</button>
                <input type="hidden" name="kw_url" id="kw_url">
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
            <input type="date" class="form-control" id="due_date" name="due_date">
        </div>

        <div class="col-lg-6">
            <label class="form-label" for="attachments">Attachments</label>
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
            <small class="form-text text-muted">You can upload multiple files.</small>
        </div>

        <!-- Repeat Task Checkbox -->
        <div class="form-group">
            <label for="repeat_task">Repeat Task</label>
            <input type="checkbox" class="form-check" id="repeat_task" name="repeat_task">
        </div>

        <!-- Repeat Every Dropdown -->
        <div class="form-group" id="repeat_every_group" style="display: none;">
            <label for="repeat_every">Repeat Every</label>
            <select id="repeat_every" name="repeat_every" class="form-control">
                <option value="1">Every Hour</option>
                <option value="24">Every Day</option>
                <option value="168">Every Week</option>
                <option value="720">Every Month</option>
                <option value="8760">Every Year</option>
            </select>
        </div>
        <script>
            $(document).ready(function() {
                $('#repeat_task').change(function() {
                    $('#repeat_every_group').toggle(this.checked);
                });
            });
        </script>

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
            $(document).on('change', '#type', function() {
                let selectedType = $(this).val();
                $('#subtype-container').hide();
                $('#sub_type').empty();
                $('#extra-fields').hide();
                $('.content-fields, .onsite-fields, .offsite-fields, .design-fields, .development-fields, .revision-fields, .adhoc-fields')
                    .hide();
                $('#title').val(selectedType);
                if (selectedType === "ADHOC") {
                    $('#task-title-wrapper').slideDown();
                    $('#taskCreateModalLabel').text("ADHOC TASK");
                } else {
                    $('#task-title-wrapper').slideUp();
                    $('#taskCreateModalLabel').text(`${selectedType} TASK`);
                }
                if (subTypeOptions[selectedType]) {
                    $('#subtype-container').slideDown();
                    $('#sub_type').append('<option value="-" disabled selected>Select Sub Type</option>');
                    subTypeOptions[selectedType].forEach(subType => {
                        $('#sub_type').append(`<option value="${subType}">${subType}</option>`);
                    });
                }
            });
            $(document).on('change', '#sub_type', function() {
                let selectedSubType = $(this).val();
                var TitleValue = $('#title').val();
                var finalTitle = TitleValue + " - " + selectedSubType;
                $('#title').val(finalTitle);
                $('#taskCreateModalLabel').text(finalTitle + " TASK");
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
            $(document).on('click', '#save-kw-url', function() {
                toastr.success('Links Saved');
                let kwUrlArray = [];
                $('.kw-url-input').each(function() {
                    let keyword = $(this).find('.keyword').val();
                    let url = $(this).find('.url').val();
                    if (keyword && url) {
                        kwUrlArray.push({
                            keyword,
                            url
                        });
                    }
                });
                $('#kw_url').val(JSON.stringify(kwUrlArray));
            });
            $(document).on('click', '.remove-kw-url', function() {
                $(this).closest('.kw-url-input').remove();
            });
            $('#department_id').change(function() {
                let departmentName = $("#department_id option:selected").text();
                if (departmentName) {
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
    @else
        @include('vendor.not-allowed')
@endif
