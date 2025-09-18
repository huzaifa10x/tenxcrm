<x-app-layout>
    <x-slot name="header">
        {{ __('Project - ' . $project->title) }}
    </x-slot>
    <div class="border card">
        <div class="flex-wrap gap-10 card-header d-flex justify-content-between align-items-center">
            <div class="gap-3 d-flex align-items-center">
                <button data-bs-toggle="modal" data-bs-target="#taskCreateModal"
                    class="btn-sm btn btn-outline-primary fs-7">
                    Create Task
                </button>
                @if ($project->status !== 'Cancelled')
                    <button data-project-id="{{ $project->id }}" class="cancel-project btn-sm btn btn-danger fs-7">
                        Cancel Project
                    </button>
                @endif
                @php
                    $today = \Carbon\Carbon::today();
                    $isRenewDisabled = $today->lt($contractEndDate);
                @endphp

                <button class="btn-sm btn btn-info fs-7" data-bs-toggle="modal" data-bs-target="#renewProjectModal"
                    {{ $isRenewDisabled ? 'disabled' : '' }}>
                    Renew Contract
                </button>

                <!-- Renew Project Modal -->
                <div class="modal fade" id="renewProjectModal" tabindex="-1" aria-labelledby="renewProjectLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="renewProjectForm" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span class="modal-title" id="renewProjectLabel">Renew {{ $project->title }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Contract Value (AED)</label>
                                        <input type="number" name="contract_value" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contract Term (months)</label>
                                        <input type="number" name="contract_term" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Free Months</label>
                                        <input type="number" name="free_months" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contract Start Date</label>
                                        <input type="date" name="contract_start_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Payment Method</label>
                                        <select name="payment_method_id" class="form-select" required>
                                            @foreach ($paymentMethods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Contract Copy (multiple)</label>
                                        <input type="file" name="contract_copy[]" class="form-control" multiple>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Handover Notes</label>
                                        <textarea name="handover_notes" class="form-control"></textarea>
                                    </div>

                                    @php
                                        $roles = [
                                            'sales_manager_ids' => 'Sales Manager',
                                            'account_manager_ids' => 'Account Manager',
                                            'tech_manager_ids' => 'Tech Manager',
                                            'seo_admin_ids' => 'SEO Admin',
                                            'content_writers_ids' => 'Content Writer',
                                        ];
                                    @endphp

                                    @foreach ($roles as $field => $label)
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $label }}</label>
                                            <select name="{{ $field }}[]" class="form-select">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Renew</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($userPermissions->contains('can update project status'))
                    <div class="gap-10 d-flex align-items-center" style="white-space: nowrap;">
                        <label class="m-0 form-label fs-7">Project Status</label>
                        <select class="h-auto form-control fs-7 project-status" data-project-id="{{ $project->id }}"
                            required>
                            <option selected disabled value="">Select status</option>
                            <option value="Current" {{ $project->status == 'Current' ? 'selected' : '' }}>
                                Current
                            </option>
                            <option value="Paused" {{ $project->status == 'Paused' ? 'selected' : '' }}>
                                Paused
                            </option>
                            <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>
                                WFF
                            </option>
                            <option value="Expired" {{ $project->status == 'Expired' ? 'selected' : '' }}>
                                Expired
                            </option>
                            <option value="Debt Collection"
                                {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt
                                Collection
                            </option>
                            <option disabled value="Cancelled" {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                            <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>
                    </div>
                @else
                    <div class="gap-10 d-flex align-items-center" style="white-space: nowrap;">
                        <label class="m-0 form-label fs-7">Project Status</label>
                        <input type="text" readonly class="h-auto form-control fs-7" value="{{ $project->status }}">
                    </div>
                @endif
                @if (auth()->user()->hasRole('ADMIN'))
                    <a href="{{ route('projects.edit', $project->id) }}"
                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                    </a>
                @endif
                <button title="Create Recurring Task" data-bs-toggle="modal" data-recurring="1"
                    data-bs-target="#taskCreateModal"
                    class="bg-danger-focus text-danger-600 taskCreateModal bg-hover-danger-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                        <path
                            d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                        <path fill-rule="evenodd"
                            d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                    </svg>
                </button>
            </div>
            @php
                $taskTypes = ['CONTENT', 'ONSITE', 'OFFSITE', 'DESIGN', 'DEVELOPMENT', 'AM', 'ADHOC'];
            @endphp
            <div class="flex-wrap gap-3 d-flex align-items-center">
                @foreach ($taskTypes as $type)
                    @php
                        $count = $taskCounts[$type] ?? 0;
                        $typeClass = match ($type) {
                            'CONTENT' => 'info',
                            'ONSITE' => 'lilac',
                            'OFFSITE' => 'success',
                            'DESIGN' => 'neutral',
                            'DEVELOPMENT' => 'danger',
                            'AM' => 'primary',
                            'ADHOC' => 'info',
                            default => 'secondary',
                        };
                    @endphp

                    <div class="flex-wrap gap-3 d-flex align-items-center">
                        <button type="button"
                            class="btn text-{{ $typeClass }}-600 bg-{{ $typeClass }}-100 position-relative px-20 py-8 text-sm line-height-1 d-flex align-items-center">
                            {{ $type }}
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $typeClass }}-600 border border-white">
                                {{ $count }}
                            </span>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-body">
            @if ($project->status == 'Cancelled' && $project->cancellationReason->count())
                <div class="mb-4 row gy-3">
                    <div class="col-12">
                        <div class="shadow-sm card border-danger">
                            <div
                                class="text-white card-header bg-danger d-flex justify-content-between align-items-center">
                                <span class="fw-bold"><i class="bi bi-x-octagon me-1"></i> Cancellation Reasons</span>
                                <span class="badge bg-light text-danger">{{ $project->cancellationReason->count() }}
                                    entries</span>
                            </div>
                            <div class="card-body">
                                @foreach ($project->cancellationReason as $cancellation)
                                    <div class="p-3 mb-3 border rounded bg-light">
                                        <div class="mb-1 text-muted small">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $cancellation->created_at->format('d M Y, h:i A') }}
                                        </div>
                                        <div class="text-dark">
                                            {{ $cancellation->reason }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-10 row gy-3">
                <div class="col-lg-3">
                    <div
                        class="p-16 text-center trail-bg h-100 d-flex flex-column justify-content-center align-items-center radius-8">
                        <a target="_blank" href="{{ $project->companies->website ?? '' }}"> <img
                                src="{{ asset('/public/storage/') }}/{{ $project->companies->logo ?? '' }}"
                                alt="" class="mb-16 rounded img-fluid object-fit-cover rounded-end-4"
                                style="max-width: 150px;"></a>
                        <h6 class="text-xl text-white"><a target="_blank"
                                href="{{ $project->companies->website ?? '' }}">{{ $project->companies->name ?? '' }}</a>
                        </h6>
                        <div class="">
                            <p class="text-white">
                                <a
                                    href="mailto:{{ optional(optional($project->companies)->details->first())->email ?? '' }}">
                                    {{ optional(optional($project->companies)->details->first())->email ?? '' }}
                            </p>
                            </a>
                            @if ($userPermissions->contains('can view companies'))
                                <a href="{{ route('companies.show', $project->companies->id) }}"
                                    class="py-8 mb-16 text-sm btn rounded-pill w-100 bg-gradient-blue-warning">View
                                    Company</a>
                            @endif
                            {{-- Contact person info --}}
                            @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                    auth()->user()->hasRole('ADMIN') ||
                                    ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#contactPersonDetailModal"
                                    class="py-8 text-sm btn rounded-pill w-100 bg-gradient-danger">Contact
                                    Person</button>
                            @endif
                        </div>
                    </div>
                </div>
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        auth()->user()->hasRole('Accountant') ||
                        auth()->user()->hasRole('SEO ADMIN') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <div class="col-lg-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 mb-2 text-lg fw-bold">Sales Brief</h6>
                            </div>
                            <div class="card-body" style="height: 500px; overflow-y: auto;">
                                <div class="">
                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 text-md fw-medium">Project Type</h6>
                                                <span
                                                    class="px-20 mt-10 text-sm text-white badge fw-semibold text-success-600 bg-success-100 py-9 radius-4">{{ $project->project_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $salesManager = null;
                                        $accountManager = null;
                                        $seoAdmin = null;
                                        $techManager = null;

                                        if ($currentRenewal->assignments) {
                                            $salesManager = optional(
                                                $currentRenewal->assignments
                                                    ->where('role_type', 'SALES MANAGER')
                                                    ->first(),
                                            )->user->name;
                                            $accountManager = optional(
                                                $currentRenewal->assignments->where('role_type', 'AM')->first(),
                                            )->user->name;
                                            $seoAdmin = optional(
                                                $currentRenewal->assignments->where('role_type', 'SEO ADMIN')->first(),
                                            )->user->name;
                                            $techManager = optional(
                                                $currentRenewal->assignments
                                                    ->where('role_type', 'TECH MANAGER')
                                                    ->first(),
                                            )->user->name;
                                        }

                                        $salesManager =
                                            $salesManager ?? ($project->salesManagers->pluck('name')->first() ?? 'N/A');
                                        $accountManager =
                                            $accountManager ??
                                            ($project->accountManagers->pluck('name')->first() ?? 'N/A');
                                        $seoAdmin = $seoAdmin ?? ($project->seoAdmins->pluck('name')->first() ?? 'N/A');
                                        $techManager =
                                            $techManager ?? ($project->techManagers->pluck('name')->first() ?? 'N/A');
                                    @endphp

                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-md fw-medium">Sales Manager</h6>
                                            <span
                                                class="px-20 mt-10 text-sm text-white badge fw-semibold text-success-600 bg-success-100 py-9 radius-4">
                                                {{ $salesManager }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-md fw-medium">Account Manager</h6>
                                            <span
                                                class="px-20 mt-10 text-sm text-white badge fw-semibold text-lilac-600 bg-lilac-100 py-9 radius-4">
                                                {{ $accountManager }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-md fw-medium">SEO Admin</h6>
                                            <span
                                                class="px-20 mt-10 text-sm text-white badge fw-semibold text-neutral-600 bg-neutral-100 py-9 radius-4">
                                                {{ $seoAdmin }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-md fw-medium">Tech Manager</h6>
                                            <span
                                                class="px-20 mt-10 text-sm text-white badge fw-semibold text-neutral-600 bg-neutral-100 py-9 radius-4">
                                                {{ $techManager }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 text-md fw-medium">Industry</h6>
                                                <span
                                                    class="px-20 mt-10 text-sm text-white badge fw-semibold text-success-600 bg-success-100 py-9 radius-4">{{ $project->industry->industry ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($project->project_type == 'Web Development')
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Payment Type</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->webDevProject->payment_type ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Remaining Payment</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->webDevProject->remaining_payment ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Site Condition</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->webDevProject->site_condition ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($project->project_type == 'Social Media')
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Channels</h6>
                                                    @foreach ($project->socialMediaProject->channels as $channel)
                                                        <span
                                                            class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $channel }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Video shoots</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->socialMediaProject->video_shoots_per_month ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Static Posts Per Month</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->socialMediaProject->static_posts ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-3 mb-24 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-md fw-medium">Reels Per Month</h6>
                                                    <span
                                                        class="px-20 mt-10 text-sm text-white badge fw-semibold text-primary-600 bg-primary-100 py-9 radius-4">{{ $project->socialMediaProject->reels ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @php
                    $allAccountManagerIds = collect();
                    $allAccountManagerIds = $allAccountManagerIds->merge($project->accountManagers->pluck('id'));
                    foreach ($project->renewals as $renewal) {
                        $amIds = $renewal->assignments->where('role_type', 'AM')->pluck('user_id');
                        $allAccountManagerIds = $allAccountManagerIds->merge($amIds);
                    }
                    $canView =
                        $allAccountManagerIds->contains(auth()->id()) ||
                        auth()->user()->hasRole('Accountant') ||
                        auth()->user()->hasRole('ADMIN') ||
                        ($project->createdBy && auth()->id() == $project->createdBy->id);
                @endphp
                @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('Accountant') ||
                        auth()->user()->hasRole('SEO ADMIN') ||
                        auth()->user()->hasRole('ADMIN') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <div class="col-md-6">
                        <div class="card radius-16">
                            <div class="card-header">
                                <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 mb-2 text-lg fw-bold">Financial Details</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="p-12 d-flex align-items-center justify-content-between bg-neutral-100">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-orange rounded-pill"></span>
                                        <span class="text-secondary-light">Contract Start</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="text-primary-light fw-semibold">
                                            {{ $currentRenewal->contract_start_date }}</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-base">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Contract End</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $contractEndDate->toDateString() }}</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-neutral-100">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Total Contract Value <span
                                                class="fs-7">(w/o VAT - in AED)</span></span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ number_format($currentRenewal->contract_value, 2) }}</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-base">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Contract Term</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $currentRenewal->contract_term }}
                                            MO</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-neutral-100">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Payment Method</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $currentRenewal->paymentMethod->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-base">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-cyan-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Free Months</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $currentRenewal->free_months }}
                                            MO</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-neutral-100">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Monthly Invoice</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ number_format($monthlyInvoice, 2) }}</span>
                                    </div>
                                </div>
                                <div class="p-12 d-flex align-items-center justify-content-between bg-base">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Next Invoice Date</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $nextInvoiceDate->toDateString() }}</span>
                                    </div>
                                </div>
                                @php
                                    $startDate = \Carbon\Carbon::parse($currentRenewal->contract_start_date);
                                    $endDate = $startDate->copy()->addMonths($currentRenewal->contract_term);
                                    $now = \Carbon\Carbon::now();
                                    $remainingMonths = max(floor($now->diffInMonths($endDate, false)), 0);
                                @endphp
                                <div class="p-12 d-flex align-items-center justify-content-between bg-base">
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Remaining Months</span>
                                    </div>
                                    <div class="gap-2 d-flex align-items-center">
                                        <span class="text-primary-light fw-semibold">{{ $remainingMonths }}
                                            MO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        // auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        $userPermissions->contains('can view campaign access') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    @if ($project->credentials->count())
                        <div class="col-lg-12">
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 mb-2 text-lg fw-bold">Campaign Access</h6>
                                    <button class="p-0 m-0 border-0 btn addmorecred">Add More
                                        Credentials</button>
                                </div>
                                <div class="card-body row" style="max-height: 500px; overflow-y: auto;">
                                    @foreach ($project->credentials as $credential)
                                        <div class="my-10 col-lg-4">
                                            <div class="p-3 border rounded card position-relative">
                                                <div class="text-capitalize fs-7"><strong>Access Id:</strong>
                                                    {{ $credential->id }}</div>
                                                @if ($credential->platform_name)
                                                    <div class="text-capitalize"><strong>Platform:</strong>
                                                        {{ $credential->platform_name }}</div>
                                                @endif
                                                @if ($credential->url)
                                                    <div class="text-capitalize"><strong>URL:</strong> <a
                                                            href="{{ $credential->url }}" target="_blank">Visit</a>
                                                    </div>
                                                @endif
                                                @if ($credential->username)
                                                    <div class="text-capitalize"><strong>Username:</strong>
                                                        {{ $credential->username }}</div>
                                                @endif
                                                @if ($credential->password)
                                                    <div class="text-capitalize"><strong>Password:</strong>
                                                        <span class="password-toggle text-muted"
                                                            data-password="{{ $credential->password }}">••••••</span>
                                                        <a href="javascript:void(0);"
                                                            class="toggle-password small">Show</a>
                                                    </div>
                                                @endif
                                                @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                                        auth()->user()->hasRole('ADMIN') ||
                                                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                                                    <button type="button"
                                                        class="top-0 m-10 delete-credential position-absolute end-0 w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                        data-id="{{ $credential->id }}">
                                                        <iconify-icon icon="fluent:delete-24-regular"
                                                            class="menu-icon"></iconify-icon>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer">
                                    <form id="clientCredentialsForm" class="mt-10" style="display: none">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <div id="credentialsContainer">
                                            <div class="mb-10 credentialRow row">
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text"
                                                        name="credentials[0][platform_name]"
                                                        placeholder="Platform Name" required>
                                                </div>
                                                <div class="col-lg-3">
                                                    <input class="form-control" type="text"
                                                        name="credentials[0][url]" placeholder="Website URL">
                                                </div>
                                                <div class="col-lg-3">
                                                    <input required class="form-control" type="text"
                                                        name="credentials[0][username]" placeholder="Username/Email">
                                                </div>
                                                <div class="gap-3 col-lg-3 d-flex align-items-center">
                                                    <input class="form-control" type="password"
                                                        name="credentials[0][password]" placeholder="Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gap-10 mt-10 d-flex align-items-center justify-content-start">
                                            <button class="btn btn-sm fs-7 btn-neutral-500" type="button"
                                                id="addCredential">Add
                                                More</button>
                                            <button class="btn btn-sm fs-7 btn-neutral-900"
                                                type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            @if ($project->seoProject)
                <div class="mb-10 row gy-3">
                    <div class="col-lg-12">
                        <div class="border card">
                            <div class="card-header">
                                <h6 class="mb-0 mb-2 text-lg fw-bold">Seo Detail</h6>
                            </div>
                            <div class="p-24 card-body">
                                <div class="flex-row flex-wrap gap-24 d-flex">
                                    <div class="gap-3 d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-md fw-bold">Number of Keywords</h6>
                                            <span
                                                class="text-sm text-secondary-light fw-normal">{{ $project->seoProject->number_of_keywords ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="gap-3 d-flex align-items-center">
                                        <div
                                            class="flex-shrink-0 w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-warning-100">
                                            1
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="mb-0 text-md fw-bold">
                                                {{ $project->seoProject->milestone_1_percentage ?? '-' }}%</h6>
                                            <span class="text-sm text-secondary-light fw-normal">
                                                {{ $project->seoProject->milestone_1_date ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="gap-3 d-flex align-items-center">
                                        <div
                                            class="flex-shrink-0 w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-lilac-100">
                                            2
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="mb-0 text-md fw-bold">
                                                {{ $project->seoProject->milestone_2_percentage ?? '-' }}%</h6>
                                            <span class="text-sm text-secondary-light fw-normal">
                                                {{ $project->seoProject->milestone_2_date ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="gap-3 d-flex align-items-center">
                                        <div
                                            class="flex-shrink-0 w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-lilac-100">
                                            3
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="mb-0 text-md fw-bold">
                                                {{ $project->seoProject->milestone_3_percentage ?? '-' }}%</h6>
                                            <span class="text-sm text-secondary-light fw-normal">
                                                {{ $project->seoProject->milestone_3_date ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mb-10 row gy-3">
                <div class="col-lg-12">
                    <div class="border card">
                        <div class="card-header">
                            <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 mb-2 text-lg fw-bold">Project Description</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <textarea readonly class="p-0 m-0 bg-transparent border-0 w-100 h-100" name="description" id="description"x>{{ $project->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                    auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                    auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                    auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                    auth()->user()->hasRole('ADMIN') ||
                    ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                <div class="mb-10 row gy-3">
                    <div class="col-lg-12">
                        <div
                            class="p-0 overflow-hidden border border-secondary card position-relative radius-12 h-100">
                            <div
                                class="flex-wrap gap-1 px-24 pt-16 border card-header bg-base border-end-0 border-start-0 border-top-0 d-flex align-items-center justify-content-between">
                                <ul class="mb-0 nav border-gradient-tab nav-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center active" id="regular-tab"
                                            data-bs-toggle="pill" data-bs-target="#regular-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-to-do-list" aria-selected="true">
                                            Regular Tasks
                                            <span
                                                class="px-12 py-6 text-sm text-white fw-semibold bg-neutral-500 rounded-pill line-height-1 ms-12 notification-alert">{{ $regularTasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="sequence-tab"
                                            data-bs-toggle="pill" data-bs-target="#sequence-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Sequence Tasks
                                            <span
                                                class="px-12 py-6 text-sm text-white fw-semibold bg-neutral-500 rounded-pill line-height-1 ms-12 notification-alert">{{ $sequenceTasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="repeating-tab"
                                            data-bs-toggle="pill" data-bs-target="#repeating-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Repeating Tasks
                                            <span
                                                class="px-12 py-6 text-sm text-white fw-semibold bg-neutral-500 rounded-pill line-height-1 ms-12 notification-alert">{{ $repeatingTasks->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="completed-tab"
                                            data-bs-toggle="pill" data-bs-target="#completed-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Completed Tasks
                                            <span
                                                class="px-12 py-6 text-sm text-white fw-semibold bg-neutral-500 rounded-pill line-height-1 ms-12 notification-alert">{{ $completedTasks->count() }}</span>
                                        </button>
                                    </li>
                                </ul>
                                <a href="{{ route('tasks.index') }}"
                                    class="gap-1 text-primary-600 hover-text-primary d-flex align-items-center">
                                    Tasks
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
                            </div>
                            <div class="p-0 card-body">
                                <div class="tab-content" id="task-tabContent">
                                    <!-- Regular Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;"
                                        class="tab-pane fade show active" id="regular-task" role="tabpanel">
                                        @if ($regularTasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count())
                                            <table class="table mb-0 bordered-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>Due Date</th>
                                                        <th>Assignee</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($regularTasks->whereIn('status', ['Todo', 'In Progress', 'Review']) as $task)
                                                        <tr>
                                                            <td>{{ $task->id }}</td>
                                                            <td><a
                                                                    href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                            </td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td>{{ $task->due_date }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'N/A' }}</td>
                                                            {{-- <td><x-badge :text="$task->priority" type="priority" /></td> --}}
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="gap-3 d-flex align-items-start">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="gap-2 d-flex align-items-center">
                                                                                        <span
                                                                                            class="mb-0 text-secondary fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span
                                                                                    class="comment-time text-secondary d-block fs-7"
                                                                                    data-time="{{ $task->comments()->latest()->first()->created_at }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="my-3 ps-24 fs-7 fw-bold text-secondary-light">No Regular Tasks
                                                Available.</p>
                                        @endif
                                    </div>
                                    <!-- Sequence Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;" class="tab-pane fade"
                                        id="sequence-task" role="tabpanel">
                                        @if ($sequenceTasks->count())
                                            <table class="table mb-0 bordered-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>Due Date</th>
                                                        <th>Assignee</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sequenceTasks->whereIn('status', ['Todo', 'In Progress', 'Review']) as $task)
                                                        <tr>
                                                            <td>{{ $task->id }}</td>
                                                            <td><a
                                                                    href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                            </td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td>{{ $task->due_date }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            {{-- <td><x-badge :text="$task->priority" type="priority" /></td> --}}
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="gap-3 d-flex align-items-start">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="gap-2 d-flex align-items-center">
                                                                                        <span
                                                                                            class="mb-0 text-secondary fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span
                                                                                    class="comment-time text-secondary d-block fs-7"
                                                                                    data-time="{{ $task->comments()->latest()->first()->created_at }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="my-3 ps-24 fs-7 fw-bold text-secondary-light">No Sequence Tasks
                                                Available.</p>
                                        @endif
                                    </div>
                                    <!-- Repeating Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;" class="tab-pane fade"
                                        id="repeating-task" role="tabpanel">
                                        @if ($repeatingTasks->count())
                                            <table class="table mb-0 bordered-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>Due Date</th>
                                                        <th>Assignee</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($repeatingTasks as $task)
                                                        <tr>
                                                            <td>{{ $task->id }}</td>
                                                            <td><a
                                                                    href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                            </td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td>{{ $task->due_date }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            {{-- <td><x-badge :text="$task->priority" type="priority" /></td> --}}
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="gap-3 d-flex align-items-start">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="gap-2 d-flex align-items-center">
                                                                                        <span
                                                                                            class="mb-0 text-secondary fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span
                                                                                    class="comment-time text-secondary d-block fs-7"
                                                                                    data-time="{{ $task->comments()->latest()->first()->created_at }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="my-3 ps-24 fs-7 fw-bold text-secondary-light">No Repeating Tasks
                                                Available.</p>
                                        @endif
                                    </div>
                                    <!-- Completed Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;" class="tab-pane fade"
                                        id="completed-task" role="tabpanel">
                                        @if ($completedTasks->count())
                                            <table class="table mb-0 bordered-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>Due Date</th>
                                                        <th>Assignee</th>
                                                        <th>Completion Date</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($completedTasks as $task)
                                                        <tr>
                                                            <td>{{ $task->id }}</td>
                                                            <td><a
                                                                    href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                            </td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td>{{ $task->due_date }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            <td>{{ $task->completed_date ?? 'N/A' }}</td>
                                                            {{-- <td><x-badge :text="$task->priority" type="priority" /></td> --}}
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="gap-3 d-flex align-items-start">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="gap-2 d-flex align-items-center">
                                                                                        <span
                                                                                            class="mb-0 text-secondary fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span
                                                                                    class="comment-time text-secondary d-block fs-7"
                                                                                    data-time="{{ $task->comments()->latest()->first()->created_at }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="my-3 ps-24 fs-7 fw-bold text-secondary-light">No Repeating Tasks
                                                Available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mb-10 row gy-3">
                @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        auth()->user()->hasRole('SEO ADMIN') ||
                        auth()->user()->hasRole('Accountant') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <div class="col-lg-6">
                        <div class="border card">
                            <div class="card-header">
                                <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 mb-2 text-lg fw-bold">Contract Files</h6>
                                    @if ($userPermissions->contains('can add project files'))
                                        <button data-bs-toggle="modal" data-bs-target="#projectAttachmentsModal"
                                            class="gap-1 text-primary-600 hover-text-primary d-flex align-items-center">
                                            Add Attachments
                                            <iconify-icon icon="solar:alt-arrow-right-linear"
                                                class="icon"></iconify-icon>
                                        </button>
                                        <div class="modal fade" id="projectAttachmentsModal" tabindex="-1"
                                            aria-labelledby="projectAttachmentsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5"
                                                            id="projectAttachmentsModalLabel">
                                                            Choose
                                                            Project
                                                            Attachments
                                                        </h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input readonly class="form-control" type="file"
                                                            id="fileInput" multiple>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button"
                                                            onclick="uploadProjectFiles({{ $project->id }})"
                                                            class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @php
                                    // Combine all files: project-level + all renewal files
                                    $allFiles = collect([]);

                                    // Project-level files
                                    if (!empty($project->contractFiles)) {
                                        $allFiles = $allFiles->merge(
                                            $project->contractFiles->map(function ($file) {
                                                $file->source = 'Project';
                                                return $file;
                                            }),
                                        );
                                    }

                                    // Renewal files
                                    if (!empty($project->renewals)) {
                                        foreach ($project->renewals as $renewal) {
                                            foreach ($renewal->files ?? [] as $file) {
                                                $file->source =
                                                    'Renewal #' .
                                                    $renewal->id .
                                                    ($renewal->is_current ? ' (Current)' : '');
                                                $allFiles->push($file);
                                            }
                                        }
                                    }
                                @endphp

                                @if ($allFiles->count() > 0)
                                    <div class="gap-24 d-flex flex-column">
                                        @foreach ($allFiles as $file)
                                            @php
                                                $filePath = asset('storage/app/public/' . $file->file_path);
                                                $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                            @endphp
                                            <div
                                                class="gap-3 pb-2 d-flex align-items-center justify-content-between border-bottom">
                                                <div class="gap-12 d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if (in_array($extension, ['pdf']))
                                                            <i
                                                                class="bi bi-file-earmark-pdf-fill fs-3 text-danger"></i>
                                                        @elseif(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                            <i class="bi bi-file-earmark-image fs-3 text-primary"></i>
                                                        @else
                                                            <i class="bi bi-file-earmark-text fs-3 text-secondary"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 text-md fw-normal">
                                                            <a target="_blank" href="{{ $filePath }}">
                                                                {{ Illuminate\Support\Str::limit($file->file_name, 20, '...') }}
                                                            </a>
                                                        </h6>
                                                        <span class="text-xs text-secondary-light fw-normal">
                                                            {{ strtoupper($extension) }} • {{ $file->source }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="gap-12 flex-align">
                                                    <a href="{{ $filePath }}" download
                                                        class="w-32-px h-32-px d-inline-flex justify-content-center align-items-center bg-success-100 text-success-600 bg-hover-success-600 text-hover-white text-md rounded-circle">
                                                        <i class="ri-download-2-fill"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-danger">No contract files attached.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif
                @if (!empty($sequenceTasksFiles))
                    @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                            auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                            auth()->user()->hasRole('Accountant') ||
                            auth()->user()->hasRole('ADMIN') ||
                            ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                        <div class="col-lg-6">
                            <div class="border card">
                                <div class="card-header">
                                    <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 mb-2 text-lg fw-bold">Task Files</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="gap-24 d-flex flex-column">
                                        @foreach ($sequenceTasksFiles as $file)
                                            <div class="gap-3 d-flex align-items-center justify-content-between">
                                                <div class="gap-12 d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('/images/home-eight/icon-pdf.png') }}"
                                                            alt="" class="">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 text-md fw-normal">
                                                            <a target="_blank"
                                                                href="{{ asset('/public/storage') }}/{{ $file['file'] }}">{{ $file['name'] }}</a>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="gap-12 flex-align">
                                                    <a href="{{ asset('/public/storage') }}/{{ $file['file'] }}"
                                                        download
                                                        class="w-32-px h-32-px d-inline-flex justify-content-center align-items-center bg-success-100 text-success-600 bg-hover-success-600 text-hover-white text-md rounded-circle">
                                                        <i class="ri-download-2-fill"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        // auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        auth()->user()->hasRole('Accountant') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <div class="col-lg-6">
                        <div class="border card">
                            <div class="card-header">
                                <div class="flex-wrap gap-2 d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 mb-2 text-lg fw-bold">Handover Notes</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <textarea readonly required class="p-0 m-0 bg-transparent border-0 w-100 h-100" name="handover_notes"
                                    id="handover_notes">{{ $currentRenewal->handover_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-xxl-6 col-md-6">
                    <div class="p-0 overflow-hidden border card border-secondary position-relative radius-12 h-100">
                        <div class="px-24 py-16 card-header bg-base d-flex align-items-center justify-content-between">
                            <span class="fw-bold">Collaborators</span>
                            @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                                    auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                    auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                                    auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                                    auth()->user()->hasRole('ADMIN') ||
                                    ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                                <select onchange="addProjectCollaborator('{{ $project->id }}')" id="user_id"
                                    class="w-auto form-select">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="p-24 card-body">
                            <div class="mb-10 row gy-3" id="collaborators-list"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-md-6">
                    <div class="p-0 overflow-hidden border card border-secondary position-relative radius-12 h-100">
                        <div class="px-24 py-16 card-header bg-base d-flex align-items-center justify-content-between">
                            <span class="fw-bold">Your Project Notes</span>
                        </div>
                        <div class="p-24 card-body">
                            <form id="note-form">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <div class="mb-3 input-group">
                                    <input type="text" name="note" class="form-control"
                                        placeholder="Write a note...">
                                    <button class="btn btn-primary" type="submit">Add Note</button>
                                </div>
                            </form>
                            <ul class="list-group" id="notes-list"></ul>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-0 overflow-hidden border card border-secondary position-relative radius-12 h-100">
                        <div class="px-24 py-16 card-header bg-base d-flex align-items-center justify-content-between">
                            <span class="fw-bold">All Contract Renewals</span>
                        </div>
                        <div class="p-24 card-body">
                            <table class="table align-middle table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">
                                            <i class="bi bi-calendar-event"></i> Start
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-calendar-check"></i> End
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-clock-history"></i> Term
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-cash-coin"></i> Value (AED)
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-gift"></i> Free Months
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-person-circle"></i> Created By
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-people"></i> Assigned Roles
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->renewals as $index => $renewal)
                                        <tr class="{{ $renewal->is_current ? 'table-warning' : '' }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($renewal->contract_start_date)->format('d M Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($renewal->contract_start_date)->addMonths($renewal->contract_term)->format('d M Y') }}
                                                @if ($renewal->is_current)
                                                    <span class="badge bg-success ms-1">Current</span>
                                                @endif
                                            </td>
                                            <td>{{ $renewal->contract_term }} <small
                                                    class="text-muted">months</small></td>
                                            <td><strong>{{ number_format($renewal->contract_value, 2) }}</strong></td>
                                            <td>{{ $renewal->free_months ?? 0 }}</td>
                                            <td>
                                                <i class="bi bi-person-badge"></i>
                                                {{ $renewal->creator->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @forelse ($renewal->assignments as $assign)
                                                    <span class="mb-1 badge bg-secondary">
                                                        <i class="bi bi-person-gear"></i> {{ $assign->role_type }}:
                                                        {{ $assign->user->name }}
                                                    </span><br>
                                                @empty
                                                    <span class="text-muted">No roles assigned</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <span class="offcanvas-title" id="offcanvasRightLabel">Task Detail</span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="taskDetails">
            ...
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contactPersonDetailModal" tabindex="-1" aria-labelledby="contactPersonDetailModal"
        aria-hidden="true">
        <div class="modal-dialog bg-base">
            <div class="modal-content bg-base">
                <div class="modal-header bg-base">
                    <h1 class="modal-title fs-5" id="contactPersonDetailModal">Company
                        Contact Persons</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-base" style="max-height: 400px; overflow-y: auto;">
                    <div class="gap-3 d-flex flex-column">
                        @if ($project->companies)
                            @foreach ($project->companies->details as $index => $details)
                                <div class="p-3 mb-10 border rounded text-start bg-base">
                                    <div class="border-bottom"><strong>name:</strong>
                                        {{ $details->name }}
                                    </div>
                                    <div class="border-bottom"><strong>email:</strong>
                                        {{ $details->email }}
                                    </div>
                                    <div class="border-bottom"><strong>phone:</strong>
                                        {{ $details->phone }}
                                    </div>
                                    <div><strong>city:</strong> {{ $details->city }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <form id="task-create-form" action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
        <div class="modal fade" id="taskCreateModal" tabindex="-1" aria-labelledby="taskCreateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="taskCreateModalLabel">Create Task</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @include('tasks.create', [
                            'projectId' => $project->id,
                            'departments' => $departments,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn task-submit btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <span class="offcanvas-title" id="offcanvasRightLabel">Task Detail</span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="taskDetails">
            ...
        </div>
    </div>
    <div class="modal fade" id="taskAttachmentsModal" tabindex="-1" aria-labelledby="taskAttachmentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="taskAttachmentsModalLabel">Upload Task Attachments</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadTaskAttachmentsForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="task_id" value="">
                        <div class="mb-3">
                            <label for="taskAttachments" class="form-label">Select Files</label>
                            <input type="file" name="attachments[]" id="taskAttachments" class="form-control"
                                multiple required>
                        </div>

                        <button type="submit" class="btn btn-primary">Upload Files</button>
                    </form>
                    <div id="taskUploadStatus"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- In your Blade somewhere -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="cancelProjectForm">
                @csrf
                <input type="hidden" name="project_id" id="cancel_project_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Project</h5>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" placeholder="Enter reason..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Submit Cancellation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#renewProjectForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('projects.renew', $project->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#renewProjectModal').modal('hide');
                    location.reload(); // or use toast notification
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let messages = Object.values(errors).flat().join('<br>');
                    alert(messages); // replace with a Bootstrap alert or toast if needed
                }
            });
        });
    </script>


    <script>
        $(document).on('click', '.cancel-project', function() {
            let projectId = $(this).data('project-id');
            $('#cancel_project_id').val(projectId);
            $('#cancelModal').modal('show');
        });

        $('#cancelProjectForm').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.post("{{ route('projects.cancel') }}", formData, function(res) {
                $('#cancelModal').modal('hide');
                alert('Cancellation request submitted for admin approval.');
            }).fail(function() {
                alert('Error occurred.');
            });
        });


        $(document).on('click', '.taskCreateModal', function() {
            if ($(this).data('recurring')) {
                $('#repeat_options').html(
                    `
                    <div class="card-header">
                        <label for="repeat_task">Repeat this task every</label>
                        <input type="checkbox" checked id="repeat_task" name="repeat_task" value="1">
                    </div>
                    <div class="card-body">
                        <div class="gap-10 d-flex align-items-center w-100">
                            <input type="number" name="count" id="count" class="border rounded form-control w-100-px"
                                min="1" value="1">
                            <select name="repeat_every" id="repeat_every" class="w-auto border rounded form-control">
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                            </select>
                            <label for="start_from" class="form-label">start from</label>
                            <input type="date" placeholder="start from" name="start_from" id="start_from"
                            class="w-auto border rounded form-control">
                            <label for="end_date" class="form-label">end date (optional)</label>
                            <input placeholder="end date (optional):" type="date" name="end_date" id="end_date"
                                class="w-auto border rounded form-control">
                        </div>
                    </div>
                    `
                );
            }
        })

        const projectId = "{{ $project->id }}";
        const fetchUrl = @json(route('project-notes.fetch', ['projectId' => $project->id]));
        const storeUrl = @json(route('project-notes.store'));
        const destroyUrl = (id) => `{{ route('project-notes.destroy', ['note' => ':id']) }}`.replace(':id', id);

        function loadNotes() {
            $.get(fetchUrl, function(notes) {
                let html = '';
                notes.forEach(note => {
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">${note.user.name} <small class="text-muted">(${new Date(note.created_at).toLocaleString()})</small></div>
                                ${note.note}
                            </div>
                            ${note.user_id === {{ auth()->id() }} ? `
                                                                                                                                                                                            <button class="btn btn-sm btn-danger delete-note" data-id="${note.id}">Delete</button>` : ''}
                        </li>`;
                });
                $('#notes-list').html(html);
            });
        }

        $(document).ready(function() {
            loadNotes();

            $('#note-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: storeUrl,
                    data: $(this).serialize(),
                    success: function(res) {
                        $('input[name="note"]').val('');
                        loadNotes();
                    }
                });
            });

            $(document).on('click', '.delete-note', function() {
                const id = $(this).data('id');
                $.ajax({
                    type: 'DELETE',
                    url: destroyUrl(id),
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadNotes();
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        var uploadedDocumentMap = {};
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#taskAttachmentsDropzone", {
            url: "{{ route('tasks.store') }}", // Laravel route for form submission
            autoProcessQueue: false,
            uploadMultiple: true,
            paramName: "attachments", // Ensure Laravel handles it as an array
            maxFiles: 10,
            maxFilesize: 5, // 5MB per file
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp",
            addRemoveLinks: true,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            init: function() {
                var dropzoneInstance = this;
                dropzoneInstance.on("sending", function(file, xhr, formData) {
                    var form = document.querySelector("#task-create-form");
                    var formDataEntries = new FormData(form);
                    formDataEntries.forEach((value, key) => {
                        formData.append(key, value);
                    });
                    formData.append("attachments[]", file);
                });
                dropzoneInstance.on("success", function(file, response) {
                    uploadedDocumentMap[file.name] = response.name;
                });
                dropzoneInstance.on("removedfile", function(file) {
                    if (uploadedDocumentMap[file.name]) {
                        delete uploadedDocumentMap[file.name];
                    }
                });
            }
        });
        $('.task-submit').click(function(e) {
            $(this).attr('disabled', true);
            $(this).text('Saving...');
            setTimeout(() => {
                $(this).attr('disabled', false);
                $(this).text('Submit');
            }, 3000);
            e.preventDefault();
            var formData = new FormData($("#task-create-form")[0]);
            myDropzone.files.forEach(function(file) {
                formData.append("attachments[]", file);
            });
            $.ajax({
                url: "{{ route('tasks.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.href = "{{ route('tasks.index') }}"
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error in submission!");
                }
            });
        });
    </script>
    <script>
        $('.addmorecred').click(function(e) {
            e.preventDefault();
            $('#clientCredentialsForm').slideDown();
        });
        $("#clientCredentialsForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) {
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
        let credentialIndex = 1;
        $("#addCredential").click(function() {
            $("#credentialsContainer").append(`
            <div class="mb-10 credentialRow row">
                <div class="col-lg-3">
                    <input required class="form-control" type="text"
                        name="credentials[${credentialIndex}][platform_name]" placeholder="Platform Name">
                </div>
                <div class="col-lg-3">
                    <input class="form-control" type="text" name="credentials[${credentialIndex}][url]"
                        placeholder="Website URL">
                </div>
                <div class="col-lg-3">
                    <input required class="form-control" type="text" name="credentials[${credentialIndex}][username]"
                        placeholder="Username/Email">
                </div>
                <div class="gap-3 col-lg-3 d-flex align-items-center">
                    <input class="form-control" type="password" name="credentials[${credentialIndex}][password]"
                        placeholder="Password">
                    <button type="button"
                    class="removeCredential btn fs-7 btn-danger btn-sm">Remove</button>
                </div>
            </div>
            `);
            credentialIndex++;
        });
        $(document).on("click", ".removeCredential", function() {
            $(this).closest(".credentialRow").remove();
        });
    </script>
    <script>
        function fetchProjectCollaborators(projectId) {
            $.get("{{ route('project.collaborators', ['project' => ':id']) }}".replace(':id', projectId), function(
                response) {
                $('#collaborators-list').empty();
                response.forEach(collaborator => {
                    $('#collaborators-list').append(`
                            <div class="col-sm-6">
                                <div class="gap-2 d-flex align-items-start">
                                    <img src="{{ asset('/public/storage') }}/${collaborator.user.img}" class="w-40-px h-40-px rounded-pill" />
                                    <div class="flex-grow-1 d-inline-flex flex-column">
                                        <h6 class="mb-0 text-sm">${collaborator.user.name}</h6>
                                        <span class="text-xs text-secondary-light">${collaborator.user.email}</span>
                                    </div>
                                     @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                                             auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                             auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                                             auth()->user()->id == $project->seoAdmins->pluck('id')->first() ||
                                             auth()->user()->hasRole('ADMIN') ||
                                             ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <iconify-icon onclick="removeProjectCollaborator(${collaborator.id}, ${projectId})" icon="fluent:delete-24-regular"></iconify-icon>
                    @endif
                                </div>
                            </div>
                        `);
                });
            });
        }

        function addProjectCollaborator(projectId) {
            $.post("{{ route('project.collaborators.store') }}", {
                project_id: projectId,
                user_id: $('#user_id').val(),
                _token: "{{ csrf_token() }}"
            }, function(response) {
                fetchProjectCollaborators(projectId);
                toastr.success(response.message);
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON.message);
            });
        }

        function removeProjectCollaborator(id, projectId) {
            $.ajax({
                url: "{{ route('project.collaborators.destroy', ['collaborator' => ':id']) }}".replace(':id', id),
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    fetchProjectCollaborators(projectId);
                    toastr.success(response.message);
                }
            });
        }
        $(document).ready(() => {
            fetchProjectCollaborators("{{ $project->id }}");
        });
    </script>
    <script>
        function timeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);
            const intervals = [{
                    label: "year",
                    seconds: 31536000
                },
                {
                    label: "month",
                    seconds: 2592000
                },
                {
                    label: "week",
                    seconds: 604800
                },
                {
                    label: "day",
                    seconds: 86400
                },
                {
                    label: "hour",
                    seconds: 3600
                },
                {
                    label: "minute",
                    seconds: 60
                },
            ];

            for (const interval of intervals) {
                const count = Math.floor(seconds / interval.seconds);
                if (count > 0) {
                    return `${count} ${interval.label}${count !== 1 ? "s" : ""} ago`;
                }
            }
            return seconds < 10 ? "Just now" : `${seconds} seconds ago`;
        }

        function updateTimestamps() {
            document.querySelectorAll(".comment-time").forEach(element => {
                const timestamp = element.getAttribute("data-time");
                element.textContent = timeAgo(timestamp);
                this.innerText = timeAgo(timestamp);
            });
        }
        setInterval(updateTimestamps, 30000);
        updateTimestamps()
    </script>
    <script>
        function deleteProjectFile(projectId, fileId) {
            if (confirm("Are you sure you want to delete this file?")) {
                fetch(`{{ route('projects.deleteFile', ['project' => '__PROJECT_ID__', 'file' => '__FILE_ID__']) }}`
                        .replace('__PROJECT_ID__', projectId).replace('__FILE_ID__', fileId), {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                "Content-Type": "application/json",
                            }
                        })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.info("File deleted successfully!", "Info");
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }

        function uploadProjectFiles(projectId) {
            let fileInput = document.getElementById("fileInput");
            if (fileInput.files.length === 0) {
                toastr.info("Please select at least one file!", "Info");
                return;
            }

            let formData = new FormData();
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append("contract_copy[]", fileInput.files[i]);
            }
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

            fetch(`{{ route('projects.uploadFiles', ['project' => '__PROJECT_ID__']) }}`.replace('__PROJECT_ID__',
                    projectId), {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success("File Uploaded Successfully!", "Success");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
    <script>
        $(document).on('click', '.delete-credential', function() {
            let credId = $(this).data('id');
            let deleteUrl = "{{ route('credentials.delete', ['project' => $project->id]) }}";
            if (!confirm('Are you sure you want to delete this credential?')) return;
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    id: credId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred');
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".project-status").forEach(function(select) {
                select.addEventListener("change", function() {
                    let projectId = this.getAttribute("data-project-id");
                    let newStatus = this.value;

                    fetch("{{ route('projects.updateStatus', ':id') }}".replace(':id',
                            projectId), {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute("content"),
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success("Project Status Updated!", "Success");
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".show-contact-person").click(function() {
                $(".contact-person-info-wrapper").slideDown();
            });
        });
        $(document).on('click', '.toggle-password', function() {
            let passwordSpan = $(this).prev('.password-toggle');
            let actualPassword = passwordSpan.data('password');

            if (passwordSpan.text() === '••••••') {
                passwordSpan.text(actualPassword);
                $(this).text('Hide');
            } else {
                passwordSpan.text('••••••');
                $(this).text('Show');
            }
        });
    </script>
    <script>
        $('#user_id').select2({
            placeholder: 'select collaborator',
            allowClear: true,
            width: '100%', // Full width
            closeOnSelect: true // Prevent closing on select
        });
    </script>
</x-app-layout>
