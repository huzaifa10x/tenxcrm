@extends('layouts.crm')

@section('title', 'Import Deals')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm.deals.index') }}">Deals</a></li>
    <li class="breadcrumb-item active">Import</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Import Deals from Pipedrive</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('crm.deals.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            CSV / Excel File <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls"
                               class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted: .csv, .xlsx, .xls — Max 50 MB</div>
                    </div>

                    <div class="alert alert-info d-flex gap-2 small">
                        <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                        <div>
                            Records are matched by <strong>Pipedrive ID</strong> (Deal - ID column).
                            If no ID is present, matching falls back to <strong>title</strong>.
                            Existing records will be updated; new rows will be inserted.
                            Rows without a title are skipped.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Start Import
                    </button>
                    <a href="{{ route('crm.deals.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>

        {{-- Column mapping reference --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-table me-2"></i>Pipedrive Column Mapping Reference
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Pipedrive CSV Column</th>
                                <th>Database Field</th>
                                <th>Type</th>
                                <th>Required?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-warning">
                                <td><code>Deal - Title</code></td>
                                <td>title</td>
                                <td>string</td>
                                <td><span class="badge bg-danger">Yes</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - ID</code></td>
                                <td>pipedrive_id</td>
                                <td>integer</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Value</code></td>
                                <td>value</td>
                                <td>decimal</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Currency of Value</code></td>
                                <td>currency</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Organization</code></td>
                                <td>organization</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Contact person</code></td>
                                <td>contact_person</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Owner</code></td>
                                <td>owner</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Pipeline</code></td>
                                <td>pipeline</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Stage</code></td>
                                <td>stage</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Status</code></td>
                                <td>status</td>
                                <td>Open / Won / Lost</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Label</code></td>
                                <td>label</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Probability</code></td>
                                <td>probability</td>
                                <td>decimal</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Weighted value</code></td>
                                <td>weighted_value</td>
                                <td>decimal</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Source origin</code></td>
                                <td>source_origin</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Source channel</code></td>
                                <td>source_channel</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Lost reason</code></td>
                                <td>lost_reason</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Total activities</code></td>
                                <td>total_activities</td>
                                <td>integer</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Done activities</code></td>
                                <td>done_activities</td>
                                <td>integer</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Activities to do</code></td>
                                <td>activities_to_do</td>
                                <td>integer</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Product name</code></td>
                                <td>product_name</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Product amount</code></td>
                                <td>product_amount</td>
                                <td>decimal</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Product quantity</code></td>
                                <td>product_quantity</td>
                                <td>integer</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Creator</code></td>
                                <td>creator</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Archive status</code></td>
                                <td>archive_status</td>
                                <td>string</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Expected close date</code></td>
                                <td>expected_close_date</td>
                                <td>date</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Next activity date</code></td>
                                <td>next_activity_date</td>
                                <td>date</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Last activity date</code></td>
                                <td>last_activity_date</td>
                                <td>date</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Deal created</code></td>
                                <td>deal_created_at</td>
                                <td>datetime</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Update time</code></td>
                                <td>deal_updated_at</td>
                                <td>datetime</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Won time</code></td>
                                <td>won_time</td>
                                <td>datetime</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                            <tr>
                                <td><code>Deal - Lost time</code></td>
                                <td>lost_time</td>
                                <td>datetime</td>
                                <td><span class="badge bg-secondary">No</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
