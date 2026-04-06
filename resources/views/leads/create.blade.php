@extends('layouts.crm')

@section('title', isset($lead) ? 'Edit Lead' : 'New Lead')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    @isset($lead)
        <li class="breadcrumb-item"><a href="{{ route('crm.leads.show', $lead) }}">{{ Str::limit($lead->title, 30) }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    @else
        <li class="breadcrumb-item active">New Lead</li>
    @endisset
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">
                    {{ isset($lead) ? 'Edit Lead' : 'Create New Lead' }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ isset($lead) ? route('crm.leads.update', $lead) : route('crm.leads.store') }}">
                    @csrf
                    @isset($lead) @method('PUT') @endisset

                    <div class="row g-3">
                        {{-- Title --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $lead->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Label --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Label</label>
                            <select name="label" class="form-select @error('label') is-invalid @enderror">
                                <option value="">— Select Label —</option>
                                @foreach(\App\Models\Lead::LABELS as $lbl)
                                    <option value="{{ $lbl }}"
                                        @selected(old('label', $lead->label ?? '') === $lbl)>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                            @error('label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Source Origin --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Source Origin</label>
                            <input type="text" name="source_origin"
                                   class="form-control @error('source_origin') is-invalid @enderror"
                                   value="{{ old('source_origin', $lead->source_origin ?? '') }}"
                                   placeholder="e.g. Manually created, Website">
                            @error('source_origin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Owner --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Owner</label>
                            <input type="text" name="owner"
                                   class="form-control @error('owner') is-invalid @enderror"
                                   value="{{ old('owner', $lead->owner ?? '') }}">
                            @error('owner')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lead Created At --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lead Created Date</label>
                            <input type="datetime-local" name="lead_created_at"
                                   class="form-control @error('lead_created_at') is-invalid @enderror"
                                   value="{{ old('lead_created_at', isset($lead->lead_created_at) ? $lead->lead_created_at->format('Y-m-d\TH:i') : '') }}">
                            @error('lead_created_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Next Activity --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Next Activity</label>
                            <input type="datetime-local" name="next_activity_at"
                                   class="form-control @error('next_activity_at') is-invalid @enderror"
                                   value="{{ old('next_activity_at', isset($lead->next_activity_at) ? $lead->next_activity_at->format('Y-m-d\TH:i') : '') }}">
                            @error('next_activity_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" rows="4"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Internal notes about this lead…">{{ old('notes', $lead->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ isset($lead) ? route('crm.leads.show', $lead) : route('crm.leads.index') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ isset($lead) ? 'Save Changes' : 'Create Lead' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
