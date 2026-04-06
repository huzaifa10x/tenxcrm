@extends('layouts.crm')

@section('title', 'Activities')

@section('breadcrumb')
    <li class="breadcrumb-item active">Activities</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('crm.activities.export', request()->query()) }}"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export
        </a>
        <a href="{{ route('crm.activities.import.form') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <button class="btn btn-sm btn-success d-flex align-items-center gap-1"
                id="btnSchedule" data-bs-toggle="modal" data-bs-target="#scheduleModal">
            <i class="bi bi-plus-lg"></i> Activity
        </button>
    </div>
@endsection

@push('styles')
<style>
/* ── Tab strip ── */
.activity-tabs .nav-link {
    color: #64748b;
    font-size: .84rem;
    padding: .45rem .85rem;
    border-radius: 0;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
}
.activity-tabs .nav-link:hover { color: #0d6efd; }
.activity-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: transparent;
    font-weight: 600;
}

/* ── Type pill filter ── */
.type-pill {
    font-size: .78rem;
    border-radius: 999px;
    padding: .2rem .75rem;
    cursor: pointer;
    border: 1px solid #dee2e6;
    background: #fff;
    color: #64748b;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    transition: all .15s;
}
.type-pill:hover, .type-pill.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

/* ── Done checkbox circle ── */
.done-toggle {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 2px solid #dee2e6;
    background: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
    flex-shrink: 0;
}
.done-toggle.is-done {
    background: #198754;
    border-color: #198754;
    color: #fff;
}
.done-toggle:hover { border-color: #198754; }

/* ── Row done state ── */
tr.row-done td:not(.done-col) { opacity: .5; text-decoration: line-through; }

/* ── Activity type icon badge ── */
.type-badge {
    width: 26px; height: 26px;
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
}

/* ── Modal ── */
.modal-activity .modal-dialog { max-width: 860px; }
.modal-activity .type-btn {
    width: 40px; height: 40px;
    border-radius: 8px;
    border: 1.5px solid #dee2e6;
    background: #f8f9fa;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
    transition: all .15s;
}
.modal-activity .type-btn:hover,
.modal-activity .type-btn.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}
.modal-activity .schedule-calendar {
    border-left: 1px solid #e2e8f0;
    min-height: 460px;
    overflow-y: auto;
}
.cal-hour {
    height: 48px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: flex-start;
    padding: 2px 8px;
    font-size: .7rem;
    color: #94a3b8;
    position: relative;
}
.notes-textarea { background: #fefce8; border: 1px solid #fef08a; }
.notes-textarea:focus { background: #fefce8; border-color: #eab308; box-shadow: none; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">
        Activities
        <span class="badge bg-secondary ms-2 fw-normal small">{{ $counts['all'] }}</span>
    </h4>
</div>

{{-- ── Period tabs (mimics Pipedrive) ── --}}
<div class="bg-white border rounded-top-3 border-bottom-0 px-2">
    <ul class="nav activity-tabs" id="periodTabs">
        @php
            $periods = [
                ''          => 'To-do',
                'all'       => 'All',
                'overdue'   => 'Overdue',
                'today'     => 'Today',
                'tomorrow'  => 'Tomorrow',
                'this_week' => 'This week',
                'next_week' => 'Next week',
            ];
            $currentPeriod = request('period', '');
            $currentDone   = request('done', 'To do');
        @endphp
        @foreach($periods as $pval => $plabel)
            <li class="nav-item">
                <a class="nav-link {{ ($currentPeriod === $pval && ($pval !== 'all' || $currentDone !== 'To do')) || ($pval === '' && $currentPeriod === '' && $currentDone === 'To do') ? 'active' : '' }}"
                   href="{{ route('crm.activities.index', array_merge(request()->except(['period','done','page']),
                       $pval === 'all' ? ['done' => 'all'] : ($pval === '' ? [] : ['period' => $pval])
                   )) }}">
                    {{ $plabel }}
                    @if(isset($counts[str_replace(' ', '_', strtolower($plabel))]))
                        <span class="badge bg-light text-dark ms-1">
                            {{ $counts[str_replace(' ', '_', strtolower($plabel))] }}
                        </span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- ── Type pill filter + search bar ── --}}
