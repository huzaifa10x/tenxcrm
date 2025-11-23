<x-app-layout>
    <x-slot name="header">
        {{ __('Website Project') }} - {{ $websiteProject->project->title }}
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Project Overview -->
    <div class="mb-3 row gy-3">
        <div class="col-lg-3">
            <div class="border card border-primary">
                <div class="card-body">
                    <h6 class="mb-2 text-primary">Platform</h6>
                    <h4>{{ $websiteProject->platform }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="border card border-warning">
                <div class="card-body">
                    <h6 class="mb-2 text-warning">Design Status</h6>
                    <h5>{{ $websiteProject->design_status }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="border card border-info">
                <div class="card-body">
                    <h6 class="mb-2 text-info">Development Status</h6>
                    <h5>{{ $websiteProject->development_status }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border {{ $websiteProject->approved_for_go_live ? 'border-success' : 'border-secondary' }}">
                <div class="card-body">
                    <h6 class="{{ $websiteProject->approved_for_go_live ? 'text-success' : 'text-secondary' }} mb-2">Go-Live Status</h6>
                    <h5>
                        @if($websiteProject->go_live_date)
                            <span class="badge bg-success">Live</span>
                        @elseif($websiteProject->approved_for_go_live)
                            <span class="badge bg-warning">Ready</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Target Date & Days Remaining -->
    @if($websiteProject->target_go_live_date)
    <div class="alert {{ $websiteProject->isOverdue() ? 'alert-danger' : 'alert-info' }}">
        <strong>Target Go-Live Date:</strong> {{ $websiteProject->target_go_live_date->format('M d, Y') }}
        @php $daysUntil = $websiteProject->getDaysUntilGoLive(); @endphp
        @if($daysUntil !== null && !$websiteProject->go_live_date)
            @if($daysUntil > 0)
                | <strong>{{ $daysUntil }} days remaining</strong>
            @elseif($daysUntil == 0)
                | <strong class="text-warning">Due Today!</strong>
            @else
                | <strong class="text-danger">Overdue by {{ abs($daysUntil) }} days</strong>
            @endif
        @endif
    </div>
    @endif

    <!-- Project Details -->
    <div class="mb-3 row gy-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Project Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('website-projects.update', $websiteProject->id) }}" method="POST" id="updateForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <input type="text" class="form-control" value="{{ $websiteProject->project->title }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Developer Assigned</label>
                            <select name="developer_assigned" class="form-control" onchange="this.form.submit()">
                                <option value="">Not Assigned</option>
                                @foreach($developers as $dev)
                                    <option value="{{ $dev->id }}" {{ $websiteProject->developer_assigned == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lead Developer</label>
                            <input type="text" class="form-control" value="{{ $websiteProject->leadDeveloper->name ?? 'Not Assigned' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Has Current SEO</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="has_current_seo" value="1"
                                    {{ $websiteProject->has_current_seo ? 'checked' : '' }} onchange="this.form.submit()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Has Current Google Ads</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="has_current_google_ads" value="1"
                                    {{ $websiteProject->has_current_google_ads ? 'checked' : '' }} onchange="this.form.submit()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Go-Live Date</label>
                            <input type="date" name="target_go_live_date" class="form-control"
                                value="{{ $websiteProject->target_go_live_date?->format('Y-m-d') }}" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Links & Files</h5>
                </div>
                <div class="card-body">
                    @if($websiteProject->figma_link)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Figma Design Link</label>
                            <a href="{{ $websiteProject->figma_link }}" target="_blank" class="d-block">
                                {{ $websiteProject->figma_link }}
                            </a>
                        </div>
                    @endif

                    @if($websiteProject->staging_link)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Staging Link</label>
                            <a href="{{ $websiteProject->staging_link }}" target="_blank" class="d-block">
                                {{ $websiteProject->staging_link }}
                            </a>
                        </div>
                    @endif

                    @if($websiteProject->design_approval_screenshot)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Design Approval</label>
                            <a href="{{ asset('storage/' . $websiteProject->design_approval_screenshot) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View Screenshot
                            </a>
                        </div>
                    @endif

                    @if($websiteProject->completion_agreement)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Completion Agreement</label>
                            <a href="{{ asset('storage/' . $websiteProject->completion_agreement) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                View Document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Design Phase -->
    <div class="mb-3 card">
        <div class="card-header bg-warning-subtle">
            <h5 class="mb-0">Design Phase</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('website-projects.update-design-status', $websiteProject->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row gy-3">
                    <div class="col-lg-4">
                        <label class="form-label">Design Status</label>
                        <select name="design_status" class="form-control" required>
                            <option value="Not Started" {{ $websiteProject->design_status == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                            <option value="In Progress" {{ $websiteProject->design_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ $websiteProject->design_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Approved" {{ $websiteProject->design_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Figma Link</label>
                        <input type="url" name="figma_link" class="form-control" placeholder="https://figma.com/..." value="{{ $websiteProject->figma_link }}">
                        <small class="text-muted">Required when marking as Completed</small>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Client Approval Screenshot</label>
                        <input type="file" name="design_approval_screenshot" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted">Required when marking as Approved</small>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-warning">Update Design Status</button>
                    </div>
                </div>

                @if($websiteProject->design_completed_at)
                    <div class="mt-3">
                        <small class="text-muted">Design completed on: {{ $websiteProject->design_completed_at->format('M d, Y H:i') }}</small>
                    </div>
                @endif

                @if($websiteProject->design_approved_at)
                    <div class="mt-2">
                        <small class="text-success">Design approved on: {{ $websiteProject->design_approved_at->format('M d, Y H:i') }}</small>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Development Phase -->
    <div class="mb-3 card">
        <div class="card-header bg-info-subtle">
            <h5 class="mb-0">Development Phase</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('website-projects.update-development-status', $websiteProject->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row gy-3">
                    <div class="col-lg-4">
                        <label class="form-label">Development Status</label>
                        <select name="development_status" class="form-control" required>
                            <option value="Not Started" {{ $websiteProject->development_status == 'Not Started' ? 'selected' : '' }}>Not Started</option>
                            <option value="In Progress" {{ $websiteProject->development_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Internal QA" {{ $websiteProject->development_status == 'Internal QA' ? 'selected' : '' }}>Internal QA (Lead Dev Review)</option>
                            <option value="Client Review" {{ $websiteProject->development_status == 'Client Review' ? 'selected' : '' }}>Client Review</option>
                            <option value="Completed" {{ $websiteProject->development_status == 'Completed' ? 'selected' : '' }}>Completed (Ready for Go-Live)</option>
                            <option value="Delivered" {{ $websiteProject->development_status == 'Delivered' ? 'selected' : '' }}>Delivered / Live</option>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Staging Link</label>
                        <input type="url" name="staging_link" class="form-control" placeholder="https://staging.example.com" value="{{ $websiteProject->staging_link }}">
                        <small class="text-muted">Required when marking as In Progress</small>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Completion Agreement</label>
                        <input type="file" name="completion_agreement" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted">Sign-off email/WhatsApp when Completed</small>
                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-info">Update Development Status</button>
                    </div>
                </div>

                @if($websiteProject->development_completed_at)
                    <div class="mt-3">
                        <small class="text-success">Development completed on: {{ $websiteProject->development_completed_at->format('M d, Y H:i') }}</small>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Pre-Go-Live Checklist -->
    @if($websiteProject->development_status === 'Completed')
    <div class="mb-3 card">
        <div class="card-header bg-success-subtle">
            <h5 class="mb-0">Pre-Go-Live Checklist</h5>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                <!-- Lead Dev QA -->
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header {{ $websiteProject->lead_dev_qa_approved ? 'bg-success text-white' : '' }}">
                            <h6 class="mb-0">Lead Developer Final QA
                                @if($websiteProject->lead_dev_qa_approved)
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $leadDevChecklist = $websiteProject->checklists()->where('type', 'lead_dev_qa')->first();
                            @endphp

                            @if(!$websiteProject->lead_dev_qa_approved)
                                <form action="{{ route('website-projects.submit-checklist', $websiteProject->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="lead_dev_qa">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="code_quality_reviewed" value="1" {{ $leadDevChecklist?->code_quality_reviewed ? 'checked' : '' }}>
                                        <label class="form-check-label">Code Quality Reviewed</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="security_checked" value="1" {{ $leadDevChecklist?->security_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">Security Checked</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="performance_optimized" value="1" {{ $leadDevChecklist?->performance_optimized ? 'checked' : '' }}>
                                        <label class="form-check-label">Performance Optimized</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="cross_browser_tested" value="1" {{ $leadDevChecklist?->cross_browser_tested ? 'checked' : '' }}>
                                        <label class="form-check-label">Cross-browser Tested</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="QA Notes">{{ $leadDevChecklist?->notes }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            @else
                                <p class="mb-0 text-success">Approved by {{ $websiteProject->leadDevQAApprovedBy->name }} on {{ $websiteProject->lead_dev_qa_approved_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- AM Validation -->
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header {{ $websiteProject->am_validation_approved ? 'bg-success text-white' : '' }}">
                            <h6 class="mb-0">AM Validation
                                @if($websiteProject->am_validation_approved)
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $amChecklist = $websiteProject->checklists()->where('type', 'am_validation')->first();
                            @endphp

                            @if(!$websiteProject->am_validation_approved)
                                <form action="{{ route('website-projects.submit-checklist', $websiteProject->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="am_validation">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="forms_working" value="1" {{ $amChecklist?->forms_working ? 'checked' : '' }}>
                                        <label class="form-check-label">Forms Working</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="content_validated" value="1" {{ $amChecklist?->content_validated ? 'checked' : '' }}>
                                        <label class="form-check-label">Content Validated</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="responsiveness_checked" value="1" {{ $amChecklist?->responsiveness_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">Responsiveness Checked</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="Validation Notes">{{ $amChecklist?->notes }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            @else
                                <p class="mb-0 text-success">Approved by {{ $websiteProject->amValidationApprovedBy->name }} on {{ $websiteProject->am_validation_approved_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SEO Tech Approval (if has SEO) -->
                @if($websiteProject->has_current_seo)
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header {{ $websiteProject->seo_tech_approved ? 'bg-success text-white' : '' }}">
                            <h6 class="mb-0">SEO Tech Approval
                                @if($websiteProject->seo_tech_approved)
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $seoChecklist = $websiteProject->checklists()->where('type', 'seo_tech')->first();
                            @endphp

                            @if(!$websiteProject->seo_tech_approved)
                                <form action="{{ route('website-projects.submit-checklist', $websiteProject->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="seo_tech">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="urls_checked" value="1" {{ $seoChecklist?->urls_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">URLs Checked</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="meta_setup_checked" value="1" {{ $seoChecklist?->meta_setup_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">Meta Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="tracking_setup" value="1" {{ $seoChecklist?->tracking_setup ? 'checked' : '' }}>
                                        <label class="form-check-label">Tracking Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="indexing_checked" value="1" {{ $seoChecklist?->indexing_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">Indexing Checked</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="page_speed_checked" value="1" {{ $seoChecklist?->page_speed_checked ? 'checked' : '' }}>
                                        <label class="form-check-label">Page Speed Checked</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="SEO Notes">{{ $seoChecklist?->notes }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            @else
                                <p class="mb-0 text-success">Approved by {{ $websiteProject->seoTechApprovedBy->name }} on {{ $websiteProject->seo_tech_approved_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Ads AM Approval (if has Ads) -->
                @if($websiteProject->has_current_google_ads)
                <div class="col-lg-6">
                    <div class="border card">
                        <div class="card-header {{ $websiteProject->ads_am_approved ? 'bg-success text-white' : '' }}">
                            <h6 class="mb-0">Ads AM Approval
                                @if($websiteProject->ads_am_approved)
                                    <span class="bg-white badge text-success float-end">✓ Approved</span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $adsChecklist = $websiteProject->checklists()->where('type', 'ads_am')->first();
                            @endphp

                            @if(!$websiteProject->ads_am_approved)
                                <form action="{{ route('website-projects.submit-checklist', $websiteProject->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="ads_am">

                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="conversions_setup" value="1" {{ $adsChecklist?->conversions_setup ? 'checked' : '' }}>
                                        <label class="form-check-label">Conversions Setup</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="tracking_validated" value="1" {{ $adsChecklist?->tracking_validated ? 'checked' : '' }}>
                                        <label class="form-check-label">Tracking Validated</label>
                                    </div>
                                    <div class="mb-2 form-check">
                                        <input class="form-check-input" type="checkbox" name="forms_tracking" value="1" {{ $adsChecklist?->forms_tracking ? 'checked' : '' }}>
                                        <label class="form-check-label">Forms Tracking</label>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input class="form-check-input" type="checkbox" name="pixel_ga4_setup" value="1" {{ $adsChecklist?->pixel_ga4_setup ? 'checked' : '' }}>
                                        <label class="form-check-label">Pixel/GA4 Setup</label>
                                    </div>

                                    <textarea name="notes" class="mb-2 form-control" rows="2" placeholder="Ads Notes">{{ $adsChecklist?->notes }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Save & Approve</button>
                                </form>
                            @else
                                <p class="mb-0 text-success">Approved by {{ $websiteProject->adsAmApprovedBy->name }} on {{ $websiteProject->ads_am_approved_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Go Live Button -->
    @if($websiteProject->approved_for_go_live && !$websiteProject->go_live_date)
    <div class="mb-3 card border-success">
        <div class="text-center card-body">
            <h4 class="mb-3 text-success">🎉 Website Ready for Go-Live!</h4>
            <p>All approvals completed. Click below to mark as live.</p>
            <form action="{{ route('website-projects.mark-as-live', $websiteProject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this project as LIVE?')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-rocket-takeoff"></i> Mark as LIVE
                </button>
            </form>
        </div>
    </div>
    @endif

    @if($websiteProject->go_live_date)
    <div class="alert alert-success">
        <h5>🎉 Website is LIVE!</h5>
        <p class="mb-0">Went live on: {{ $websiteProject->go_live_date->format('M d, Y H:i') }}</p>
    </div>
    @endif

    <!-- Activity History -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Activity History</h5>
        </div>
        <div class="card-body">
            @foreach($websiteProject->history as $history)
                <div class="gap-3 mb-3 d-flex">
                    <div class="flex-shrink-0">
                        <iconify-icon icon="mdi:history" width="20" height="20"></iconify-icon>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0">{{ $history->action }}</p>
                        <small class="text-muted">
                            {{ $history->user->name ?? 'System' }} • {{ $history->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
