@extends('layouts.crm')

@section('title', $activity->subject)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.activities.index') }}">Activities</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($activity->subject, 40) }}</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.activities.edit', $activity) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('crm.activities.done', $activity) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm {{ $activity->is_done ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-lg me-1"></i>{{ $activity->is_done ? 'Done' : 'Mark Done' }}
            </button>
        </form>
        <form method="POST" action="{{ route('crm.activities.destroy', $activity) }}"
              onsubmit="return confirm('Delete this activity?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="type-badge bg-{{ $activity->type_color }}-subtle text-{{ $activity->type_color }}"
                          style="width:44px;height:44px;font-size:1.2rem;border-radius:50%;display:flex;align-items:center;justify-content:center">
                        <i class="bi {{ $activity->type_icon }}"></i>
                    </span>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $activity->subject }}</h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-{{ $activity->type_color }}">{{ $activity->type }}</span>
                            <span class="badge {{ $activity->is_done ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $activity->done }}
                            </span>
                            @if($activity->priority === 'High')
                                <span class="badge bg-danger">High Priority</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Due Date</label>
                        <p class="mb-0 fw-semibold {{ $activity->due_date?->isPast() && !$activity->is_done ? 'text-danger' : '' }}">
                            {{ $activity->due_date?->format('d M Y') ?? '—' }}
                            @if($activity->due_time)
                                <small class="text-muted d-block">{{ substr($activity->due_time, 0, 5) }}</small>
                            @endif
                        </p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Duration</label>
                        <p class="mb-0">{{ $activity->duration ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Assigned To</label>
                        <p class="mb-0">{{ $activity->assigned_to ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Organization</label>
                        <p class="mb-0">{{ $activity->organization ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Contact Person</label>
                        <p class="mb-0">{{ $activity->contact_person ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Priority</label>
                        <p class="mb-0">{{ $activity->priority }}</p>
                    </div>

                    @if($activity->primary_email)
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $activity->primary_email }}">{{ $activity->primary_email }}</a>
                            </p>
                        </div>
                    @endif
                    @if($activity->primary_phone)
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0">
                                <a href="tel:{{ $activity->primary_phone }}">{{ $activity->primary_phone }}</a>
                            </p>
                        </div>
                    @endif
                    @if($activity->location)
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0">{{ $activity->location }}</p>
                        </div>
                    @endif
                    @if($activity->video_call_url)
                        <div class="col-12">
                            <label class="text-muted small">Video Call</label>
                            <p class="mb-0"><a href="{{ $activity->video_call_url }}" target="_blank">{{ $activity->video_call_url }}</a></p>
                        </div>
                    @endif
                    @if($activity->description)
                        <div class="col-12">
                            <label class="text-muted small">Description</label>
                            <p class="mb-0">{{ $activity->description }}</p>
                        </div>
                    @endif
                    @if($activity->note)
                        <div class="col-12">
                            <label class="text-muted small">Note</label>
                            <div class="p-3 rounded" style="background:#fefce8;border:1px solid #fef08a">
                                {{ $activity->note }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Linked records --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Linked Records</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <span class="text-muted">Deal / Lead:</span><br>
                    @if($activity->deal)
                        <a href="{{ route('crm.deals.show', $activity->deal) }}">{{ $activity->deal->title }}</a>
                    @elseif($activity->lead)
                        <a href="{{ route('crm.leads.show', $activity->lead) }}">{{ $activity->lead->title }}</a>
                    @elseif($activity->deal_name)
                        {{ $activity->deal_name }}
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Timestamps --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Created:</strong> {{ $activity->created_at->format('d M Y H:i') }}</div>
                <div><strong>Updated:</strong> {{ $activity->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
