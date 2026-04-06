@extends('layouts.crm')

@section('title', $deal->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.deals.index') }}">Deals</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($deal->title, 40) }}</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.deals.edit', $deal) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('crm.deals.destroy', $deal) }}"
              onsubmit="return confirm('Delete this deal?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="row g-4">

    {{-- ── Left: main details + notes ── --}}
    <div class="col-lg-8">

        {{-- Header card --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $deal->title }}</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-{{ $deal->status_badge_class }} fs-6">{{ $deal->status }}</span>
                            @if($deal->stage)
                                <span class="badge bg-{{ $deal->stage_badge_class }}">{{ $deal->stage }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fs-4 fw-bold text-success">
                            {{ $deal->value ? number_format($deal->value, 2) . ' ' . $deal->currency : '—' }}
                        </div>
                        <small class="text-muted">Deal Value</small>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Organization</label>
                        <p class="mb-0 fw-semibold">{{ $deal->organization ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Contact Person</label>
                        <p class="mb-0">{{ $deal->contact_person ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Owner</label>
                        <p class="mb-0">{{ $deal->owner ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Pipeline</label>
                        <p class="mb-0">{{ $deal->pipeline ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Source Origin</label>
                        <p class="mb-0">{{ $deal->source_origin ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Source Channel</label>
                        <p class="mb-0">{{ $deal->source_channel ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Probability</label>
                        <p class="mb-0">{{ $deal->probability !== null ? $deal->probability . '%' : '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Weighted Value</label>
                        <p class="mb-0">
                            {{ $deal->weighted_value ? number_format($deal->weighted_value, 2) . ' ' . $deal->currency : '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Creator</label>
                        <p class="mb-0">{{ $deal->creator ?? '—' }}</p>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Expected Close</label>
                        <p class="mb-0">{{ $deal->expected_close_date?->format('d M Y') ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Last Activity</label>
                        <p class="mb-0">{{ $deal->last_activity_date?->format('d M Y') ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Deal Created</label>
                        <p class="mb-0">{{ $deal->deal_created_at?->format('d M Y H:i') ?? '—' }}</p>
                    </div>

                    @if($deal->lost_reason)
                        <div class="col-12">
                            <label class="text-muted small">Lost Reason</label>
                            <p class="mb-0 text-danger">{{ $deal->lost_reason }}</p>
                        </div>
                    @endif

                    @if($deal->notes)
                        <div class="col-12">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $deal->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Activity summary --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-activity me-2"></i>Activity Summary</h6>
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="fs-4 fw-bold">{{ $deal->total_activities }}</div>
                        <div class="text-muted small">Total</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-success">{{ $deal->done_activities }}</div>
                        <div class="text-muted small">Done</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-warning">{{ $deal->activities_to_do }}</div>
                        <div class="text-muted small">To Do</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes timeline --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-chat-left-dots me-2"></i>Notes
                    <span class="badge bg-secondary ms-1">{{ $deal->crmNotes->count() }}</span>
                </h6>
            </div>
            <div class="card-body">
                {{-- Add note --}}
                <form method="POST" action="{{ route('crm.deals.notes.store', $deal) }}" class="mb-4">
                    @csrf
                    <textarea name="content" rows="3"
                              class="form-control @error('content') is-invalid @enderror mb-2"
                              placeholder="Add a note…" required></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add Note
                    </button>
                </form>

                @forelse($deal->crmNotes as $note)
                    <div class="note-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold small">{{ $note->author ?? 'System' }}</span>
                                <span class="text-muted small ms-2">
                                    {{ $note->created_at->diffForHumans() }}
                                    · {{ $note->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <form method="POST"
                                  action="{{ route('crm.deals.notes.destroy', [$deal, $note]) }}"
                                  onsubmit="return confirm('Delete note?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-link text-danger p-0 btn-sm">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                        <p class="mb-0 mt-1">{{ $note->content }}</p>
                    </div>
                @empty
                    <p class="text-muted small">No notes yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Right: action panel ── --}}
    <div class="col-lg-4">

        {{-- Update Status --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Update Status</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('crm.deals.status', $deal) }}" id="statusForm">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select name="status" class="form-select" id="statusSelect"
                                onchange="toggleLostReason(this.value)">
                            @foreach(\App\Models\Deal::STATUSES as $s)
                                <option value="{{ $s }}" @selected($deal->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="lostReasonWrap" class="mb-3"
                         style="{{ $deal->status === 'Lost' ? '' : 'display:none' }}">
                        <label class="form-label small fw-semibold">Lost Reason</label>
                        <textarea name="lost_reason" class="form-control form-control-sm" rows="2"
                                  placeholder="Why was this deal lost?">{{ $deal->lost_reason }}</textarea>
                    </div>
                    <button class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        {{-- Update Stage --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Move Stage</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('crm.deals.stage', $deal) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select name="stage" class="form-select">
                            @foreach(\App\Models\Deal::STAGES as $st)
                                <option value="{{ $st }}" @selected($deal->stage === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-outline-primary w-100">Move Stage</button>
                </form>
            </div>
        </div>

        {{-- Product info if present --}}
        @if($deal->product_name)
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-box me-2"></i>Product</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-1"><strong>Name:</strong> {{ $deal->product_name }}</div>
                    <div class="mb-1"><strong>Qty:</strong> {{ $deal->product_quantity ?? '—' }}</div>
                    <div><strong>Amount:</strong>
                        {{ $deal->product_amount ? number_format($deal->product_amount, 2) . ' ' . $deal->currency : '—' }}
                    </div>
                </div>
            </div>
        @endif

        {{-- Meta --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Pipedrive ID:</strong> {{ $deal->pipedrive_id ?? '—' }}</div>
                <div class="mb-1"><strong>Archive:</strong> {{ $deal->archive_status ?? '—' }}</div>
                <div class="mb-1"><strong>Visible to:</strong> {{ $deal->visible_to ?? '—' }}</div>
                <div class="mb-1"><strong>Won:</strong> {{ $deal->won_time?->format('d M Y') ?? '—' }}</div>
                <div class="mb-1"><strong>Lost:</strong> {{ $deal->lost_time?->format('d M Y') ?? '—' }}</div>
                <div class="mb-1"><strong>Record created:</strong> {{ $deal->created_at->format('d M Y H:i') }}</div>
                <div><strong>Last updated:</strong> {{ $deal->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleLostReason(val) {
    document.getElementById('lostReasonWrap').style.display = val === 'Lost' ? '' : 'none';
}
</script>
@endpush
