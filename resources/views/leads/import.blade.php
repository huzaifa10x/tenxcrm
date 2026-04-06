@extends('layouts.crm')

@section('title', 'Import Leads')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.leads.index') }}">Leads</a></li>
    <li class="breadcrumb-item active">Import</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Import Leads from Pipedrive</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('crm.leads.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">CSV / Excel File <span class="text-danger">*</span></label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls"
                               class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted: .csv, .xlsx, .xls — Max 20 MB</div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Start Import
                    </button>
                    <a href="{{ route('crm.leads.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>

        {{-- Column mapping reference --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-table me-2"></i>Expected Pipedrive Column Mapping</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pipedrive CSV Column</th>
                            <th>Maps To</th>
                            <th>Required?</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <tr><td><code>Lead - Title</code></td><td>title</td><td><span class="badge bg-danger">Yes</span></td></tr>
                        <tr><td><code>Lead - Labels</code></td><td>label</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td><code>Lead - Source origin</code></td><td>source_origin</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td><code>Lead - Owner</code></td><td>owner</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td><code>Lead - Lead created</code></td><td>lead_created_at</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td><code>Lead - Next activity</code></td><td>next_activity_at</td><td><span class="badge bg-secondary">No</span></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top small text-muted py-2 px-3">
                <i class="bi bi-info-circle me-1"></i>
                Existing leads (matched by title) will be updated. New rows will be inserted.
                Rows with blank titles are skipped.
            </div>
        </div>

    </div>
</div>
@endsection
