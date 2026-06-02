<x-app-layout>
    <x-slot name="header">{{ __('CRM — Deal') }}</x-slot>

    @include('pipedrive._styles')

    <div class="crm-page">
        @if (session('success'))
            <div class="alert alert-success py-2" style="font-size:.85rem;">{{ session('success') }}</div>
        @endif

        {{-- ── Header ─────────────────────────────────────────── --}}
        <div class="crm-toolbar">
            <div>
                <a href="{{ route('pipedrive.deals.index') }}" class="text-decoration-none text-muted"
                    style="font-size:.78rem;">
                    <i class="bi bi-arrow-left me-1"></i>{{ $deal->pipeline->name }}
                </a>
                <h4 class="mt-1">{{ $deal->title }}</h4>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if ($deal->status === 'open')
                    <form method="POST" action="{{ route('pipedrive.deals.won', $deal) }}" class="d-inline">@csrf
                        <button class="btn btn-success btn-sm"><i class="bi bi-trophy me-1"></i>Won</button>
                    </form>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#lostModal"><i
                            class="bi bi-x-circle me-1"></i>Lost</button>
                @else
                    <span class="badge bg-{{ $deal->status === 'won' ? 'success' : 'danger' }} px-3 py-2">
                        {{ strtoupper($deal->status) }}
                    </span>
                @endif
                @can('update', $deal)
                    <a href="{{ route('pipedrive.deals.edit', $deal) }}" class="btn btn-sm btn-outline-warning"><i
                            class="bi bi-pencil"></i></a>
                @endcan
                @can('delete', $deal)
                    <form method="POST" action="{{ route('pipedrive.deals.destroy', $deal) }}"
                        onsubmit="return confirm('Delete this deal?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                @endcan
            </div>
        </div>

        {{-- ── Stage timeline ─────────────────────────────────── --}}
        @php
            $currentPos = $deal->stage->position;
        @endphp
        <div class="crm-stage-bar">
            @foreach ($stages as $stage)
                @php
                    $cls =
                        $stage->position < $currentPos
                            ? 'passed'
                            : ($stage->id === $deal->stage_id
                                ? 'current'
                                : 'future');
                    $days = $stageDurations[$stage->id] ?? 0;
                @endphp
                <div class="crm-stage-seg {{ $cls }}">
                    {{ $stage->name }}
                    <small>{{ $cls === 'future' ? '—' : $days . 'd' }}</small>
                </div>
            @endforeach
        </div>

        <div class="row g-3">
            {{-- ── Sidebar ────────────────────────────────────── --}}
            <div class="col-lg-4">
                {{-- Summary --}}
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-card-text me-1"></i>Summary</div>
                    <div class="crm-panel-body">
                        <div class="crm-meta-row"><span>Value</span><span>{{ $deal->currency }}
                                {{ number_format((float) $deal->value, 2) }}</span></div>
                        <div class="crm-meta-row"><span>Stage</span><span><span class="badge"
                                    style="background:{{ $deal->stage->color }};color:#fff;">{{ $deal->stage->name }}</span></span>
                        </div>
                        <div class="crm-meta-row"><span>Status</span><span>{{ ucfirst($deal->status) }}</span></div>
                        <div class="crm-meta-row"><span>Label</span><span>{{ $deal->label ?: '—' }}</span></div>
                        <div class="crm-meta-row"><span>Owner</span><span>{{ $deal->owner?->name ?? '—' }}</span></div>
                        <div class="crm-meta-row"><span>Expected
                                close</span><span>{{ optional($deal->expected_close_date)->format('M d, Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Person --}}
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-person me-1"></i>Person</div>
                    <a class="crm-panel-body" href="{{ route('pipedrive.persons.edit', $deal->person->id) }}">
                        @if ($deal->person)
                            <div class="fw-semibold mb-1">{{ $deal->person->name }}</div>
                            @if ($deal->person->phone)
                                <div style="font-size:.82rem;"><i class="bi bi-telephone me-1 text-muted"></i><a
                                        href="tel:{{ $deal->person->phone }}">{{ $deal->person->phone }}</a></div>
                            @endif
                            @if ($deal->person->email)
                                <div style="font-size:.82rem;"><i class="bi bi-envelope me-1 text-muted"></i><a
                                        href="mailto:{{ $deal->person->email }}">{{ $deal->person->email }}</a></div>
                            @endif
                        @else
                            <span class="text-muted" style="font-size:.85rem;">No contact linked.</span>
                        @endif
                    </a>
                </div>

                {{-- Organization --}}
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-building me-1"></i>Organization</div>
                    <div class="crm-panel-body">
                        @if ($deal->organization)
                            <div class="fw-semibold mb-1">{{ $deal->organization->name }}</div>
                            @if ($deal->organization->website)
                                <div style="font-size:.82rem;"><i class="bi bi-link-45deg me-1 text-muted"></i><a
                                        href="{{ $deal->organization->website }}"
                                        target="_blank">{{ $deal->organization->website }}</a></div>
                            @endif
                        @else
                            <span class="text-muted" style="font-size:.85rem;">No organization linked.</span>
                        @endif
                    </div>
                </div>

                {{-- Overview --}}
                <div class="crm-panel">
                    <div class="crm-panel-head"><i class="bi bi-speedometer2 me-1"></i>Overview</div>
                    <div class="crm-panel-body">
                        <div class="crm-meta-row"><span>Deal age</span><span>{{ $deal->deal_age }} days</span></div>
                        <div class="crm-meta-row"><span>Inactive</span><span>{{ $deal->inactive_days }} days</span>
                        </div>
                        <div class="crm-meta-row"><span>Source</span><span>{{ $deal->source_origin ?? '—' }}</span>
                        </div>
                        <div class="crm-meta-row">
                            <span>Created</span><span>{{ $deal->created_at->format('M d, Y') }}</span></div>
                    </div>
                </div>
            </div>

            {{-- ── Main: activities + notes ───────────────────── --}}
            <div class="col-lg-8">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab"
                            data-bs-target="#tab-activity"><i class="bi bi-calendar2-check me-1"></i>Activity</button>
                    </li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-notes"><i
                                class="bi bi-sticky me-1"></i>Notes</button></li>
                </ul>

                <div class="tab-content">
                    {{-- Activity --}}
                    <div class="tab-pane fade show active" id="tab-activity">
                        <div class="crm-panel mb-3">
                            <div class="crm-panel-body">
                                <form method="POST" action="{{ route('pipedrive.activities.store', $deal) }}">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select name="type" class="form-select form-select-sm">
                                                @foreach (\App\Models\Crm\Activity::TYPES as $t)
                                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="subject" class="form-control form-control-sm"
                                                placeholder="Subject (e.g. Follow-up call)" required>
                                        </div>
                                        <div class="col-md-4"><input type="date" name="due_date"
                                                class="form-control form-control-sm"></div>
                                        <div class="col-md-4"><input type="time" name="due_time"
                                                class="form-control form-control-sm"></div>
                                        <div class="col-md-4"><input type="text" name="location"
                                                class="form-control form-control-sm" placeholder="Location"></div>
                                        <div class="col-12">
                                            <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Notes…"></textarea>
                                        </div>
                                    </div>
                                    <div class="text-end mt-2"><button class="btn btn-primary btn-sm"><i
                                                class="bi bi-plus-lg me-1"></i>Schedule activity</button></div>
                                </form>
                            </div>
                        </div>

                        <div class="crm-panel">
                            <div class="crm-panel-head">Timeline</div>
                            <div class="crm-panel-body">
                                @forelse ($deal->activities as $activity)
                                    <div class="crm-timeline-item {{ $activity->done ? 'done' : '' }}">
                                        <div class="crm-timeline-icon"><i class="bi {{ $activity->icon }}"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-semibold"
                                                    style="font-size:.85rem; {{ $activity->done ? 'text-decoration:line-through;opacity:.6;' : '' }}">{{ $activity->subject }}</span>
                                                <span class="text-muted"
                                                    style="font-size:.72rem;">{{ ucfirst($activity->type) }}</span>
                                            </div>
                                            @if ($activity->note)
                                                <div class="text-muted" style="font-size:.8rem;">
                                                    {{ $activity->note }}</div>
                                            @endif
                                            <div class="text-muted" style="font-size:.72rem;">
                                                @if ($activity->due_date)
                                                    <i
                                                        class="bi bi-clock me-1"></i>{{ $activity->due_date->format('M d, Y') }}
                                                    {{ $activity->due_time }}
                                                @endif
                                                · {{ $activity->user?->name }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            <form method="POST"
                                                action="{{ route('pipedrive.activities.toggle', $activity) }}">@csrf
                                                <button
                                                    class="btn btn-sm {{ $activity->done ? 'btn-success' : 'btn-outline-success' }}"
                                                    title="Mark done"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('pipedrive.activities.destroy', $activity) }}"
                                                onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="crm-empty"><i class="bi bi-calendar-x"></i>No activities yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="tab-pane fade" id="tab-notes">
                        <div class="crm-panel mb-3">
                            <div class="crm-panel-body">
                                <form method="POST" action="{{ route('pipedrive.notes.store', $deal) }}">
                                    @csrf
                                    <textarea name="content" class="form-control" rows="3" placeholder="Write a note…" required></textarea>
                                    <div class="text-end mt-2"><button class="btn btn-primary btn-sm"><i
                                                class="bi bi-plus-lg me-1"></i>Add note</button></div>
                                </form>
                            </div>
                        </div>

                        <div class="crm-panel">
                            <div class="crm-panel-head">Notes &amp; communication history</div>
                            <div class="crm-panel-body">
                                @forelse ($deal->notes as $note)
                                    <div class="crm-timeline-item">
                                        <div class="crm-timeline-icon" style="background:#fff9db;color:#e67700;"><i
                                                class="bi {{ $note->pinned ? 'bi-pin-angle-fill' : 'bi-sticky' }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div style="font-size:.85rem; white-space:pre-line;">{{ $note->content }}
                                            </div>
                                            <div class="text-muted" style="font-size:.72rem;">
                                                {{ $note->user?->name }} · {{ $note->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            <form method="POST" action="{{ route('pipedrive.notes.pin', $note) }}">
                                                @csrf
                                                <button
                                                    class="btn btn-sm {{ $note->pinned ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    title="Pin"><i class="bi bi-pin-angle"></i></button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('pipedrive.notes.destroy', $note) }}"
                                                onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="crm-empty"><i class="bi bi-sticky"></i>No notes yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lost reason modal --}}
    <div class="modal fade" id="lostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('pipedrive.deals.lost', $deal) }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Mark deal as lost</h6><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Reason (optional)</label>
                    <input type="text" name="lost_reason" class="form-control"
                        placeholder="e.g. Budget, chose competitor…">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm">Mark as lost</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
