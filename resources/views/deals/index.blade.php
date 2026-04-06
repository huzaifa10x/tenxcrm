@extends('layouts.crm')

@section('title', 'Deals')

@section('breadcrumb')
    <li class="breadcrumb-item active">Deals</li>
@endsection

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.deals.export', request()->query()) }}"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export
        </a>
        <a href="{{ route('crm.deals.import.form') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <a href="{{ route('crm.deals.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Deal
        </a>
    </div>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-1">Total Deals</div>
                <div class="fs-4 fw-bold">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-primary">
            <div class="card-body">
                <div class="text-muted small mb-1">Open</div>
                <div class="fs-4 fw-bold text-primary">{{ number_format($stats['open']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-success">
            <div class="card-body">
                <div class="text-muted small mb-1">Won</div>
                <div class="fs-4 fw-bold text-success">{{ number_format($stats['won']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-info">
            <div class="card-body">
                <div class="text-muted small mb-1">Open Pipeline Value</div>
                <div class="fs-5 fw-bold text-info">
                    AED {{ number_format($stats['total_val'], 0) }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('crm.deals.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search title, org, contact…"
                       value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\Deal::STATUSES as $s)
                        <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    @foreach(\App\Models\Deal::STAGES as $st)
                        <option value="{{ $st }}" @selected(($filters['stage'] ?? '') === $st)>{{ $st }}</option>
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
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('crm.deals.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>

        {{-- Advanced row --}}
        <div class="row g-2 mt-1 align-items-end">
            <div class="col-md-2">
                <input type="number" name="value_min" class="form-control form-control-sm"
                       placeholder="Value min" value="{{ $filters['value_min'] ?? '' }}" min="0">
            </div>
            <div class="col-md-2">
                <input type="number" name="value_max" class="form-control form-control-sm"
                       placeholder="Value max" value="{{ $filters['value_max'] ?? '' }}" min="0">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ $filters['date_from'] ?? '' }}" title="Deal created from">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ $filters['date_to'] ?? '' }}" title="Deal created to">
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
                        <th>Organization</th>
                        <th>Value</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Expected Close</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($deals as $deal)
                    <tr>
                        <td class="text-muted small">{{ $deal->pipedrive_id ?? $deal->id }}</td>
                        <td>
                            <a href="{{ route('crm.deals.show', $deal) }}"
                               class="text-decoration-none fw-semibold text-dark">
                                {{ $deal->title }}
                            </a>
                            @if($deal->notes)
                                <i class="bi bi-chat-left-text text-muted ms-1"
                                   title="{{ Str::limit($deal->notes, 60) }}"></i>
                            @endif
                        </td>
                        <td class="small">{{ $deal->organization ?? '—' }}</td>
                        <td class="small fw-semibold">
                            {{ $deal->value ? number_format($deal->value, 0) . ' ' . $deal->currency : '—' }}
                        </td>
                        <td>
                            @if($deal->stage)
                                <span class="badge bg-{{ $deal->stage_badge_class }}">{{ $deal->stage }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $deal->status_badge_class }}">{{ $deal->status }}</span>
                        </td>
                        <td class="small">{{ $deal->owner ?? '—' }}</td>
                        <td class="small text-muted">
                            {{ $deal->expected_close_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="small text-muted">
                            {{ $deal->deal_created_at?->format('d M Y') ?? $deal->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Quick status change --}}
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Change status">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px">
                                        @foreach(\App\Models\Deal::STATUSES as $st)
                                            <li>
                                                <form method="POST"
                                                      action="{{ route('crm.deals.status', $deal) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $st }}">
                                                    <button class="dropdown-item small d-flex align-items-center gap-2"
                                                            type="submit">
                                                        <span class="badge bg-{{
                                                            $st === 'Won' ? 'success' : ($st === 'Lost' ? 'danger' : 'primary')
                                                        }}">{{ $st }}</span>
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <a href="{{ route('crm.deals.edit', $deal) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('crm.deals.destroy', $deal) }}"
                                      onsubmit="return confirm('Delete this deal?')">
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
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No deals found. <a href="{{ route('crm.deals.create') }}">Create one</a>
                            or <a href="{{ route('crm.deals.import.form') }}">import from Pipedrive</a>.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 pt-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    <div class="text-secondary small order-2 order-md-1">
        Showing 
        <span class="fw-semibold text-dark">{{ $deals->firstItem() }}</span> 
        to 
        <span class="fw-semibold text-dark">{{ $deals->lastItem() }}</span> 
        of 
        <span class="fw-semibold text-dark">{{ $deals->total() }}</span> 
        deals
    </div>

    <div class="order-1 order-md-2">
        {{ $deals->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