<div class="bg-white border border-top-0 rounded-bottom-0 px-3 py-2 d-flex align-items-center gap-3 flex-wrap">
    {{-- Type pills --}}
    <div class="d-flex gap-1 flex-wrap">
        <a href="{{ route('crm.activities.index', array_merge(request()->except(['type','page']))) }}"
           class="type-pill {{ !request('type') ? 'active' : '' }}">All</a>
        @foreach(\App\Models\Activity::TYPES as $t)
            <a href="{{ route('crm.activities.index', array_merge(request()->except(['type','page']), ['type' => $t])) }}"
               class="type-pill {{ request('type') === $t ? 'active' : '' }}">
                <i class="bi {{ \App\Models\Activity::TYPE_ICONS[$t] }}"></i>
                {{ $t }}
            </a>
        @endforeach
    </div>

    <div class="ms-auto d-flex gap-2">
        {{-- Search --}}
        <form method="GET" action="{{ route('crm.activities.index') }}" class="d-flex gap-2">
            @foreach(request()->except(['search','page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Search…" value="{{ request('search') }}" style="width:180px">
            {{-- Assigned to --}}
            <select name="assigned_to" class="form-select form-select-sm" style="width:140px"
                    onchange="this.form.submit()">
                <option value="">All users</option>
                @foreach($assignees as $u)
                    <option value="{{ $u }}" @selected(request('assigned_to') === $u)>{{ $u }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('crm.activities.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x"></i>
            </a>
        </form>
    </div>
</div>

{{-- ── Activities Table ── --}}
<div class="card border-0 shadow-sm rounded-top-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-crm table-hover mb-0" id="activitiesTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px" class="done-col">Done</th>
                        <th style="width:36px"></th>
                        <th>Subject</th>
                        <th>Deal / Lead</th>
                        <th>Contact person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Organization</th>
                        <th>Due date</th>
                        <th>Duration</th>
                        <th>Assigned to user</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($activities as $activity)
                    <tr class="{{ $activity->is_done ? 'row-done' : '' }}" id="row-{{ $activity->id }}">
                        {{-- Done toggle --}}
                        <td class="done-col">
                            <button class="done-toggle {{ $activity->is_done ? 'is-done' : '' }} border-0"
                                    onclick="toggleDone({{ $activity->id }}, this)"
                                    title="{{ $activity->done }}">
                                @if($activity->is_done)
                                    <i class="bi bi-check-lg"></i>
                                @endif
                            </button>
                        </td>
                        {{-- Type icon --}}
                        <td>
                            <span class="type-badge bg-{{ $activity->type_color }}-subtle text-{{ $activity->type_color }}">
                                <i class="bi {{ $activity->type_icon }}"></i>
                            </span>
                        </td>
                        {{-- Subject --}}
                        <td>
                            <a href="{{ route('crm.activities.show', $activity) }}"
                               class="text-decoration-none text-dark fw-semibold">
                                {{ $activity->subject }}
                            </a>
                            @if($activity->priority === 'High')
                                <span class="badge bg-danger ms-1 small">High</span>
                            @endif
                        </td>
                        {{-- Deal / Lead --}}
                        <td class="small text-muted">
                            @if($activity->deal)
                                <a href="{{ route('crm.deals.show', $activity->deal) }}"
                                   class="text-decoration-none text-primary">
                                    {{ $activity->deal->title }}
                                </a>
                            @elseif($activity->lead)
                                <a href="{{ route('crm.leads.show', $activity->lead) }}"
                                   class="text-decoration-none text-info">
                                    {{ $activity->lead->title }}
                                </a>
                            @elseif($activity->deal_name)
                                <span class="badge bg-light text-dark border">{{ Str::limit($activity->deal_name, 30) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        {{-- Contact --}}
                        <td class="small">{{ $activity->contact_person ?? '—' }}</td>
                        {{-- Email --}}
                        <td class="small">
                            @if($activity->primary_email)
                                <a href="mailto:{{ $activity->primary_email }}" class="text-decoration-none text-primary small">
                                    {{ $activity->primary_email }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        {{-- Phone --}}
                        <td class="small">
                            @if($activity->primary_phone)
                                <a href="tel:{{ $activity->primary_phone }}" class="text-decoration-none text-dark">
                                    {{ $activity->primary_phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        {{-- Organization --}}
                        <td class="small text-muted">{{ $activity->organization ?? '—' }}</td>
                        {{-- Due date --}}
                        <td class="small {{ $activity->due_date && $activity->due_date->isPast() && !$activity->is_done ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ $activity->due_date?->format('M j, Y') ?? '—' }}
                            @if($activity->due_time)
                                <br><small>{{ substr($activity->due_time, 0, 5) }}</small>
                            @endif
                        </td>
                        {{-- Duration --}}
                        <td class="small text-muted">{{ $activity->duration ?? '—' }}</td>
                        {{-- Assigned --}}
                        <td class="small">{{ $activity->assigned_to ?? '—' }}</td>
                        {{-- Actions --}}
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-xs btn-sm btn-outline-secondary"
                                        onclick="openEditModal({{ $activity->id }})"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('crm.activities.destroy', $activity) }}"
                                      onsubmit="return confirm('Delete this activity?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                            No activities found.
                            <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                Schedule one now
                            </button>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <p class="text-muted small mb-0">
        Showing {{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}
        of {{ $activities->total() }} activities
    </p>
    {{ $activities->links() }}
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     SCHEDULE AN ACTIVITY MODAL  (matches Pipedrive Image 2 exactly)
══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-activity" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">

            {{-- Header --}}
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-semibold" id="scheduleModalLabel">Schedule an activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="scheduleForm" method="POST" action="{{ route('crm.activities.store') }}">
                @csrf
                <div class="modal-body p-0">
                    <div class="row g-0">

                        {{-- ── Left pane ── --}}
                        <div class="col-md-8 p-4">

                            {{-- Subject input --}}
                            <div class="mb-3">
                                <input type="text" name="subject" id="modalSubject"
                                       class="form-control form-control-lg fw-semibold border-0 border-bottom rounded-0 px-0"
                                       placeholder="Call" required
                                       style="font-size:1.1rem; box-shadow:none">
                            </div>

                            {{-- Type buttons --}}
                            <div class="d-flex gap-2 mb-4">
                                @foreach(\App\Models\Activity::TYPES as $t)
                                    <button type="button"
                                            class="type-btn {{ $t === 'Call' ? 'active' : '' }}"
                                            data-type="{{ $t }}"
                                            title="{{ $t }}"
                                            onclick="selectType(this)">
                                        <i class="bi {{ \App\Models\Activity::TYPE_ICONS[$t] }}"></i>
                                    </button>
                                @endforeach
                                <input type="hidden" name="type" id="modalType" value="Call">
                            </div>

                            {{-- Date / Time row --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-clock text-muted"></i>
                                <input type="date" name="due_date" id="modalDueDate"
                                       class="form-control form-control-sm"
                                       value="{{ now()->format('Y-m-d') }}"
                                       style="width:150px" onchange="updateCalendarHeader()">
                                <input type="time" name="due_time" id="modalDueTime"
                                       class="form-control form-control-sm"
                                       placeholder="h:mm PM" style="width:120px">
                                <span class="text-muted">–</span>
                                <input type="time" name="end_time" id="modalEndTime"
                                       class="form-control form-control-sm"
                                       placeholder="h:mm PM" style="width:120px">
                                <input type="date" name="_end_date"
                                       class="form-control form-control-sm"
                                       value="{{ now()->format('Y-m-d') }}"
                                       style="width:150px">
                            </div>

                            {{-- Priority --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-flag text-muted"></i>
                                <select name="priority" class="form-select form-select-sm w-auto">
                                    <option value="Normal">Priority</option>
                                    <option value="Normal">Normal</option>
                                    <option value="High">High</option>
                                </select>
                            </div>

                            {{-- Extra fields toggle --}}
                            <div class="mb-3">
                                <span class="text-muted small">Add </span>
                                <button type="button" class="btn btn-link btn-sm p-0 text-primary"
                                        onclick="toggleExtra('location')">location</button>,
                                <button type="button" class="btn btn-link btn-sm p-0 text-primary"
                                        onclick="toggleExtra('video_call')">video call</button>,
                                <button type="button" class="btn btn-link btn-sm p-0 text-primary"
                                        onclick="toggleExtra('description')">description</button>
                            </div>

                            <div id="extra-location" class="mb-3" style="display:none">
                                <input type="text" name="location" class="form-control form-control-sm"
                                       placeholder="Location">
                            </div>
                            <div id="extra-video_call" class="mb-3" style="display:none">
                                <input type="url" name="video_call_url" class="form-control form-control-sm"
                                       placeholder="Video call URL">
                            </div>
                            <div id="extra-description" class="mb-3" style="display:none">
                                <input type="text" name="description" class="form-control form-control-sm"
                                       placeholder="Description">
                            </div>

                            {{-- Free / Busy --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-calendar2-minus text-muted"></i>
                                <select name="visible_to" class="form-select form-select-sm w-auto">
                                    <option>Free</option>
                                    <option>Busy</option>
                                </select>
                                <i class="bi bi-info-circle text-muted" title="Shown on synced calendar"></i>
                            </div>

                            {{-- Notes (yellow textarea) --}}
                            <div class="d-flex gap-2 mb-3">
                                <i class="bi bi-sticky text-muted mt-1"></i>
                                <textarea name="note" rows="4"
                                          class="form-control form-control-sm notes-textarea flex-grow-1"
                                          placeholder="Notes are visible within Pipedrive, but not to event guests"></textarea>
                            </div>

                            {{-- Assigned to --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-person-circle text-muted"></i>
                                <select name="assigned_to" class="form-select form-select-sm"
                                        style="max-width:260px">
                                    <option value="">— Assign to —</option>
                                    @foreach($assignees as $u)
                                        <option value="{{ $u }}">{{ $u }}</option>
                                    @endforeach
                                    <option value="10X">10X (You)</option>
                                </select>
                            </div>

                            {{-- Links: Deal/Lead, People, Organization --}}
                            <div class="d-flex gap-1 mb-1">
                                <i class="bi bi-link-45deg text-muted mt-1"></i>
                                <div class="flex-grow-1 d-flex flex-column gap-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-currency-dollar text-muted"></i>
                                        </span>
                                        <select name="deal_id" class="form-select form-select-sm border-start-0">
                                            <option value="">Deal or Lead</option>
                                            @foreach(\App\Models\Deal::orderBy('title')->get(['id','title']) as $d)
                                                <option value="{{ $d->id }}">{{ $d->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-person text-muted"></i>
                                        </span>
                                        <input type="text" name="contact_person"
                                               class="form-control form-control-sm border-start-0"
                                               placeholder="People">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-building text-muted"></i>
                                        </span>
                                        <input type="text" name="organization"
                                               class="form-control form-control-sm border-start-0"
                                               placeholder="Organization">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Right pane: mini calendar ── --}}
                        <div class="col-md-4 schedule-calendar p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-semibold small" id="calHeader">
                                    {{ now()->format('l, F jS') }}
                                </span>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-xs btn-sm btn-outline-secondary p-0 px-1"
                                            onclick="shiftCalDay(-1)"><i class="bi bi-chevron-left"></i></button>
                                    <button type="button" class="btn btn-xs btn-sm btn-outline-secondary p-0 px-1"
                                            onclick="shiftCalDay(1)"><i class="bi bi-chevron-right"></i></button>
                                </div>
                            </div>

                            {{-- Active type badge --}}
                            <div class="mb-3">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="calTypeBadge">
                                    <i class="bi bi-telephone-fill me-1"></i> Call
                                </button>
                            </div>

                            {{-- Hour grid --}}
                            <div id="hourGrid">
                                @for($h = 0; $h < 24; $h++)
                                    <div class="cal-hour">
                                        <span>{{ $h === 0 ? '12 AM' : ($h < 12 ? $h . ' AM' : ($h === 12 ? '12 PM' : ($h - 12) . ' PM')) }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                    </div>{{-- /row --}}
                </div>{{-- /modal-body --}}

                {{-- Footer --}}
                <div class="modal-footer border-top px-4 py-3 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-link text-muted p-0" title="Settings">
                        <i class="bi bi-gear fs-5"></i>
                    </button>
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check mb-0">
                            <input type="checkbox" name="done" value="Done" class="form-check-input" id="markDoneCheck">
                            <label class="form-check-label small" for="markDoneCheck">Mark as done</label>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4">Save</button>
                        <button type="button" class="btn btn-outline-secondary" title="Save and duplicate">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Edit modal (loads via AJAX) ── --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="editModalContent">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Type selector ── */
function selectType(btn) {
    document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const type = btn.dataset.type;
    document.getElementById('modalType').value = type;
    const icons = @json(\App\Models\Activity::TYPE_ICONS);
    document.getElementById('calTypeBadge').innerHTML =
        `<i class="bi ${icons[type]} me-1"></i> ${type}`;
}

/* ── Toggle extra fields ── */
function toggleExtra(field) {
    const el = document.getElementById('extra-' + field);
    el.style.display = el.style.display === 'none' ? '' : 'none';
}

/* ── Calendar header ── */
function updateCalendarHeader() {
    const val = document.getElementById('modalDueDate').value;
    if (!val) return;
    const d = new Date(val + 'T00:00:00');
    const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const months = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];
    const suffix = n => [,'st','nd','rd'][((n%100-20)%10)||n%100] || 'th';
    document.getElementById('calHeader').textContent =
        `${days[d.getDay()]}, ${months[d.getMonth()]} ${d.getDate()}${suffix(d.getDate())}`;
}

function shiftCalDay(dir) {
    const inp = document.getElementById('modalDueDate');
    const d = new Date(inp.value + 'T00:00:00');
    d.setDate(d.getDate() + dir);
    inp.value = d.toISOString().split('T')[0];
    updateCalendarHeader();
}

/* ── Toggle done via AJAX ── */
function toggleDone(id, btn) {
    fetch(`/crm/activities/${id}/done`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const isDone = data.done === 'Done';
        btn.classList.toggle('is-done', isDone);
        btn.innerHTML = isDone ? '<i class="bi bi-check-lg"></i>' : '';
        const row = document.getElementById('row-' + id);
        row.classList.toggle('row-done', isDone);
    });
}

/* ── Open edit modal ── */
function openEditModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    document.getElementById('editModalContent').innerHTML =
        '<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div></div>';
    modal.show();
    fetch(`/crm/activities/${id}/edit`)
        .then(r => r.text())
        .then(html => {
            document.getElementById('editModalContent').innerHTML = html;
        });
}

/* ── Handle done checkbox in modal ── */
document.getElementById('markDoneCheck').addEventListener('change', function() {
    this.value = this.checked ? 'Done' : 'To do';
});

/* ── Auto-scroll calendar to 8 AM on open ── */
document.getElementById('scheduleModal').addEventListener('shown.bs.modal', () => {
    const grid = document.getElementById('hourGrid');
    grid.scrollTop = 8 * 48; // 48px per hour
});
</script>
@endpush
