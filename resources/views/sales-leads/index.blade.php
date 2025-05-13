<x-app-layout>
    <x-slot name="header">
        Leads Kanban Board
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <button class="btn btn-primary" onclick="openLeadForm()">
                <i class="bi bi-plus-lg"></i> Add Lead
            </button>
            <button class="btn btn-sm btn-outline-primary" onclick="createStatusPrompt()" title="Add Status">
                New Status
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <div class="card-body">
            <div class="row g-3" id="kanbanBoard">
                @foreach ($statuses as $status)
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 h-100 bg-white">
                            <div
                                class="card-header bg-light d-flex justify-content-between align-items-center border-bottom">
                                <span class="mb-0 text-secondary fw-bold">
                                    {{ $status->name }}
                                </span>
                                <button class="btn btn-sm btn-outline-secondary"
                                    onclick="editStatusName({{ $status->id }}, '{{ addslashes($status->name) }}')"
                                    title="Edit Status Name">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </div>

                            <div class="card-body lead-dropzone bg-light-subtle p-2"
                                data-status-id="{{ $status->id }}" style="min-height: 250px;">
                                @forelse ($status->leads as $lead)
                                    <div class="card draggable-lead mb-3 border-start border-3 border-primary shadow-sm"
                                        data-id="{{ $lead->id }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-dark fw-semibold">
                                                        <i
                                                            class="bi bi-person-circle me-10 text-primary"></i>{{ $lead->name }}
                                                    </h6>
                                                    @if ($lead->company_name)
                                                        <div class="text-muted small mb-1">
                                                            <i class="bi bi-building me-1"></i>{{ $lead->company_name }}
                                                        </div>
                                                    @endif
                                                    @if ($lead->email)
                                                        <div class="text-muted small mb-1">
                                                            <i class="bi bi-envelope-at me-1"></i>{{ $lead->email }}
                                                        </div>
                                                    @endif
                                                    @if ($lead->phone)
                                                        <div class="text-muted small mb-1">
                                                            <i class="bi bi-telephone me-1"></i>{{ $lead->phone }}
                                                        </div>
                                                    @endif
                                                    @if ($lead->lead_date)
                                                        <div class="text-muted small">
                                                            <i
                                                                class="bi bi-calendar-event me-1"></i>{{ $lead->lead_date->format('d M Y') }}
                                                        </div>
                                                    @endif
                                                    @if ($lead->created_by)
                                                        <div class="text-muted small">
                                                            <i
                                                                class="bi bi-calendar-event me-1"></i>{{ $lead->creator->name ?? '' }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ms-2">
                                                    <button class="btn btn-sm btn-outline-warning me-1"
                                                        onclick="editLead({{ $lead->id }})" title="Edit">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteLead({{ $lead->id }})" title="Delete">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info"
                                                        onclick="viewLead({{ $lead->id }})" title="View">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted small py-3">
                                        <i class="bi bi-info-circle me-1"></i> No leads in this stage
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="leadModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <form id="leadForm">
                @csrf
                <input type="hidden" name="_method" value="POST" />
                <input type="hidden" name="id" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lead Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input name="name" class="form-control mb-2" placeholder="Name" required>
                        <input name="email" class="form-control mb-2" placeholder="Email">
                        <input name="phone" class="form-control mb-2" placeholder="Phone">
                        <input name="company_name" class="form-control mb-2" placeholder="Company">
                        <input name="location" class="form-control mb-2" placeholder="location">
                        <input name="website" class="form-control mb-2" placeholder="Website">
                        <input name="lead_date" id="lead_date" class="form-control mb-2" type="date"
                            placeholder="Date">
                        <select name="lead_status_id" class="form-control mb-2">
                            @foreach ($LeadStatus as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <select name="services[]" class="form-control mb-2">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        <select id="source_id" name="source_id" class="form-control mb-2">
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </select>
                        <select id="industry_id" name="industry_id" class="form-control mb-2">
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->industry }}</option>
                            @endforeach
                        </select>
                        <select id="priority" name="priority" class="form-control mb-2">
                            <option value="low">low</option>
                            <option value="medium">medium</option>
                            <option value="high">high</option>
                        </select>
                        <input type="hidden" value="{{ auth()->user()->id }}" name="created_by">

                        <select id="is_qualified" name="is_qualified" class="form-control mb-2">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>

                        <textarea name="comments" class="form-control mb-2" placeholder="Comments"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Save</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Offcanvas to View Lead -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="viewLeadOffcanvas"
        aria-labelledby="viewLeadOffcanvasLabel" data-lead-id="">
        <div class="offcanvas-header bg-dark">
            <h5 id="viewLeadOffcanvasLabel" class="text-light">Lead Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Lead Details Card -->
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3 text-primary"><i class="bi bi-person-circle"></i> Lead Information</h6>
                    <div id="leadDetails">
                        <!-- Lead details will be dynamically populated here -->
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="mb-4">
                <h5 class="text-secondary mb-3">Comments</h5>
                <div id="commentsList">
                    <!-- Comments will be dynamically populated here -->
                </div>
                <!-- Add Comment Section -->
                <div class="mt-3">
                    <textarea id="newComment" class="form-control" placeholder="Add a comment" rows="3"></textarea>
                    <button class="btn btn-primary mt-2" onclick="addComment()">
                        <i class="bi bi-send"></i> Add Comment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewLead(leadId) {
            $.get("{{ route('leads.show', ':lead') }}".replace(':lead', leadId), function(lead) {
                // Populate the lead details in the offcanvas
                let leadHtml = `
            <div class="mb-3">
                <strong>Name:</strong> ${lead.name || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Email:</strong> ${lead.email || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Phone:</strong> ${lead.phone || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Company:</strong> ${lead.company_name || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Source:</strong> ${lead.source.name || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Location:</strong> ${lead.location || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Website:</strong> ${lead.website || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Lead Date:</strong> ${lead.lead_date || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Created By:</strong> ${lead.creator ? lead.creator.name : 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Status:</strong> ${lead.status.name || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Priority:</strong> ${lead.priority || 'N/A'}
            </div>
            <div class="mb-3">
                <strong>Qualified:</strong> ${lead.is_qualified ? 'Yes' : 'No'}
            </div>
            <div class="mb-3">
                <strong>Industry:</strong> ${lead.industry ? lead.industry.industry : 'N/A'}
            </div>
        `;
                $('#leadDetails').html(leadHtml);

                // Fetch and display the comments for the lead
                setTimeout(() => {
                    fetchComments(leadId);
                }, 1000);
            });

            // Show the offcanvas
            $('#viewLeadOffcanvas').offcanvas('show');
            $('#viewLeadOffcanvas').data('lead-id', leadId);
        }

        // Fetch comments for a specific lead
        function fetchComments(leadId) {
            $.get("{{ route('leads.comments.index', ':lead') }}".replace(':lead', leadId), function(comments) {
                let commentsHtml = '';
                comments.forEach(comment => {
                    commentsHtml += `
                <div class="comment mb-2">
                    <p><strong>${comment.creator.name}</strong>: ${comment.comment}</p>
                    <button class="btn btn-warning btn-sm" onclick="editComment(${comment.id}, '${comment.comment}', ${leadId})">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteComment(${comment.id}, ${leadId})">
                        <i class="bi bi-trash3-fill"></i> Delete
                    </button>
                </div>
            `;
                });
                $('#commentsList').html(commentsHtml);
            });
        }

        // Add a comment to a specific lead
        function addComment() {
            let leadId = $('#viewLeadOffcanvas').data('lead-id'); // Use the lead ID from the offcanvas data attribute
            let comment = $('#newComment').val();

            $.post("{{ route('leads.comments.store', ':lead') }}".replace(':lead', leadId), {
                _token: '{{ csrf_token() }}',
                comment: comment
            }, function(response) {
                if (response.success) {
                    fetchComments(leadId); // Refresh the comments list
                    $('#newComment').val(''); // Clear the comment box
                }
            });
        }

        function editComment(commentId, currentComment, leadId) {
            let newComment = prompt('Edit Comment:', currentComment);
            if (newComment) {
                $.ajax({
                    url: "{{ route('leads.comments.update', ['lead' => ':lead', 'comment' => ':comment']) }}"
                        .replace(':lead', leadId)
                        .replace(':comment', commentId),
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: newComment
                    },
                    success: function(response) {
                        fetchComments(leadId); // Refresh the comments list
                    }
                });
            }
        }

        function deleteComment(commentId, leadId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                $.ajax({
                    url: "{{ route('leads.comments.destroy', ['lead' => ':lead', 'comment' => ':comment']) }}"
                        .replace(':lead', leadId)
                        .replace(':comment', commentId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        fetchComments(leadId); // Refresh the comments list
                    }
                });
            }
        }
    </script>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const routes = {
            store: '{{ route('leads.store') }}',
            show: id => `{{ url('leads') }}/${id}`,
            update: id => `{{ route('leads.update', ':id') }}`.replace(':id', id),
            destroy: id => `{{ route('leads.destroy', ':id') }}`.replace(':id', id),
            statusNameChange: id => `{{ route('lead-statuses.update', ':id') }}`.replace(':id', id),
            updateStatus: '{{ route('leads.updateStatus') }}',
            createStatus: '{{ route('lead-statuses.store') }}',
        };

        document.querySelectorAll('.lead-dropzone').forEach(zone => {
            new Sortable(zone, {
                group: 'leads',
                animation: 150,
                onEnd: function(evt) {
                    let leadId = evt.item.dataset.id;
                    let newStatusId = evt.to.getAttribute('data-status-id');

                    $.post(routes.updateStatus, {
                        _token: '{{ csrf_token() }}',
                        lead_id: leadId,
                        lead_status_id: newStatusId
                    }, function(res) {
                        toastr.success(res.message);
                    });
                }
            });
        });

        $('#leadForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = form.find('input[name="id"]').val();
            let url = id ? routes.update(id) : routes.store;
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize() + '&_method=' + method + '&_token={{ csrf_token() }}',
                success: function(res) {
                    $('#leadModal').modal('hide');
                    form[0].reset();
                    location.reload();
                }
            });
        });

        function openLeadForm() {
            $('#leadForm')[0].reset();
            $('#leadForm input[name="id"]').val('');
            $('#leadModal').modal('show');
        }

        function editLead(id) {
            $.get(routes.show(id), function(lead) {
                let form = $('#leadForm');
                form.find('input[name="id"]').val(lead.id);
                form.find('input[name="name"]').val(lead.name);
                form.find('input[name="location"]').val(lead.location);
                form.find('input[name="email"]').val(lead.email);
                form.find('input[name="phone"]').val(lead.phone);
                form.find('input[name="company_name"]').val(lead.company_name);
                form.find('input[name="website"]').val(lead.website);
                form.find('input[name="lead_date"]').val(lead.lead_date);
                form.find('textarea[name="comments"]').val(lead.comments);
                form.find('select[name="lead_status_id"]').val(lead.lead_status_id);
                form.find('select[name="industry_id"]').val(lead.industry_id);
                form.find('select[name="source_id"]').val(lead.source_id);
                form.find('select[name="is_qualified"]').val(lead.is_qualified == true ? 1 : 0);
                form.find('select[name="priority"]').val(lead.priority);
                form.find('select[name="services[]"]').val(lead.services.map(s => s.id));
                $('#leadModal').modal('show');
            });
        }

        function deleteLead(id) {
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: routes.destroy(id),
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        location.reload();
                        toastr.success(res.message);
                    }
                });
            }
        }

        function createStatusPrompt() {
            let name = prompt("Enter new status name:");
            if (name) {
                $.post(routes.createStatus, {
                    _token: '{{ csrf_token() }}',
                    name: name
                }, function(res) {
                    toastr.success('New Status Created!');
                    setTimeout(() => location.reload(), 500);
                });
            }
        }

        function editStatusName(statusId, currentName) {
            const newName = prompt("Update status name:", currentName);
            if (newName && newName !== currentName) {
                fetch(routes.statusNameChange(statusId), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: newName
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert("Failed to update status name.");
                        }
                    });
            }
        }
    </script>
</x-app-layout>
