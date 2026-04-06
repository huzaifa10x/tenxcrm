@extends('layouts.crm')

@section('title', $lead->title)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($lead->title, 40) }}</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('crm.leads.destroy', $lead) }}"
              onsubmit="return confirm('Delete this lead?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="row g-4">
    {{-- Left column: details --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $lead->title }}</h4>
                        @if($lead->label)
                            <span class="badge bg-{{ $lead->label_badge_class }} fs-6">
                                {{ $lead->label }}
                            </span>
                        @endif
                    </div>
                    <small class="text-muted">ID #{{ $lead->id }}</small>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="text-muted small">Source Origin</label>
                        <p class="mb-0">{{ $lead->source_origin ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Owner</label>
                        <p class="mb-0">{{ $lead->owner ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Lead Created</label>
                        <p class="mb-0">{{ $lead->lead_created_at?->format('d M Y H:i') ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Next Activity</label>
                        <p class="mb-0">{{ $lead->next_activity_at?->format('d M Y') ?? '—' }}</p>
                    </div>
                    @if($lead->notes)
                        <div class="col-12">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $lead->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Notes / Activity timeline --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-chat-left-dots me-2"></i>Notes</h6>
            </div>
            <div class="card-body">
                {{-- Add note form --}}
                <form method="POST" action="{{ route('crm.leads.notes.store', $lead) }}" class="mb-4">
                    @csrf
                    <div class="mb-2">
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                  rows="3" placeholder="Add a note…" required></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add Note
                    </button>
                </form>

                {{-- Notes list --}}
                @forelse($lead->crmNotes as $note)
                    <div class="note-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold small">{{ $note->author ?? 'System' }}</span>
                                <span class="text-muted small ms-2">
                                    {{ $note->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <form method="POST"
                                  action="{{ route('crm.leads.notes.destroy', [$lead, $note]) }}"
                                  onsubmit="return confirm('Delete note?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-link text-danger p-0">
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

    {{-- Right column: quick actions --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Update Label</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('crm.leads.label', $lead) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select name="label" class="form-select">
                            @foreach(\App\Models\Lead::LABELS as $lbl)
                                <option value="{{ $lbl }}" @selected($lead->label === $lbl)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Update Label</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mt-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Created:</strong> {{ $lead->created_at->format('d M Y H:i') }}</div>
                <div><strong>Updated:</strong> {{ $lead->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
