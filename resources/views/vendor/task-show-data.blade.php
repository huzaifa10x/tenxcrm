<div class="completion-anim-wrapper">
    <svg id="completion" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 101">
        <style>
            .st0 {
                fill: #51BBA0;
                fill-opacity: 0.4;
            }

            .st1 {
                fill: #51BBA0;
                fill-opacity: 0.1;
            }

            .st2 {
                fill: #51BBA0;
            }
        </style>
        <title>
            C1DBEBC0-CF7A-42D3-B615-1AB5DE73C3E9
        </title>
        <g id="configurator">
            <g id="configurator_completion">
                <g id="stars">
                    <circle id="Oval" class="st0" cx="14" cy="18" r="1" />
                    <circle id="Oval-Copy-4" class="st0" cx="27" cy="20" r="1" />
                    <circle id="Oval-Copy-10" class="st0" cx="76" cy="20" r="1" />
                    <circle id="Oval-Copy-2" class="st0" cx="61.5" cy="12.5" r="1.5" />
                    <circle id="Oval-Copy-9" class="st0" cx="94" cy="53" r="1" />
                    <circle id="Oval-Copy-6" class="st0" cx="88" cy="14" r="1" />
                    <circle id="Oval-Copy-7" class="st0" cx="59" cy="1" r="1" />
                    <circle id="Oval_1_" class="st0" cx="43" cy="9" r="2" />
                    <path id="ster-01" class="st0" d="M28.5 3.8L26 6l2.2-2.5L26 1l2.5 2.2L31 1l-2.2 2.5L31 6z" />
                    <path id="ster-01" class="st0"
                        d="M3.5 50.9l-2.1 2.4 1.7-2.7-2.9-1.2 3.1.8.2-3.2.2 3.2 3.1-.8-2.9 1.2 1.7 2.7z" />
                    <path id="ster-01" class="st0"
                        d="M93.5 27.8L91 30l2.2-2.5L91 25l2.5 2.2L96 25l-2.2 2.5L96 30z" />
                    <circle id="Oval-Copy-5" class="st0" cx="91" cy="40" r="2" />
                    <circle id="Oval-Copy-3" class="st0" cx="7" cy="36" r="2" />
                    <circle id="Oval-Copy-8" class="st0" cx="7.5" cy="5.5" r=".5" />
                </g>
            </g>
        </g>
        <g id="cirkel">
            <g id="Mask">
                <path id="path-1_1_" class="st1"
                    d="M49 21c22.1 0 40 17.9 40 40s-17.9 40-40 40S9 83.1 9 61s17.9-40 40-40z" />
            </g>
        </g>
        <path id="check" class="st2"
            d="M31.3 64.3c-1.2-1.5-3.4-1.9-4.9-.7-1.5 1.2-1.9 3.4-.7 4.9l7.8 10.4c1.3 1.7 3.8 1.9 5.3.4L71.1 47c1.4-1.4 1.4-3.6 0-5s-3.6-1.4-5 0L36.7 71.5l-5.4-7.2z" />
    </svg>
