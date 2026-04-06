@extends('layouts.crm')

@section('title', isset($deal) ? 'Edit Deal' : 'New Deal')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.deals.index') }}">Deals</a></li>
    @isset($deal)
        <li class="breadcrumb-item"><a href="{{ route('crm.deals.show', $deal) }}">{{ Str::limit($deal->title, 30) }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
    @else
        <li class="breadcrumb-item active">New Deal</li>
    @endisset
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <form method="POST"
              action="{{ isset($deal) ? route('crm.deals.update', $deal) : route('crm.deals.store') }}">
            @csrf
            @isset($deal) @method('PUT') @endisset

            <div class="row g-4">

                {{-- ── Core Details ── --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Core Details</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $deal->title ?? '') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        @foreach(\App\Models\Deal::STATUSES as $s)
                                            <option value="{{ $s }}"
                                                @selected(old('status', $deal->status ?? 'Open') === $s)>
                                                {{ $s }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Value</label>
                                    <input type="number" name="value" step="0.01" min="0"
                                           class="form-control @error('value') is-invalid @enderror"
                                           value="{{ old('value', $deal->value ?? '') }}">
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Currency</label>
                                    <input type="text" name="currency" maxlength="10"
                                           class="form-control"
                                           value="{{ old('currency', $deal->currency ?? 'AED') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Probability (%)</label>
                                    <input type="number" name="probability" min="0" max="100" step="1"
                                           class="form-control @error('probability') is-invalid @enderror"
                                           value="{{ old('probability', $deal->probability ?? '') }}">
                                    @error('probability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Stage</label>
                                    <select name="stage" class="form-select">
                                        <option value="">— Select Stage —</option>
                                        @foreach(\App\Models\Deal::STAGES as $st)
                                            <option value="{{ $st }}"
                                                @selected(old('stage', $deal->stage ?? '') === $st)>
                                                {{ $st }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Contact Info ── --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Contact & Organization</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Organization</label>
                                    <input type="text" name="organization" class="form-control"
                                           value="{{ old('organization', $deal->organization ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control"
                                           value="{{ old('contact_person', $deal->contact_person ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Owner</label>
                                    <input type="text" name="owner" class="form-control"
                                           value="{{ old('owner', $deal->owner ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Pipeline</label>
                                    <input type="text" name="pipeline" class="form-control"
                                           value="{{ old('pipeline', $deal->pipeline ?? '') }}"
                                           placeholder="e.g. Pipeline">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Label</label>
                                    <input type="text" name="label" class="form-control"
                                           value="{{ old('label', $deal->label ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Source & Dates ── --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Source & Dates</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Source Origin</label>
                                    <input type="text" name="source_origin" class="form-control"
                                           value="{{ old('source_origin', $deal->source_origin ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Source Channel</label>
                                    <input type="text" name="source_channel" class="form-control"
                                           value="{{ old('source_channel', $deal->source_channel ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Expected Close Date</label>
                                    <input type="date" name="expected_close_date" class="form-control"
                                           value="{{ old('expected_close_date', isset($deal->expected_close_date) ? $deal->expected_close_date->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Next Activity Date</label>
                                    <input type="date" name="next_activity_date" class="form-control"
                                           value="{{ old('next_activity_date', isset($deal->next_activity_date) ? $deal->next_activity_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Product ── --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Product (optional)</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Product Name</label>
                                    <input type="text" name="product_name" class="form-control"
                                           value="{{ old('product_name', $deal->product_name ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="number" name="product_quantity" min="0" class="form-control"
                                           value="{{ old('product_quantity', $deal->product_quantity ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <input type="number" name="product_amount" step="0.01" min="0"
                                           class="form-control"
                                           value="{{ old('product_amount', $deal->product_amount ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Lost Reason & Notes ── --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Additional Info</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lost Reason</label>
                                    <input type="text" name="lost_reason" class="form-control"
                                           value="{{ old('lost_reason', $deal->lost_reason ?? '') }}"
                                           placeholder="Required when status is Lost">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Notes</label>
                                    <textarea name="notes" rows="4" class="form-control"
                                              placeholder="Internal notes about this deal…">{{ old('notes', $deal->notes ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="col-12 d-flex gap-2 justify-content-end pb-4">
                    <a href="{{ isset($deal) ? route('crm.deals.show', $deal) : route('crm.deals.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>
                        {{ isset($deal) ? 'Save Changes' : 'Create Deal' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
