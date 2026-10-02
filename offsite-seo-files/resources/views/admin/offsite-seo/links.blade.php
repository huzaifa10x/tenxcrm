@php
    $statusBadge = ['live' => 'success', 'pending' => 'warning', 'lost' => 'danger'];
@endphp
<x-app-layout>
    <x-slot name="header">
        {{ __('Offsite Links') }}
    </x-slot>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            @if ($isManager)
                <a href="{{ route('admin.offsite-seo.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Performance dashboard</a>
            @endif
        </div>
        <button type="button" class="btn btn-success" id="addLinkBtn"><i class="bi bi-plus-circle me-1"></i>Add Link</button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    @component('admin.offsite-seo._filters', ['action' => route('admin.offsite-seo.links.index'), 'range' => $range, 'ranges' => $ranges, 'from' => $from, 'to' => $to])
        @if ($isManager)
            <div class="col-md-2 col-6">
                <label class="form-label fw-semibold">Employee</label>
                <select name="user_id" class="form-select">
                    <option value="">All</option>
                    @foreach ($team as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold">Project</label>
            <select name="project_id" class="form-select">
                <option value="">All</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label fw-semibold">Status</label>
            <select name="link_status" class="form-select">
                <option value="">All</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('link_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
                <option value="none" {{ request('link_status') === 'none' ? 'selected' : '' }}>Not set</option>
            </select>
        </div>
    @endcomponent

    <div class="shadow-sm card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Link-Building Records</h6>
            <span class="badge bg-primary">{{ $links->total() }} links</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Project / Task</th>
                        <th>Type</th>
                        <th>Keyword</th>
                        <th>Link URL</th>
                        <th>Target URL</th>
                        <th>Follow</th>
                        <th>DR</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($links as $link)
                        <tr>
                            <td class="text-nowrap">{{ $link->created_at?->format('d M Y') }}</td>
                            <td>{{ $names[$link->owner_id] ?? '—' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $link->project->title ?? '—' }}</div>
                                @if ($link->task)
                                    <a href="{{ route('tasks.show', $link->task_id) }}" class="small text-decoration-none">
                                        #{{ $link->task_id }} {{ Str::limit($link->task->title, 35) }}</a>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $link->task->offsiteTask->sub_type ?? ($link->task->type ?? '—') }}</span></td>
                            <td>{{ $link->keyword }}</td>
                            <td><a href="{{ $link->url }}" target="_blank" rel="noopener">{{ Str::limit($link->url, 40) }}</a></td>
                            <td>
                                @if (Str::startsWith($link->destination_url, 'http'))
                                    <a href="{{ $link->destination_url }}" target="_blank" rel="noopener">{{ Str::limit($link->destination_url, 35) }}</a>
                                @else
                                    {{ $link->destination_url ?: '—' }}
                                @endif
                            </td>
                            <td>{{ $link->rel ? ucfirst($link->rel) : '—' }}</td>
                            <td>{{ $link->dr ?? '—' }}</td>
                            <td>
                                <select class="form-select form-select-sm js-status" style="min-width:105px;"
                                    data-url="{{ route('admin.offsite-seo.links.status', $link->id) }}">
                                    @if (!$link->link_status)
                                        <option value="" selected disabled>Not set</option>
                                    @endif
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $link->link_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-warning js-edit" title="Edit"
                                    data-url="{{ route('admin.offsite-seo.links.update', $link->id) }}"
                                    data-user="{{ $link->owner_id }}"
                                    data-task="{{ $link->task_id }}"
                                    data-task-label="#{{ $link->task_id }} {{ $link->task->title ?? '' }} — {{ $link->project->title ?? '' }}"
                                    data-keyword="{{ $link->keyword }}"
                                    data-link="{{ $link->url }}"
                                    data-destination="{{ $link->destination_url }}"
                                    data-rel="{{ $link->rel }}"
                                    data-dr="{{ $link->dr }}"
                                    data-status="{{ $link->link_status ?? 'live' }}"
                                    data-date="{{ $link->created_at?->toDateString() }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.offsite-seo.links.destroy', $link->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this link record?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-5 text-center text-muted">
                                <i class="mb-2 bi bi-inbox fs-1 d-block"></i>No links found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($links->hasPages())
            <div class="card-footer">{{ $links->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- Add / Edit modal --}}
    <div class="modal fade" id="linkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="linkForm" action="{{ route('admin.offsite-seo.links.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="linkMethod" value="POST">
                    <div class="modal-header">
                        <h6 class="modal-title" id="linkModalTitle">Add Link</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            @if ($isManager)
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" id="linkUser" class="form-select" required>
                                        <option value="">Select employee</option>
                                        @foreach ($team as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="{{ $isManager ? 'col-md-6' : 'col-12' }}">
                                <label class="form-label fw-semibold">Task <span class="text-danger">*</span></label>
                                <select name="task_id" id="linkTask" class="form-select" required>
                                    <option value="">Select task</option>
                                </select>
                                <small class="text-muted">Project and link type are taken from the task.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Keyword / anchor text <span class="text-danger">*</span></label>
                                <input type="text" name="keyword" id="linkKeyword" class="form-control" maxlength="255" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Link created on</label>
                                <input type="date" name="link_date" id="linkDate" class="form-control" max="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Link URL (page where the backlink is placed) <span class="text-danger">*</span></label>
                                <input type="url" name="url" id="linkUrl" class="form-control" maxlength="255" placeholder="https://" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Target URL (client page the link points to)</label>
                                <input type="text" name="destination_url" id="linkDestination" class="form-control" placeholder="https://">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Follow type</label>
                                <select name="rel" id="linkRel" class="form-select">
                                    <option value="">Not specified</option>
                                    @foreach ($rels as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Domain Rating (0-100)</label>
                                <input type="number" name="dr" id="linkDr" class="form-control" min="0" max="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="link_status" id="linkStatus" class="form-select" required>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Save Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isManager = @json($isManager);
            const storeUrl = @json(route('admin.offsite-seo.links.store'));
            const tasksUrl = @json(route('admin.offsite-seo.links.tasks'));
            const today = @json(now()->toDateString());
            const modal = new bootstrap.Modal(document.getElementById('linkModal'));
            const $task = $('#linkTask');

            function loadTasks(userId, selected, selectedLabel) {
                $task.html('<option value="">Loading…</option>');
                if (isManager && !userId) {
                    $task.html('<option value="">Select an employee first</option>');
                    return;
                }
                $.get(tasksUrl, { user_id: userId }, function(tasks) {
                    $task.html('<option value="">Select task</option>');
                    tasks.forEach(function(task) {
                        $task.append($('<option>').val(task.id).text(task.label));
                    });
                    // keep the current task selectable even if it is older than the list covers
                    if (selected && !$task.find('option[value="' + selected + '"]').length) {
                        $task.append($('<option>').val(selected).text(selectedLabel));
                    }
                    $task.val(selected || '');
                }).fail(function() {
                    $task.html('<option value="">Could not load tasks</option>');
                });
            }

            $('#linkUser').on('change', function() {
                loadTasks($(this).val(), null, null);
            });

            $('#addLinkBtn').on('click', function() {
                $('#linkForm').attr('action', storeUrl)[0].reset();
                $('#linkMethod').val('POST');
                $('#linkModalTitle').text('Add Link');
                $('#linkDate').val(today);
                loadTasks(isManager ? $('#linkUser').val() : null, null, null);
                modal.show();
            });

            $(document).on('click', '.js-edit', function() {
                const d = $(this).data();
                $('#linkForm').attr('action', d.url);
                $('#linkMethod').val('PUT');
                $('#linkModalTitle').text('Edit Link');
                $('#linkUser').val(d.user);
                $('#linkKeyword').val(d.keyword);
                $('#linkUrl').val(d.link);
                $('#linkDestination').val(d.destination === '-' ? '' : d.destination);
                $('#linkRel').val(d.rel || '');
                $('#linkDr').val(d.dr);
                $('#linkStatus').val(d.status);
                $('#linkDate').val(d.date);
                loadTasks(d.user, d.task, d.taskLabel);
                modal.show();
            });

            $(document).on('change', '.js-status', function() {
                const select = this;
                $.ajax({
                    url: $(select).data('url'),
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}', link_status: $(select).val() },
                    success: function(response) {
                        $(select).find('option[value=""]').remove();
                        toastr.success(response.message);
                    },
                    error: function() {
                        toastr.error('Could not update the link status.');
                    }
                });
            });
        });
    </script>
</x-app-layout>