</div>
<div class="completion-form-wrapper mb-10" style="display: none;">
    {{-- COMPLETION FORM --}}
    <div class="card border border-secondary">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    @if ($task->title === 'Handover Meet with The Sales Manager')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="handover_meeting">
                            <label class="form-label" for="handover_meeting">Have you done Handover Meet with The
                                Sales
                                Manager</label>
                            <input id="handover_meeting" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title === 'Keywords Approval')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <span class="d-block fw-bold mb-2">Keyword Approval Process</span>
                            <div class="mt-3">
                                <label for="seo_admin_approval" class="form-label">SEO Admin Approval
                                    (Completed)</label>
                                <input required type="hidden" name="fields[0][name]" value="seo_admin_approval">
                                <input type="checkbox" class="form-check-input" id="seo_admin_approval" required
                                    name="fields[0][value]">
                            </div>
                            <div class="mb-3">
                                <label for="client_approval" class="form-label">Client Approval (Completed)</label>
                                <input required type="hidden" name="fields[1][name]" value="client_approval">
                                <input type="checkbox" class="form-check-input" id="client_approval" required
                                    name="fields[1][value]">
                            </div>
                            <button class="btn btn-sm btn-dark mt-3" type="submit">Mark Task as Complete</button>
                        </form>
                    @elseif ($task->title === 'Intro Call')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="intro_call">
                            <label class="form-label" for="intro_call">Have you done Intro Call</label>
                            <input id="intro_call" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title === 'Onboarding Email')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="onboarding_email">
                            <label class="form-label" for="onboarding_email">Have you sent Onboarding
                                Email</label>
                            <input id="onboarding_email" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title === 'Send SEO Benchmark Report to client')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="benchmark_report_to_client">
                            <label class="form-label" for="benchmark_report_to_client">Have you set benchmark
                                report
                                to
                                client</label>
                            <input id="benchmark_report_to_client" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'First Client Meeting')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Location</label>
                            <input type="text" name="fields[0][name]" value="location" hidden>
                            <select class="form-control" name="fields[0][value]">
                                <option value="Zoom">Zoom</option>
                                <option value="10X Office">10X Office</option>
                                <option value="Client Office">Client Office</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Upload the Content Intake Form')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Upload the Content Intake Form</label>
                            <input type="text" name="fields[0][name]" value="content_intake_form" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Upload the Local Listing Form')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Upload the Local Listing Form</label>
                            <input type="text" name="fields[0][name]" value="local_listing_form" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'On-site Audit (from SE ranking)')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">On-site Audit (from SE ranking)</label>
                            <input type="text" name="fields[0][name]" value="onsite_from_se_ranking" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'PUBLISH CONTENT')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label" for="publish_content">Have you published content</label>
                            <input type="hidden" name="fields[0][name]" value="publish_content">
                            <input id="publish_content" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Backlink Audit & DISAVOW')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Document of Backlink Audit</label>
                            <input type="text" name="fields[0][name]" value="backlink_audit" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <label class="form-label">Document of DISAVOW (optional)</label>
                            <input type="text" name="fields[1][name]" value="disavow" hidden>
                            <input type="file" name="fields[1][value]" class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($relatedData && in_array($relatedData->sub_type, ['SEO CONTENT', 'BLOG CONTENT']))
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Number Of Words</label>
                            <input type="text" name="fields[0][name]" value="number_of_words" hidden>
                            <input class="form-control" type="number" name="fields[0][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif (
                        $relatedData &&
                            in_array($relatedData->sub_type, [
                                'RE-ONSITE',
                                'CHANGES IN CONTENT',
                                'CREATE NEW PAGE',
                                'REPLACE/UPLOAD CONTENT',
                                'ONSITE AUDIT',
                                'LP DESIGN',
                                'BANNERS',
                                'VIDEO',
                                'REVISIONS',
                                'SITE SPEED AND RENDERING',
                            ]))
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Attachment</label>
                            <input type="text" name="fields[0][name]" value="attachment" hidden>
                            <input type="file" name="fields[][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'SEO Content Request')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Upload SEO Content Request Document</label>
                            <input type="text" name="fields[0][name]" value="seo_content_request_document" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <label class="form-label" for="content_completion_word_count">content completion
                                word
                                count</label>
                            <input type="hidden" name="fields[1][name]" value="content_completion_word_count">
                            <input id="content_completion_word_count" class="form-control" type="number" required
                                name="fields[1][value]">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Content Audit')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Content Audit</label>
                            <input type="text" name="fields[0][name]" value="content_audit" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'First On-site')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">First On-site Report</label>
                            <input type="text" name="fields[0][name]" value="first_onsite_report" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Request Local Listing')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Upload Request Local Listing Doc</label>
                            <input type="text" name="fields[0][name]" value="request_local_listing" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Create the Project on SE Ranking')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Attach Benchmark Report</label>
                            <input type="text" name="fields[0][name]" value="benchmark_report" hidden>
                            <input type="file" name="fields[0][value]" required class="form-control">
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Performance Report')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Performance</label>
                            <input type="text" name="fields[0][name]" value="performance" hidden>
                            <select name="fields[0][value]" class="form-control">
                                <option value="Excellent">Excellent</option>
                                <option value="Needs Improvement">Needs Improvement</option>
                                <option value="Unsatisfactory">Unsatisfactory</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Campaign Meeting (Deep-dive) with Tech')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">MINUTES OF MEETINGS</label>
                            <input type="text" name="fields[1][name]" value="minutes_of_meeting" hidden>
                            <textarea name="fields[1][value]" class="form-control" cols="30" rows="10"></textarea>
                            <label class="form-label">Status</label>
                            <input type="hidden" name="fields[0][name]" value="expectations" hidden>
                            <select name="fields[0][value]" class="form-control">
                                <option value="Exceeds Expectations">Exceeds Expectations</option>
                                <option value="Meets Expectations">Meets Expectations</option>
                                <option value="Below Expectations">Below Expectations</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Campaign Meeting (Deep-dive) with Client')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">MINUTES OF MEETINGS</label>
                            <input type="text" name="fields[1][name]" value="minutes_of_meeting" hidden>
                            <textarea name="fields[1][value]" class="form-control" cols="30" rows="10"></textarea>
                            <label class="form-label">Status</label>
                            <input type="hidden" name="fields[0][name]" value="status" hidden>
                            <select name="fields[0][value]" class="form-control">
                                <option value="Very Satisfied">Very Satisfied</option>
                                <option value="Neutral">Neutral</option>
                                <option value="Dissatisfied">Dissatisfied</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Renewal Reminder')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="renewal_reminder_status">
                            <label class="form-label" for="renewal_reminder_status">Have you sent Renewal
                                Reminder</label>
                            <input id="renewal_reminder_status" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <label class="form-label w-100">Feeling</label>
                            <input type="hidden" name="fields[1][name]" value="feeling" hidden>
                            <select name="fields[1][value]" class="form-control">
                                <option value="Confident">Confident</option>
                                <option value="Unsure">Unsure</option>
                                <option value="No Renewal">No Renewal</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Renewal Contract')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <input type="hidden" name="fields[0][name]" value="renewal_contract_status">
                            <label class="form-label" for="renewal_contract_status">Have you sent
                                Follow-Up</label>
                            <input id="renewal_contract_status" class="form-check-input" type="checkbox" required
                                name="fields[0][value]">
                            <label class="form-label w-100">Feeling</label>
                            <input type="hidden" name="fields[1][name]" value="feeling" hidden>
                            <select name="fields[1][value]" class="form-control">
                                <option value="Confirmed">Confirmed</option>
                                <option value="Awaiting Response">Awaiting Response</option>
                                <option value="Unlikely">Unlikely</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Renewal Status')
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Renewal Status</label>
                            <input type="hidden" name="fields[0][name]" value="status" hidden>
                            <select name="fields[0][value]" class="form-control">
                                <option value="Pending">Pending</option>
                                <option value="Expired">Expired</option>
                            </select>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif (
                        $task->title == 'Request Backlinks' ||
                            ($relatedData && in_array($relatedData->sub_type, ['MANUAL LINKS', 'IH LINKS', 'OH LINKS'])))
                        <form id="taskForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <label class="form-label">Notes</label>
                            <input type="text" name="fields[0][name]" value="notes" hidden>
                            <textarea class="form-control" name="fields[0][value]" cols="30" rows="10" required
                                placeholder="Enter Notes"></textarea>
                            <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                Task</button>
                        </form>
                    @elseif ($task->title == 'Add the Client Credentials')
                        <form id="clientCredentialsForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <div id="credentialsContainer">
                                <div class="credentialRow row">
                                    <div class="col-lg-3">
                                        <input required class="form-control" type="text"
                                            name="credentials[0][platform_name]" placeholder="Platform Name">
                                    </div>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text" name="credentials[0][url]"
                                            placeholder="Website URL">
                                    </div>
                                    <div class="col-lg-3">
                                        <input required class="form-control" type="text"
                                            name="credentials[0][username]" placeholder="Username/Email">
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center gap-3">
                                        <input class="form-control" type="password" name="credentials[0][password]"
                                            placeholder="Password">
                                        <button type="button"
                                            class="removeCredential btn fs-7 btn-danger btn-sm">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start gap-10 mt-10">
                                <button class="btn btn-sm fs-7 btn-neutral-500" type="button" id="addCredential">Add
                                    More</button>
                                <button class="btn btn-sm fs-7 btn-neutral-900" type="submit">Submit</button>
                            </div>
                        </form>
                    @elseif($task->title == 'Add KW Mapping')
                        <form id="keywordForm3" class="mt-4">
                            @csrf
                            <input type="hidden" name="type" value="closing">

                            <div id="urlGroups">
                                <div class="url-group card shadow-sm border-0 mb-3 border">
                                    <div class="card-body bg-light-subtle">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">URL</label>
                                            <input type="url" class="form-control url-input"
                                                placeholder="https://example.com" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Keywords <small class="text-muted">(One
                                                    per line)</small></label>
                                            <textarea class="form-control keyword-textarea" rows="4" placeholder="Enter keywords, one per line..."
                                                required></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove-url-group">
                                                <i class="bi bi-trash me-1"></i> Remove URL Group
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
                                <button type="button" class="btn btn-outline-primary" id="addUrlGroup">
                                    <i class="bi bi-plus-circle me-1"></i> Add Another URL Group
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Submit Keywords
                                </button>
                            </div>
                        </form>
                    @elseif ($task->title === 'Connect the GA, GSC, GTM, and GMB')
                        <form class="ga-gmb-form" enctype="multipart/form-data" style="display: none">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <span class="d-block fw-bold">Connect the GA, GSC, GTM, and GMB</span>
                            <label for="GA" form="label">GA</label>
                            <input type="hidden" name="fields[0][name]" value="GA">
                            <input type="checkbox" checked id="GA" class="form-check-input" required
                                name="fields[0][value]">
                            <label for="GSC" form="label">GSC</label>
                            <input type="hidden" name="fields[1][name]" value="GSC">
                            <input type="checkbox" checked id="GSC" class="form-check-input" required
                                name="fields[1][value]">
                            <label for="GTM" form="label">GTM</label>
                            <input type="hidden" name="fields[2][name]" value="GTM">
                            <input type="checkbox" checked id="GTM" class="form-check-input" required
                                name="fields[2][value]">
                            <label for="GMB" form="label">GMB</label>
                            <input type="hidden" name="fields[3][name]" value="GMB">
                            <input type="checkbox" checked id="GMB" class="form-check-input" required
                                name="fields[3][value]">
                        </form>
                        <form id="clientCredentialsForm2">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <div id="credentialsContainer">
                                <div class="credentialRow row mb-10">
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text"
                                            name="credentials[0][platform_name]" value="GA" readonly
                                            placeholder="GA Credential">
                                    </div>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="email" name="credentials[0][username]"
                                            placeholder="Email*" required>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center gap-3">
                                        <input class="form-control" type="password" name="credentials[0][password]"
                                            placeholder="Password">
                                    </div>
                                </div>
                                <div class="credentialRow row mb-10">
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text"
                                            name="credentials[1][platform_name]" value="GSC" readonly
                                            placeholder="GSC Credential">
                                    </div>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="email" name="credentials[1][username]"
                                            placeholder="Email*" required>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center gap-3">
                                        <input class="form-control" type="password" name="credentials[1][password]"
                                            placeholder="Password">
                                    </div>
                                </div>
                                <div class="credentialRow row mb-10">
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text"
                                            name="credentials[2][platform_name]" value="GTM" readonly
                                            placeholder="GTM Credential">
                                    </div>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="email" name="credentials[2][username]"
                                            placeholder="Email*" required>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center gap-3">
                                        <input class="form-control" type="password" name="credentials[2][password]"
                                            placeholder="Password">
                                    </div>
                                </div>
                                <div class="credentialRow row mb-10">
                                    <div class="col-lg-3">
                                        <input class="form-control" type="text"
                                            name="credentials[3][platform_name]" value="GMB" readonly
                                            placeholder="GMB Credential">
                                    </div>
                                    <div class="col-lg-3">
                                        <input class="form-control" type="email" name="credentials[3][username]"
                                            placeholder="Email*" required>
                                    </div>
                                    <div class="col-lg-3 d-flex align-items-center gap-3">
                                        <input class="form-control" type="password" name="credentials[3][password]"
                                            placeholder="Password">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start gap-10 mt-10">
                                <button class="btn fs-7 btn-neutral-900" type="submit">Submit</button>
                            </div>
                        </form>
                    @elseif($task->title == 'OFFSITE - PARTNER LINKS TASK')
                        <form id="partnerLinkForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            <input type="hidden" name="task_id" value="{{ $task->id }}">

                            <div id="link-sections">
                                <div class="link-group border p-3 mb-3">
                                    <div class="mb-2">
                                        <label>Keyword</label>
                                        <input type="text" name="links[0][keyword]" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label>URL</label>
                                        <input type="url" name="links[0][url]" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <label>Backlink</label>
                                        <input type="url" name="links[0][backlink]" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-2">
                                        <label>Amount</label>
                                        <input type="number" step="0.01" name="links[0][amount]"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="addMore" class="btn btn-secondary mb-3">+ Add More</button>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit All</button>
                        </form>
                    @else
                        <div class="col-lg-12">
                            <form id="taskForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <label class="form-label">Notes</label>
                                <input type="text" name="fields[0][name]" value="notes" hidden>
                                <textarea class="form-control" name="fields[0][value]" cols="30" rows="10" required
                                    placeholder="Enter Notes"></textarea>
                                <button class="btn btn-sm d-block btn-neutral-900 mt-10" type="submit">Complete
                                    Task</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="responseMessage"></div>
