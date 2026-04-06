@extends('layouts.crm')

@section('title', 'Leads')

@section('breadcrumb')
    <li class="breadcrumb-item active">Leads</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.leads.export', request()->query()) }}"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export
        </a>
        <a href="{{ route('crm.leads.import.form') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <a href="{{ route('crm.leads.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Lead
        </a>
    </div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Leads
        <span class="badge bg-secondary ms-2 fw-normal">{{ $leads->total() }}</span>
    </h4>
</div>

{{-- Filters --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('crm.leads.index') }}" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search title, owner…" value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select name="label" class="form-select form-select-sm">
                    <option value="">All Labels</option>
                    @foreach(\App\Models\Lead::LABELS as $l)
                        <option value="{{ $l }}" @selected(($filters['label'] ?? '') === $l)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="owner" class="form-select form-select-sm">
                    <option value="">All Owners</option>
                    @foreach($owners as $o)
                        <option value="{{ $o }}" @selected(($filters['owner'] ?? '') === $o)>{{ $o }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="source_origin" class="form-select form-select-sm">
                    <option value="">All Sources</option>
                    @foreach($sources as $s)
                        <option value="{{ $s }}" @selected(($filters['source_origin'] ?? '') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ $filters['date_from'] ?? '' }}" title="From date">
            </div>
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ $filters['date_to'] ?? '' }}" title="To date">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('crm.leads.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-crm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Label</th>
                        <th>Source</th>
                        <th>Owner</th>
                        <th>Next Activity</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td class="text-muted small">{{ $lead->id }}</td>
                        <td>
                            <a href="{{ route('crm.leads.show', $lead) }}"
                               class="text-decoration-none fw-semibold text-dark">
                                {{ $lead->title }}
                            </a>
                            @if($lead->notes)
                                <i class="bi bi-chat-left-text text-muted ms-1" title="{{ Str::limit($lead->notes, 60) }}"></i>
                            @endif
                        </td>
                        <td>
                            @if($lead->label)
                                <span class="badge bg-{{ $lead->label_badge_class }}">{{ $lead->label }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $lead->source_origin ?? '—' }}</td>
                        <td class="small">{{ $lead->owner ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $lead->next_activity_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="small text-muted">
                            {{ $lead->lead_created_at?->format('d M Y') ?? $lead->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Quick label update --}}
                                <div class="dropdown">
                                    <button class="btn btn-xs btn-outline-secondary dropdown-toggle btn-sm"
                                            data-bs-toggle="dropdown" title="Change label">
                                        <i class="bi bi-tag"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach(\App\Models\Lead::LABELS as $lbl)
                                            <li>
                                                <form method="POST"
                                                      action="{{ route('crm.leads.label', $lead) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="label" value="{{ $lbl }}">
                                                    <button class="dropdown-item small" type="submit">
                                                        {{ $lbl }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <a href="{{ route('crm.leads.edit', $lead) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('crm.leads.destroy', $lead) }}"
                                      onsubmit="return confirm('Delete this lead?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No leads found. <a href="{{ route('crm.leads.create') }}">Create one</a>
                            or <a href="{{ route('crm.leads.import.form') }}">import from Pipedrive</a>.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
    <div class="text-muted small order-2 order-sm-1">
        Showing 
        <span class="fw-bold text-dark">{{ $leads->firstItem() }}</span> 
        to 
        <span class="fw-bold text-dark">{{ $leads->lastItem() }}</span> 
        of 
        <span class="fw-bold text-dark">{{ $leads->total() }}</span> 
        leads
    </div>

    <div class="pagination-sm order-1 order-sm-2">
        {{ $leads->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