</div>
<div class="d-flex mb-10 align-items-end gap-10">
    <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
        <label class="form-label fs-7 m-0" for="project">Project</label>
        @if (($task->createdBy && $task->createdBy->id == auth()->user()->id) || auth()->user()->hasRole('ADMIN'))
            <div class="d-flex align-items-start gap-10">
                <select class="form-control" name="project_id" onchange="updateTaskField(this)">
                    @foreach ($projects as $project)
                        <option {{ $task->project_id == $project->id ? 'selected' : '' }}
                            value="{{ $project->id }}">
                            {{ $project->title }}</option>
                    @endforeach
                </select>
                <a href="{{ route('projects.show', $task->project->id) }}"
                    class="btn btn-success btn-sm fs-7"><iconify-icon icon="majesticons:eye-line"
                        class="icon text-xl"></iconify-icon></a>
                @if ($task->repeatingTask)
                    <span class="fs-7 badge btn btn-danger rounded mt-1">Repeating
                        Every {{ $task->repeatingTask->count }}
                        {{ $task->repeatingTask->repeat_every }}</span>
                @endif
            </div>
        @else
            <a href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }} -
                {{ $task->project->companies->name ?? 'N/A' }}</a>
        @endif
    </div>
    @if ($task->contentTask && $task->contentTask->number_of_pages)
        <div class="col-lg-6">
            <label class="form-label fs-7 m-0" for="number_of_pages">Number Of Pages</label>
            <input type="text" class="form-control w-auto fs-7" readonly name="number_of_pages"
                value="{{ $task->contentTask->number_of_pages }}">
        </div>
    @endif
    @if ($task->type == 'DESIGN' && $relatedData)
        <div class="col-lg-6">
            <label class="form-label fs-7 m-0" for="input_number">Number Of Designs</label>
            <input type="text" class="form-control w-auto fs-7" readonly name="input_number"
                value="{{ $relatedData->input_number }}">
        </div>
    @endif
    <button onclick="taskLinkCopy('{{ route('tasks.show', $task->id) }}')"
        class="btn btn-outline-primary fs-7 d-inline-flex align-items-center gap-1">
        <i class="bi bi-share-fill"></i> Share
    </button>
    <a href="{{ route('tasks.show', $task->id) }}"
        class="btn btn-outline-primary fs-7 d-inline-flex align-items-center gap-1">
        Open
    </a>
</div>
<div class="card {{ $task->status == 'Completed' ? 'bg-success-200' : '' }} border border-secondary mb-10">
    <div class="card-body gap-10 d-flex flex-wrap align-items-end">
        @if (auth()->user()->hasRole('ADMIN') ||
                ($task->createdBy && $task->createdBy->id == auth()->user()->id) ||
                auth()->user()->hasPermission('can edit task'))
            <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
                <label class="form-label fs-7 m-0" for="priority">Priority</label>
                <select class="form-control fs-7 h-auto" id="priority" name="priority"
                    onchange="updateTaskField(this)">
                    <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ $task->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
                <label class="form-label fs-7 m-0" for="assigned_to">Assignee</label>
                <select onchange="updateTaskField(this)" class="form-control fs-7 h-auto" name="assigned_to"
                    id="assigned_to">
                    @foreach ($users as $user)
                        @if (!$task->assignedTo)
                            <option disabled value="-" selected>System</option>
                        @endif
                        <option {{ $task->assignedTo && $user->id == $task->assignedTo->id ? 'selected' : '' }}
                            value="{{ $user->id }}">
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
                <label class="form-label fs-7 m-0" for="sub_type">Priority</label>
                <input readonly type="text" class="form-control fs-7 h-auto" value="{{ $task->priority }}">
            </div>
            <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
                <label class="form-label fs-7 m-0" for="assigned_to">Assignee</label>
                <input readonly type="text" class="form-control fs-7 h-auto"
                    value="{{ $task->assignedTo->name ?? '' }}">
            </div>
        @endif
        @if ($task->department_id)
            <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
                <label class="form-label fs-7 m-0" for="department">Department</label>
                <input readonly type="text" class="form-control fs-7 h-auto"
                    value="{{ $task->department->name }}">
            </div>
        @endif
        <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
            <label class="form-label fs-7 m-0" for="sub_type">Created by</label>
            <input readonly type="text" class="form-control fs-7 h-auto"
                value="{{ $task->createdBy->name ?? 'System or user deleted' }}">
        </div>
        <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
            <label class="form-label fs-7 m-0" for="status">Status</label>
            <select class="form-control fs-7 task-status h-auto" onchange="handleTaskStatusChange(this)"
                data-task-id="{{ $task->id }}" required name="status">
                <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Todo"
                    {{ $task->status == 'Todo' ? 'selected' : '' }}>Todo</option>
                <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                    value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                    Progress</option>
                <option disabled {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                    value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }} value="Review"
                    {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                <option {{ auth()->user()->hasPermission('can change task status') ? '' : 'disabled' }}
                    value="Blocked" {{ $task->status == 'Blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </div>

        <div class="d-flex flex-column align-items-baseline gap-1" style="white-space: nowrap;">
            @if ($relatedData)
                <label class="form-label fs-7 m-0" for="sub_type">Type</label>
                <input readonly type="text" class="form-control fs-7 h-auto"
                    value="{{ $task->type }} -> {{ $relatedData->sub_type }}">
            @else
                <label class="form-label fs-7 m-0" for="sub_type">Type</label>
                <input readonly type="text" class="form-control fs-7 h-auto" value="{{ $task->type }}">
            @endif
        </div>
        @if ($task->status === 'Completed')
            <button type="button" class="btn-sm fs-7 btn btn-secondary text-light-800" disabled>Completed</button>
        @else
            <form id="task-complete-form-{{ $task->id }}"
                action="{{ route('task.markAsCompleted', $task->id) }}" method="POST"
                style="display: inline-block;">
                @csrf
                @method('PATCH')
                <button type="button" class="btn-sm fs-7 btn btn-success  complete-task-btn"
                    data-task-id="{{ $task->id }}">Complete Task</button>
            </form>
        @endif
        @if ($userPermissions->contains('can create tasks') && auth()->user()->id == $task->created_by)
            @if ($task->repeatingTask && $task->repeat_task)
                <button class="btn btn-sm fs-7 btn-danger disable-repeating"
                    data-task-id="{{ $task->id }}">Disable
                    Repeat</button>
            @endif
            @if ($task->repeatingTask && !$task->repeat_task)
                <button class="btn btn-sm fs-7 btn-success enable-repeating"
                    data-task-id="{{ $task->id }}">Enable
                    Repeat</button>
            @endif
        @endif
        @if ($task->parent_task_id)
            <a href="{{ route('tasks.show', $task->parent_task_id) }}" class="btn btn-sm fs-7 btn-success">Parent
                Task</a>
        @endif
    </div>
</div>
<div class="row gy-3 mb-10">

    @if ($userPermissions->contains('can add offsite links'))
        @if (in_array($task->title, [
                'Request for R1',
                'Request for R2',
                'Request for R3',
                'Schedule R1',
                'Schedule R2',
                'Schedule R3',
                'Request Backlinks',
            ]) ||
                ($relatedData && in_array($relatedData->sub_type, ['MANUAL LINKS', 'IH LINKS', 'OH LINKS'])))
            <div class="d-flex justify-content-end align-items-center">
                <button class="btn btn-primary btn-sm open-add-more-links-wrapper">
                    Add kw/urls
                </button>
            </div>
        @endif
        <div class="col-lg-12 add-more-links-wrapper" style="display: none">
            <form id="keywordForm2">
                @csrf
                <input type="text" class="form-control mb-3" id="mainKeyword" placeholder="Enter Keyword"
                    required>
                <input type="hidden" name="type" value="closing">
                <textarea class="form-control mb-3" id="urlList" placeholder="Enter URLs (one per line)" rows="6" required></textarea>

                <div class="d-flex align-items-center justify-content-end gap-10">
                    <button class="btn bnt-sm btn-info-900" type="submit">Save Keywords</button>
                </div>
            </form>
        </div>
    @endif

    <div class="col-lg-4">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Title</span>
            </div>
            <div class="card-body bg-warning-50">
                @if ($relatedData && $relatedData->sub_type == 'ADHOC')
                    <input type="text" class="m-0 p-0 border-0 w-100 h-100" onchange="updateTaskField(this)"
                        id="title" name="title" value="#{{ $task->id }} - {{ $task->title }}" required>
                @else
                    <input type="text" class="m-0 p-0 border-0 w-100 h-100" id="title" name="title"
                        readonly value="#{{ $task->id }} - {{ $task->title }}" required>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header bg-success-50 py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Start Date</span>
            </div>
            <div class="card-body bg-success-50">
                <input type="datetime" readonly disabled class="m-0 p-0 border-0 w-100 h-100 date-picker"
                    onchange="updateTaskField(this)" name="start_date" id="start_date"
                    value="{{ $task->start_date }}" required>
            </div>
        </div>
    </div>
    @if ($task->status == 'Completed')
        <div class="col-lg-4">
            <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                <div
                    class="card-header bg-info-50 py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <span class="fw-bold">Completed at</span>
                </div>
                <div class="card-body bg-info-50">
                    <input type="datetime" readonly disabled class="m-0 p-0 border-0 w-100 h-100 date-picker"
                        id="completed_date" value="{{ $task->completed_date }}" required>
                </div>
            </div>
        </div>
    @endif
    <div class="col-lg-4">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header bg-danger-50 py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Due Date</span>
            </div>
            <div class="card-body bg-danger-50">
                <input type="date" class="m-0 p-0 border-0 w-100 h-100 date-picker"
                    onchange="updateTaskField(this)" name="due_date" id="due_date" value="{{ $task->due_date }}"
                    required>
            </div>
        </div>
    </div>
</div>
<div class="row gy-3 mb-10">
    <div class="col-lg-8">
        <div class="card border border-secondary" style="height: 500px; overflow-y: auto">
            <div class="card-header position-sticky top-0">
                <span class="fw-bold">Description</span>
            </div>
            <div class="card-body">
                <textarea class="rounded border m-0 w-100 h-100"
                    @if (auth()->user()->hasRole('ADMIN')) onchange="updateTaskField(this)" @endif name="description" id="description"
                    cols="30" rows="10">{{ $task->description }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border border-secondary" style="height: 500px; overflow-y: auto">
            <div class="card-header position-sticky top-0">
                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <span class="fw-bold">Task Files</span>
                    @if ($is_ajax)
                        <a href="{{ route('tasks.show', $task->id) }}">Add More</a>
                    @else
                        <button type="button" id="taskAttachments"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Add More
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div id="uploadTaskAttachmentsForm" style="display: none">
                    <form action="{{ route('tasks.attachments.store', $task->id) }}" class="dropzone"
                        id="taskAttachmentDropzone" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                    </form>
                    <button type="button" class="btn btn-primary my-3" id="uploadTaskFilesBtn">
                        Upload Files
                    </button>
                </div>

                <div id="taskUploadStatus" class="mt-2"></div>

                @if (
                    $task->title == 'SEO Content Request' &&
                        ($task->project->tasks->where('title', 'Upload the Content Intake Form')->first() &&
                            $task->project->tasks->where('title', 'Upload the Content Intake Form')->first()->status == 'Completed'))
                    @php
                        $completions = $task->project->tasks->where('title', 'Upload the Content Intake Form')->first()
                            ->completions;
                    @endphp
                    @foreach ($completions as $completion)
                        @php
                            $filePath = '/storage/app/public/' . $completion->field_value;
                        @endphp
                        <div class="card mb-10 border">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <a href="{{ asset($filePath) }}"
                                    class="fs-7 d-block">{{ $completion->field_name }}</a>
                                <div class="gap-10 d-flex align-items-center">
                                    <a href="{{ asset($filePath) }}" download="content_intake_form"
                                        class="btn border-0 m-0 p-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            fill="green" class="bi bi-download" viewBox="0 0 16 16">
                                            <path
                                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                            <path
                                                d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div id="taskAttachmentsContainer">
                </div>
            </div>
        </div>
    </div>
</div>
@if (
    $task->project->tasks->where('title', 'Keywords Approval')->first() &&
        $task->project->tasks->where('title', 'Keywords Approval')->first()->status == 'Completed' &&
        $task->title == 'SEO Content Request')
    <div class="row gy-3 mb-16">
        <div class="col-lg-6">
            <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <span class="fw-bold">Project Keywords</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Url</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($task->project->tasks->where('title', 'Add KW Mapping')->first()->projectKeywords as $keyword)
                                <tr>
                                    <td>{{ $keyword->keyword }}</td>
                                    <td>{{ $keyword->url }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($task->project->tasks->where('title', 'Add KW Mapping')->first()->completions)
            <div class="col-lg-6">
                <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                    <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                        <span class="fw-bold">KW Mapping Closing Inputs</span>
                    </div>
                    <div class="card-body p-0 table-responsive" style="height: 500px; overflow: auto;">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                @foreach ($task->project->tasks->where('title', 'Add KW Mapping')->first()->completions as $completion)
                                    <tr>
                                        @if ($completion->field_value === 'on')
                                            <td class="fs-7">
                                                {{ str_replace('_', ' ', ucfirst($completion->field_name)) }}
                                            </td>
                                            <td class="fs-7">
                                                <span class="text-success ms-2">
                                                    <iconify-icon icon="mdi:check-circle" width="20"
                                                        height="20"></iconify-icon>
                                                </span>
                                            </td>
                                        @endif
                                        @if (Str::startsWith($completion->field_value, 'task_attachments/'))
                                            <td class="fs-7">Attachment</td>
                                            <td class="fs-7">
                                                @php
                                                    $filePath = '/public/storage/' . $completion->field_value;
                                                @endphp
                                                <a href="{{ asset($filePath) }}" target="_blank"
                                                    class="fs-7 text-primary d-block mt-1">
                                                    Download File
                                                </a>
                                                <a href="{{ asset($filePath) }}" target="_blank"
                                                    class="fs-7 text-primary d-block mt-1">
                                                    Show
                                                </a>
                                            </td>
                                        @elseif ($completion->field_value != 'on' && !Str::startsWith($completion->field_value, 'task_attachments/'))
                                            <td class="fs-7">
                                                {{ str_replace('_', ' ', ucfirst($completion->field_name)) }}
                                            </td>
                                            <td class="fs-7">{{ $completion->field_value }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
<div class="row gy-3 mb-16">
    @if ($task->completions->count())
        <div class="col-lg-4">
            <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <span class="fw-bold">Closing Inputs</span>
                </div>
                <div class="card-body p-0 table-responsive" style="height: 500px; overflow: auto;">
                    <table class="table table-striped table-bordered">
                        <thead class="position-static">
                            <tr>
                                <th class="fw-light">Closing Title</th>
                                <th class="fw-light">Closing Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($task->completions as $completion)
                                <tr>
                                    @if ($completion->field_value === 'on')
                                        <td class="fs-7">
                                            {{ str_replace('_', ' ', ucfirst($completion->field_name)) }}
                                        </td>
                                        <td class="fs-7">
                                            <span class="text-success ms-2">
                                                <iconify-icon icon="mdi:check-circle" width="20"
                                                    height="20"></iconify-icon>
                                            </span>
                                        </td>
                                    @endif
                                    @if (Str::startsWith($completion->field_value, 'task_attachments/'))
                                        <td class="fs-7">{{ $completion->field_name }}</td>
                                        <td class="fs-7">
                                            @php
                                                $filePath = '/public/storage/' . $completion->field_value;
                                            @endphp
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="fs-7 text-primary d-block mt-1">
                                                Download File
                                            </a>
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="fs-7 text-primary d-block mt-1">
                                                Show
                                            </a>
                                        </td>
                                    @elseif ($completion->field_value != 'on' && !Str::startsWith($completion->field_value, 'task_attachments/'))
                                        <td class="fs-7">
                                            {{ str_replace('_', ' ', ucfirst($completion->field_name)) }}
                                        </td>
                                        <td class="fs-7">{{ $completion->field_value }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    @if ($task->project->credentials->count() && $task->title == 'Add the Client Credentials')
        <div class="col-lg-4">
            <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <span class="fw-bold">Credentials</span>
                </div>
                <div class="card-body" style="height: 500px; overflow-y: auto;">
                    @foreach ($task->project->credentials as $credential)
                        <div class="my-10">
                            <div class="text-capitalize"><strong>Platform:</strong>
                                {{ $credential->platform_name }}</div>
                            <div class="text-capitalize"><strong>URL:</strong> <a href="{{ $credential->url }}"
                                    target="_blank">Visit</a></div>
                            <div class="text-capitalize"><strong>Username:</strong>
                                {{ $credential->username }}</div>
                            <div class="text-capitalize"><strong>Password:</strong>
                                <span class="password-toggle text-muted"
                                    data-password="{{ $credential->password }}">••••••</span>
                                <a href="javascript:void(0);" class="toggle-password small">Show</a>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="col-12">
        <div class="card border border-secondary p-0 h-100 position-relative radius-12">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Task Comments</span>
            </div>
            <div class="card-body p-0 position-relative">
                <div class="row gy-3 p-10">
                    <ul id="comment-list" class="list-group mb-3"></ul>
                </div>
                <form action="#" class="row gy-3 p-10 bottom-0 left-0 w-100 bg-base-50">
                    <div class="d-flex align-items-end flex-column justify-content-center gap-3">
                        {{-- id="comment-text" --}}
                        <textarea rows="10" class="editor form-control border"></textarea>
                        <div class="d-flex align-items-center gap-4">
                            <button type="button" id="add-commentt" data-taskID="{{ $task->id }}"
                                onclick="doComment()"
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

<div class="row gy-3 mb-10">

    <div class="col-lg-6 starting-kw-url-wrapper" style="display: none">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Assigned Keywords & Urls</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>URL</th>
                            @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="startingKwUrlsTable">
                        <!-- Data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 closing-kw-url-wrapper" style="display: none">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Task Keywords & Urls</span>
            </div>
            <div class="card-body table-responsive p-0" style="height: 500px; overflow: auto;">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>URL</th>
                            @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="closingKwUrlsTable">
                        <!-- Data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <input type="hidden" id="task_id" value="{{ $task->id }}">
    <input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

</div>
@if ($task->title == 'OFFSITE - PARTNER LINKS TASK')
    <div class="row my-10">
        <div class="col-lg-12">
            <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                    <span class="fw-bold">Partner Links</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Url</th>
                                <th>Backlink</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($task->partnerLinks as $link)
                                <tr>
                                    <td>{{ $link->keyword }}</td>
                                    <td>{{ $link->url }}</td>
                                    <td>{{ $link->backlink }}</td>
                                    <td>{{ $link->amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="row my-10">
    <div class="col-xxl-6 col-md-6">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div
                class="card-header py-16 px-24 bg-base d-flex align-items-center justify-content-between border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Collaborators</span>
                <select onchange="addCollaborator('{{ $task->id }}')" id="user_id"
                    class="form-select w-auto">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body p-24">
                <div class="row gy-3 mb-10" id="collaborators-list">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-md-6">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Task Activity</span>
            </div>
            <div class="card-body p-24">
                <div class="row gy-3 mb-10" id="collaborators-list">
                    @foreach ($task->history as $history)
                        <div class="timeline-item mb-6">
                            <div class="timeline-content d-flex align-items-center gap-10">
                                <iconify-icon icon="mdi:history" width="12" height="12"></iconify-icon>
                                <span class="fw-semibold fs-7">
                                    {{ ucwords(str_replace('_', ' ', $history->action)) }}
                                </span>
                                <small
                                    class="text-secondary fs-7">{{ $history->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row gy-3 mb-10">
    <div class="col-12">
        <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
            <div class="card-header py-16 px-24 bg-base border border-end-0 border-start-0 border-top-0">
                <span class="fw-bold">Danger Zone</span>
            </div>
            <div class="card-body p-24">
                @if (auth()->user()->hasRole('ADMIN'))
                    <div class="d-flex align-items-center justify-content-between gap-10">
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                            style="display: inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="card-delete-button text-danger-600">
                                Delete Tasks
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<button class="btn-save-pulse btn btn-lg btn-success">
    Save
</button>

<input type="hidden" id="taskId" value="{{ $task->id }}">

<script>
    $(document).ready(function() {
        $('#addUrlGroup').click(function() {
            $('#urlGroups').append(`
                <div class="url-group card shadow-sm border-0 mb-3 border">
                    <div class="card-body bg-light-subtle">
                        <div class="mb-3">
                            <label class="form-label fw-bold">URL</label>
                            <input type="url" class="form-control url-input" placeholder="https://example.com" required>
                        </div>
                    
                        <div class="mb-3">
                            <label class="form-label fw-bold">Keywords <small class="text-muted">(One per line)</small></label>
                            <textarea class="form-control keyword-textarea" rows="4"
                                placeholder="Enter keywords, one per line..." required></textarea>
                        </div>
                    
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-url-group">
                                <i class="bi bi-trash me-1"></i> Remove URL Group
                            </button>
                        </div>
                    </div>
                </div>
            `);
        });
        // Remove URL group
        $(document).on('click', '.remove-url-group', function() {
            $(this).closest('.url-group').remove();
        });
        // Handle form submission
        $('#keywordForm3').submit(function(e) {
            e.preventDefault();

            let combinedKeywords = [];

            $('.url-group').each(function() {
                const url = $(this).find('.url-input').val().trim();
                const keywordsRaw = $(this).find('.keyword-textarea').val();

                if (url && keywordsRaw) {
                    const keywords = keywordsRaw
                        .split('\n')
                        .map(k => k.trim())
                        .filter(k => k.length > 0); // remove blanks

                    keywords.forEach(keyword => {
                        combinedKeywords.push({
                            keyword: keyword,
                            url: url
                        });
                    });
                }
            });

            if (combinedKeywords.length === 0) {
                toastr.error("Please enter at least one keyword.");
                return;
            }

            let formData = new FormData();
            formData.append("task_id", "{{ $task->id }}");
            formData.append("type", "closing");
            combinedKeywords.forEach((item, index) => {
                formData.append(`keywords[${index}][keyword]`, item.keyword);
                formData.append(`keywords[${index}][url]`, item.url);
            });

            $.ajax({
                url: "{{ route('projects.storeKeywords', $task->project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) {
                        triggerCompletionSubmit();
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });

    $(document).on('click', '.toggle-password', function() {
        let passwordSpan = $(this).prev('.password-toggle');
        let actualPassword = passwordSpan.data('password');

        if (passwordSpan.text() === '••••••') {
            passwordSpan.text(actualPassword);
            $(this).text('Hide');
        } else {
            passwordSpan.text('••••••');
            $(this).text('Show');
        }
    });

    let index = 1;

    $('#addMore').on('click', function() {
        const section = `
        <div class="link-group border p-3 mb-3">
            <div class="mb-2">
                <label>Keyword</label>
                <input type="text" name="links[${index}][keyword]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>URL</label>
                <input type="url" name="links[${index}][url]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Backlink</label>
                <input type="url" name="links[${index}][backlink]" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Amount</label>
                <input type="number" step="0.01" name="links[${index}][amount]" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-section">Remove</button>
        </div>`;

        $('#link-sections').append(section);
        index++;
    });

    $(document).on('click', '.remove-section', function() {
        $(this).closest('.link-group').remove();
    });

    $('#partnerLinkForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('partner-links.store-multiple') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    toastr.success('Links added successfully!')
                    triggerCompletionSubmit();
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    function fetchCollaborators(taskId) {
        $.get("{{ route('task.collaborators', ['task' => ':id']) }}".replace(':id', taskId), function(response) {
            $('#collaborators-list').empty();
            response.forEach(collaborator => {
                $('#collaborators-list').append(`
                <div class="col-sm-6">
                    <div class="d-flex align-items-start gap-2">
                        <img src="{{ asset('/public/storage') }}/${collaborator.user.img}" class="w-40-px h-40-px rounded-pill" /> 
                        <div class="flex-grow-1 d-inline-flex flex-column">
                            <h6 class="text-sm mb-0">${collaborator.user.name}</h6>
                            <span class="text-xs text-secondary-light">${collaborator.user.email}</span>
                        </div>
                        @if ($task->created_by == auth()->user()->id || auth()->user()->hasRole('ADMIN'))
                        <iconify-icon onclick="removeCollaborator(${collaborator.id}, ${taskId})" icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                        @endif
                    </div>
                </div>
            `);
            });
        });
    }

    function addCollaborator(taskId) {
        $.post("{{ route('task.collaborators.store') }}", {
            task_id: taskId,
            user_id: $('#user_id').val(),
            _token: "{{ csrf_token() }}"
        }, function(response) {
            fetchCollaborators(taskId);
            toastr.success(response.message)
        });
    }

    function removeCollaborator(id, taskId) {
        $.ajax({
            url: "{{ route('task.collaborators.destroy', ['collaborator' => ':id']) }}".replace(':id', id),
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                fetchCollaborators(taskId);
                toastr.success(response.message)
            }
        });
    }

    function loadStartingKwUrls() {
        $.ajax({
            url: '{{ route('offsite.tasks.starting_kw_urls', ['task_id' => $task->id]) }}',
            type: "GET",
            success: function(data) {
                var tableBody = $("#startingKwUrlsTable");
                tableBody.empty();
                data.forEach(function(kwUrl) {
                    tableBody.append(`
                    <tr id="row-${kwUrl.id}">
                        <td>${kwUrl.keyword}</td>
                        <td><a href="${kwUrl.url}" target="_blank">${kwUrl.url}</a></td>
                        @if (
                            $userPermissions->contains('can remove offsite links') &&
                                $task->status !== 'Completed' &&
                                ($task->createdBy && $task->createdBy->id == auth()->user()->id))
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${kwUrl.id}">Delete</button>
                            </td>
                        @endif
                        </tr>
                            `);
                });
                if (data.length > 0) {
                    $('.starting-kw-url-wrapper').slideDown()
                }
            },
            error: function() {
                $("#startingKwUrlsTable").html(
                    '<tr><td colspan="4">Error Loading Data</td></tr>');
            }
        });
    }

    function loadClosingKwUrls() {
        $.ajax({
            url: '{{ route('offsite.tasks.closing_kw_urls', ['task_id' => $task->id]) }}',
            type: "GET",
            success: function(data) {
                var tableBody = $("#closingKwUrlsTable");
                tableBody.empty();
                data.forEach(function(kwUrl) {
                    tableBody.append(`
                        <tr id="row-${kwUrl.id}">
                            <td>${kwUrl.keyword}</td>
                            <td><a href="${kwUrl.url}" target="_blank">${kwUrl.url}</a></td>
                            @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                            <td>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${kwUrl.id}">Delete</button>
                            </td>
                            @endif
                        </tr>
                    `);
                });
                if (data.length > 0) {
                    $('.closing-kw-url-wrapper').slideDown()
                }
            },
            error: function() {
                $("#closingKwUrlsTable").html(
                    '<tr><td colspan="4">Error Loading Data</td></tr>');
            }
        });
    }

    setTimeout(() => {
        loadStartingKwUrls();
        loadClosingKwUrls();
    }, 2000);

    $(document).ready(function() {
        fetchCollaborators("{{ $task->id }}");

        var deleteUrlTemplate = '{{ route('project-keyword.delete', ['id' => ':id']) }}';
        $(document).off("click", ".delete-btn").on("click", ".delete-btn", function(event) {
            event.preventDefault();

            var id = $(this).data("id");
            var deleteUrl = deleteUrlTemplate.replace(":id", id);

            $.ajax({
                url: deleteUrl,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#row-" + id).fadeOut();
                    toastr.success("Keyword deleted successfully!");
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || "Failed to delete record!");
                }
            });
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

    $(document).on('click', '.complete-task-btn', function() {
        $('.completion-form-wrapper').slideDown();
        $(this).fadeOut();
    });

    function triggerCompletionSubmit() {
        let button = $('.complete-task-btn');
        $('button[type="submit"]').prop('disabled', true);
        $('button[type="submit"]').text('Loading...');
        setTimeout(() => {
            $('button[type="submit"]').prop('disabled', false);
            $('button[type="submit"]').text('Submit');
        }, 3000);
        $.ajax({
            url: "{{ route('task.markAsCompleted', $task->id) }}",
            type: "PATCH",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    button.fadeIn()
                    button.text("Completed").removeClass("btn-neutral-900").addClass("btn-success").prop(
                        "disabled", true);
                    toastr.success("Task marked as completed!", "Success");
                    $('.completion-form-wrapper').slideUp();
                    $('.completion-anim-wrapper').fadeIn();
                    setTimeout(() => {
                        $('.completion-anim-wrapper').fadeOut();
                        setTimeout(() => {
                            window.location.href = "{{ route('tasks.index') }}";
                        }, 1000);
                    }, 1000);
                } else {
                    toastr.error(response.message || "Something went wrong!");
                }
            },
            error: function(xhr) {
                toastr.error(
                    "Failed to mark task as completed.",
                    "Error");
            }
        });
    }

    $(document).ready(function() {
        $(document).on("submit", "#taskForm", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('tasks.complete') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message, "Success");
                    if (xhr.status === 200) {
                        triggerCompletionSubmit();
                    }
                },
                error: function(xhr) {
                    toastr.error("Error: " + xhr.responseText, "Error");
                }
            });
        });
        $("#taskForm2").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('tasks.complete') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message, "Success");
                    if (xhr.status === 200) {
                        triggerCompletionSubmit();
                    }
                },
                error: function(xhr) {
                    toastr.error("Error: " + xhr.responseText, "Error");
                }
            });
        });
    });

    $(document).ready(function() {
        let credentialIndex = 1;
        $("#addCredential").click(function() {
            $("#credentialsContainer").append(`
            <div class="credentialRow row">
                <div class="col-lg-3">
                    <input required class="form-control" type="text"
                        name="credentials[${credentialIndex}][platform_name]" placeholder="Platform Name">
                </div>
                <div class="col-lg-3">
                    <input class="form-control" type="text" name="credentials[${credentialIndex}][url]"
                        placeholder="Website URL">
                </div>
                <div class="col-lg-3">
                    <input required class="form-control" type="text" name="credentials[${credentialIndex}][username]"
                        placeholder="Username/Email">
                </div>
                <div class="col-lg-3 d-flex align-items-center gap-3">
                    <input class="form-control" type="password" name="credentials[${credentialIndex}][password]"
                        placeholder="Password">
                    <button type="button"
                    class="removeCredential btn fs-7 btn-danger btn-sm">Remove</button>
                </div>
            </div>
            `);
            credentialIndex++;
        });
        $(document).on("click", ".removeCredential", function() {
            $(this).closest(".credentialRow").remove();
        });
        $("#clientCredentialsForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) {
                        triggerCompletionSubmit();
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
        $("#clientCredentialsForm2").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) {
                        let form = $('.ga-gmb-form');
                        let formData = form.serialize();
                        $.ajax({
                            url: "{{ route('tasks.complete') }}",
                            type: "POST",
                            data: formData,
                        });
                        setTimeout(() => {
                            triggerCompletionSubmit();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });

    $(document).ready(function() {

        $("#keywordForm2").submit(function(e) {
            e.preventDefault();

            let keyword = $("#mainKeyword").val().trim();
            let urls = $("#urlList").val().trim().split("\n").filter(url => url.trim() !== "");

            if (!keyword || urls.length === 0) {
                alert("Please enter a keyword and at least one valid URL.");
                return;
            }

            let formData = new FormData();
            formData.append("_token", $('input[name="_token"]').val());
            formData.append("type", $('input[name="type"]').val());
            formData.append("task_id", "{{ $task->id }}");

            urls.forEach((url, index) => {
                formData.append(`keywords[${index}][keyword]`, keyword);
                formData.append(`keywords[${index}][url]`, url.trim());
            });

            $.ajax({
                url: "{{ route('projects.storeKeywords', $task->project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    response.data.forEach(function(kwUrl) {
                        loadClosingKwUrls();
                        $('.add-more-links-wrapper').fadeOut()
                    });
                    $("#mainKeyword").val('');
                    $("#urlList").val('');
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });

    });

    function timeAgo(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        const intervals = [{
                label: "year",
                seconds: 31536000
            },
            {
                label: "month",
                seconds: 2592000
            },
            {
                label: "week",
                seconds: 604800
            },
            {
                label: "day",
                seconds: 86400
            },
            {
                label: "hour",
                seconds: 3600
            },
            {
                label: "minute",
                seconds: 60
            },
        ];

        for (const interval of intervals) {
            const count = Math.floor(seconds / interval.seconds);
            if (count > 0) {
                return `${count} ${interval.label}${count !== 1 ? "s" : ""} ago`;
            }
        }
        return seconds < 10 ? "Just now" : `${seconds} seconds ago`;
    }

    function doComment() {
        const button = document.getElementById('add-commentt');
        const taskId = button.getAttribute('data-taskID');

        const commentText = $('.editor').val();

        fetch("{{ route('comments.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    task_id: taskId,
                    comment: commentText
                })
            })
            .then(response => response.json())
            .then(data => {
                loadComments();
                editor.setContent(''); // Clear editor
            })
            .catch(error => console.error("Error:", error));
    }

    function loadComments() {
        let taskId = $("input[name='task_id']").val();
        $.get("{{ route('comments.index', $task->id) }}", function(data) {
            $('#comment-list').empty();
            data.forEach(comment => {
                $('#comment-list').append(
                    `
                <div class="position-relative p-1 px-24">
                    <div class="d-flex align-items-start gap-3">
                        <div class="w-100 p-3 rounded border">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                  <img src="{{ asset('/public/storage') }}/${comment.user.img}" alt="" class="w-40-px h-40-px rounded-pill">  <span class="mb-0 fs-7">${comment.user.name} ${comment.user_id == {{ auth()->id() }} ? '(you)' : ''}</span>
                                </div>
                                ${comment.user_id === {{ auth()->id() }} ? `
                                    <button type="button" data-id="${comment.id}" class="remove-item-btn delete-comment bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"> 
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </button>
                                ` : ''}
                            </div>
                            <hr class="my-24" />
                            <!-- Render HTML content safely -->
                            <p class="mb-0 text-primary-light" style="white-space: pre-wrap;">${comment.comment}</p>
                            <span class="comment-time d-block fs-7" data-time="${comment.created_at}"></span>
                        </div>
                    </div>
                </div>
                `
                );
            });
            updateTimestamps();
        });
    }

    function updateTimestamps() {
        document.querySelectorAll(".comment-time").forEach(element => {
            const timestamp = element.getAttribute("data-time");
            element.textContent = timeAgo(timestamp);
        });
    }

    setInterval(updateTimestamps, 30000);

    $(document).on('click', '.delete-comment', function() {
        let commentId = $(this).data('id');
        $.ajax({
            url: "{{ route('comments.destroy', ['id' => ':id']) }}".replace(':id', commentId),
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                loadComments();
            }
        });
    });

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
            let filePath = "{{ asset('/public/storage/') }}/" + file.file_path;
            let original_name = file?.original_name;
            let extension = file.file_path.split('.').pop();
            let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            let createdAt = new Date(file.created_at).toLocaleString();
            html += `
            <div class="card mb-10 border">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="fs-7 d-block">${file?.user?.name}</span>
                    <div class="gap-10 d-flex align-items-center">
                        <a href="${filePath}" download="${original_name}" class="btn border-0 m-0 p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="green" class="bi bi-download" viewBox="0 0 16 16">
                              <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                              <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                            </svg>
                        </a>
                        <button type="button" class="delete-attachment btn border-0 m-0 p-0"
                            data-attachment-id="${file.id}"
                            data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                              <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="card-body d-flex align-items-center">
                    <img src="${imageExtensions.includes(extension)? filePath : '{{ asset('/images/doc.png') }}'}" alt=""
                        class="w-40-px h-40-px object-fit-cover ${imageExtensions.includes(extension)?'rounded':''} me-12">
                    <div class="flex-grow-1 fs-6">
                        <a target="_blank" href="${filePath}" class="text-md mb-0 fw-medium fs-6">${original_name}</a>
                        <span class="fs-7 d-block">${createdAt}</span>
                    </div>
                </div>
            </div>`;
        });
        $("#taskAttachmentsContainer").html(html);
    }

    var uploadedDocumentMap = {};
    Dropzone.autoDiscover = false;

    var taskDropzone = new Dropzone("#taskAttachmentDropzone", {
        url: "{{ route('tasks.attachments.store', $task->id) }}",
        autoProcessQueue: false,
        uploadMultiple: true,
        paramName: "attachments", // Ensure Laravel handles it as an array
        maxFiles: 10,
        maxFilesize: 5, // 5MB per file
        acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt",
        addRemoveLinks: true,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        init: function() {
            var dropzoneInstance = this;
            dropzoneInstance.on("sending", function(file, xhr, formData) {
                var form = document.querySelector("#task-create-form");
                var formDataEntries = new FormData(form);
                formDataEntries.forEach((value, key) => {
                    formData.append(key, value);
                });
                formData.append("attachments[]", file);
            });
            dropzoneInstance.on("success", function(file, response) {
                uploadedDocumentMap[file.name] = response.name;
            });
            dropzoneInstance.on("removedfile", function(file) {
                if (uploadedDocumentMap[file.name]) {
                    delete uploadedDocumentMap[file.name];
                }
            });
        }
    });

    $(document).on('click', '#taskAttachments', function(e) {
        $('#uploadTaskAttachmentsForm').slideDown();
    });

    $(document).on("click", "#uploadTaskFilesBtn", function(e) {
        var formData = new FormData($("#taskAttachmentDropzone")[0]);
        taskDropzone.files.forEach(function(file) {
            formData.append("attachments[]", file);
        });
        $.ajax({
            url: "{{ route('tasks.attachments.store', $task->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Task attachments added');
                renderAttachments(response.attachments);
                $('#uploadTaskAttachmentsForm').slideUp();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Error in submission!");
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

    function updateTaskField(element) {
        let allowEdit = {{ auth()->user()->hasPermission('can edit task') ? '1' : '0' }}
        let taskId = $("#taskId").val();
        let fieldName = $(element).attr("name");
        let fieldValue = $(element).val();
        if (allowEdit == 1) {
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
        } else {
            toastr.error("Editing Not Allowed");
        }
    }

    function handleTaskStatusChange(element) {
        let taskId = $(element).data("task-id");
        let updateUrl = "{{ route('tasks.updateStatus', ':id') }}".replace(':id', taskId);
        let newStatus = $(element).val();

        $.ajax({
            url: updateUrl,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
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
    }

    $(document).ready(function() {
        loadComments()
        loadTaskAttachments()
    });

    function taskLinkCopy(link) {
        navigator.clipboard.writeText(link)
            .then(() => {
                toastr.success('Task link copied to clipboard!');
            })
            .catch(err => {
                console.error('Failed to copy: ', err);
                toastr.success('Could not copy the link.');
            });
    }
</script>
