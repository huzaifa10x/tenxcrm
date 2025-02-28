<x-app-layout>
    <x-slot name="header">
        {{ __('Project - ' . $project->title) }}
    </x-slot>
    @if ($userPermissions->contains('can view all projects') || $userPermissions->contains('can view own projects'))
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    @if ($userPermissions->contains('can create tasks'))
                        <button data-bs-toggle="modal" data-bs-target="#taskCreateModal"
                            class="btn-sm btn btn-outline-primary">
                            Create Task
                        </button>
                    @endif
                    @if ($userPermissions->contains('can update project status'))
                        <div class="d-flex align-items-center gap-10" style="white-space: nowrap;">
                            <label class="form-label fs-7 m-0">Project Status</label>
                            <select class="form-control fs-7 h-auto project-status"
                                data-project-id="{{ $project->id }}" required>
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
                                <option value="Cancelled" {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                                <option value="Completed" {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-10" style="white-space: nowrap;">
                            <label class="form-label fs-7 m-0">Project Status</label>
                            <input type="text" readonly class="form-control fs-7 h-auto"
                                value="{{ $project->status }}">
                        </div>
                    @endif
                </div>
                @php
                    $taskTypes = ['CONTENT', 'ONSITE', 'OFFSITE', 'DESIGN', 'DEVELOPMENT', 'AM'];
                @endphp
                <div class="d-flex flex-wrap align-items-center gap-3">
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
                                default => 'secondary', // Fallback for unknown types
                            };
                        @endphp

                        <div class="d-flex flex-wrap align-items-center gap-3">
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
                <div class="row gy-3 mb-10">
                    <div class="col-lg-3">
                        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                            <img src="{{ asset('/images/user-grid/user-grid-bg1.png') }}" alt=""
                                class="w-100 object-fit-cover">
                            <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                                <div class="text-center border border-top-0 border-start-0 border-end-0">
                                    <img src="{{ asset('/public/storage/') }}/{{ $project->companies->logo }}" alt=""
                                        class="mt-10 w-200-p p-10 h-200-px rounded object-fit-cover"
                                        style="backdrop-filter: blur(9px);">
                                    <h6 class="mb-0 mt-16">{{ $project->companies->name }}</h6>
                                    <span
                                        class="text-secondary-light mb-16">{{ optional(optional($project->companies)->details->first())->email ?? '' }}
                                    </span>
                                </div>
                                <div class="my-10">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="border p-3 rounded bg-info-50 credential-item">
                                            <div><strong>Website:</strong> {{ $project->companies->website }}</div>
                                            <div><strong>City:</strong> {{ $project->companies->city }}</div>
                                            <div><strong>Country:</strong> {{ $project->companies->country }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($project->companies->details as $index => $details)
                                        <div
                                            class="border p-3 rounded bg-light credential-item mb-10 {{ $index > 0 ? 'd-none' : '' }}">
                                            <div><strong>name:</strong> {{ $details->name }}</div>
                                            <div><strong>email:</strong> {{ $details->email }}</div>
                                            <div><strong>phone:</strong> {{ $details->phone }}</div>
                                            <div><strong>city:</strong> {{ $details->city }}</div>
                                            <div><strong>Website:</strong> <a href="{{ $details->website }}"
                                                    target="_blank">{{ $details->website }}</a></div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (count($project->credentials) > 1)
                                    <button id="showMoreCredentials" class="btn btn-sm fs-7 btn-primary mt-2">Show More
                                        Contacts</button>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Sales Brief</h6>
                            </div>
                            <div class="card-body" style="height: 500px; overflow-y: auto;">
                                <div class="">
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Project Type</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{ $project->project_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Sales Manger</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{ $project->salesManagers->pluck('name')->first() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Account Manager</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-lilac-600 bg-lilac-100 px-20 py-9 radius-4 text-white">{{ $project->accountManagers->pluck('name')->first() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Tech Manger</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-neutral-600 bg-neutral-100 px-20 py-9 radius-4 text-white">{{ $project->techManagers->pluck('name')->first() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Industry</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{ $project->industry->industry }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($project->seoProject)
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Number of keywords</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->number_of_keywords ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 1st Milestone %
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_1_percentage ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 1st Milestone Date
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_1_date ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 2nd Milestone %
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-info-600 bg-info-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_2_percentage ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 2nd Milestone Date
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-info-600 bg-info-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_2_date ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 3rd Milestone %
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-lilac-600 bg-lilac-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_3_percentage ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Guarantee for 3rd Milestone Date
                                                    </h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-lilac-600 bg-lilac-100 px-20 py-9 radius-4 text-white">{{ $project->seoProject->milestone_3_date ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($project->project_type == 'Web Development')
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Payment Type</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->webDevProject->payment_type ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Remaining Payment</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->webDevProject->remaining_payment ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Site Condition</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->webDevProject->site_condition ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($project->project_type == 'Social Media')
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Channels</h6>
                                                    @foreach ($project->socialMediaProject->channels as $channel)
                                                        <span
                                                            class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $channel }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Video shoots Per Month</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->socialMediaProject->video_shoots_per_month ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Static Posts Per Month</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->socialMediaProject->static_posts ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="text-md mb-0 fw-medium">Reels Per Month</h6>
                                                    <span
                                                        class="mt-10 badge text-sm fw-semibold text-primary-600 bg-primary-100 px-20 py-9 radius-4 text-white">{{ $project->socialMediaProject->reels ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Campaign Access</h6>
                            </div>
                            <div class="card-body" style="height: 500px; overflow-y: auto;">
                                @foreach ($project->credentials as $credential)
                                    <div class="my-10">
                                        <div class="text-capitalize"><strong>Platform:</strong>
                                            {{ $credential->platform_name }}</div>
                                        <div class="text-capitalize"><strong>URL:</strong> <a
                                                href="{{ $credential->url }}" target="_blank">Visit</a></div>
                                        <div class="text-capitalize"><strong>Username:</strong>
                                            {{ $credential->username }}</div>
                                        <div class="text-capitalize"><strong>Password:</strong>
                                            <span class="password-toggle text-muted"
                                                data-password="{{ $credential->password }}">••••••</span>
                                            <a href="javascript:void(0);" class="toggle-password small">Show</a>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Financial Details</h6>
                            </div>
                            <div class="card-body" style="height: 500px; overflow-y: auto;">
                                <div class="">
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Contract Start</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $project->contract_start_date }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Contract End</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $contractEndDate->toDateString() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Contract Value</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ number_format($project->contract_value, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Contract Term (Months)</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $project->contract_term }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Payment Method</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $project->paymentMethod->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Free Months</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $project->free_months }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Monthly Invoice</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ number_format($monthlyInvoice, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Next Invoice Date</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $nextInvoiceDate->toDateString() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Client Category:</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $project->category }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $startDate = \Carbon\Carbon::parse($project->contract_start_date);
                                        $endDate = $startDate->copy()->addMonths($project->contract_term);
                                        $now = \Carbon\Carbon::now();
                                        $remainingMonths = max(floor($now->diffInMonths($endDate, false)), 0);
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-24">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="text-md mb-0 fw-medium">Remaining Months:</h6>
                                                <span
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">
                                                    {{ $remainingMonths }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-3 mb-10">
                    <div class="col-lg-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-2 fw-bold text-lg mb-0">Project Description</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <textarea readonly class="border-0 bg-transparent p-0 m-0 w-100 h-100" name="description" id="description"x>{{ $project->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-3 mb-10">
                    <div class="col-lg-12">
                        <div
                            class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                            <div
                                class="card-header pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                                <h6 class="text-lg mb-0">Tasks</h6>
                                <ul class="nav bordered-tab d-inline-flex nav-pills mb-0 gap-3" id="task-tab"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <button id="regular-tab" data-bs-toggle="pill"
                                                data-bs-target="#regular-task" type="button" role="tab"
                                                class="active btn text-neutral-600 bg-neutral-100 position-relative px-20 py-8 text-sm line-height-1 d-flex align-items-center">
                                                Regular Tasks
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info-600 border border-white">
                                                    {{ $regularTasks->count() }}
                                                </span>
                                            </button>
                                        </div>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <button id="sequence-tab" data-bs-toggle="pill"
                                                data-bs-target="#sequence-task" type="button" role="tab"
                                                class="btn text-neutral-600 bg-neutral-100 position-relative px-20 py-8 text-sm line-height-1 d-flex align-items-center">
                                                Sequence Tasks
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info-600 border border-white">
                                                    {{ $sequenceTasks->count() }}
                                                </span>
                                            </button>
                                        </div>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <button id="repeating-tab" data-bs-toggle="pill"
                                                data-bs-target="#repeating-task" type="button" role="tab"
                                                class="btn text-neutral-600 bg-neutral-100 position-relative px-20 py-8 text-sm line-height-1 d-flex align-items-center">
                                                Repeating Tasks
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info-600 border border-white">
                                                    {{ $repeatingTasks->count() }}
                                                </span>
                                            </button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0">
                                <div class="tab-content" id="task-tabContent">
                                    <!-- Regular Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;"
                                        class="tab-pane fade show active" id="regular-task" role="tabpanel">
                                        @if ($regularTasks->count())
                                            <table class="table bordered-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Assignee</th>
                                                        <th>Start Date</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($regularTasks as $task)
                                                        <tr data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="loadTaskDetails({{ $task->id }})">
                                                            <td>{{ $task->id }}</td>
                                                            <td>{{ $task->title }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td><x-badge :text="$task->priority" type="priority" /></td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="d-flex align-items-start gap-3">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="d-flex align-items-center gap-2">
                                                                                        <span
                                                                                            class="mb-0 fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span class="comment-time d-block fs-7"
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
                                            <p class="text-secondary-light">No Regular Tasks Available.</p>
                                        @endif
                                    </div>
                                    <!-- Sequence Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;" class="tab-pane fade"
                                        id="sequence-task" role="tabpanel">
                                        @if ($sequenceTasks->count())
                                            <table class="table bordered-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Assignee</th>
                                                        <th>Start Date</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sequenceTasks as $task)
                                                        <tr data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="loadTaskDetails({{ $task->id }})">
                                                            <td>{{ $task->id }}</td>
                                                            <td>{{ $task->title }}</td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td><x-badge :text="$task->priority" type="priority" /></td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="d-flex align-items-start gap-3">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="d-flex align-items-center gap-2">
                                                                                        <span
                                                                                            class="mb-0 fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span class="comment-time d-block fs-7"
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
                                            <p class="text-secondary-light">No Sequence Tasks Available.</p>
                                        @endif
                                    </div>
                                    <!-- Repeating Tasks -->
                                    <div style="max-height: 500px; overflow-y: auto;" class="tab-pane fade"
                                        id="repeating-task" role="tabpanel">
                                        @if ($repeatingTasks->count())
                                            <table class="table bordered-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Assignee</th>
                                                        <th>Start Date</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($repeatingTasks as $task)
                                                        <tr data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="loadTaskDetails({{ $task->id }})">
                                                            <td>{{ $task->id }}</td>
                                                            <td>{{ $task->title }}<div
                                                                    class="badge rounded {{ $task->repeatingTask ? ($task->repeat_task ? 'bg-info-50 text-info' : 'bg-warning-50 text-warning') : '' }} fs-7">
                                                                    {{ $task->repeatingTask ? ($task->repeat_task ? ' - Repeating Enabled' : ' - Repeating Disabled') : '' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                                                            <td>{{ $task->start_date }}</td>
                                                            <td><x-badge :text="$task->priority" type="priority" /></td>
                                                            <td><x-badge :text="$task->status" type="status" /></td>
                                                        </tr>
                                                        @if ($task->comments()->latest()->first())
                                                            <tr>
                                                                <td colspan="7" class="bg-info-50 text-muted">
                                                                    <div style="max-width: 50%;">
                                                                        <div class="d-flex align-items-start gap-3">
                                                                            <img src="{{ asset('/public/storage/' . $task->comments()->latest()->first()->user->img) }}"
                                                                                alt=""
                                                                                class="w-40-px h-40-px rounded-pill">
                                                                            <div class="w-100">
                                                                                <div
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <div
                                                                                        class="d-flex align-items-center gap-2">
                                                                                        <span
                                                                                            class="mb-0 fs-7">{{ $task->comments()->latest()->first()->user->name }}
                                                                                            {{ $task->comments()->latest()->first()->user->id == auth()->id() ? '(you)' : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="mb-0 text-primary-light">
                                                                                    {{ $task->comments()->latest()->first()->comment }}
                                                                                </p>
                                                                                <span class="comment-time d-block fs-7"
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
                                            <p class="text-secondary-light">No Repeating Tasks Available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-3 mb-10">
                    <div class="col-lg-6">
                        @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
                            <div class="card border">
                                <div class="card-header">
                                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                        <h6 class="mb-2 fw-bold text-lg mb-0">Contract Files</h6>
                                        @if ($userPermissions->contains('can add project files'))
                                            <button data-bs-toggle="modal" data-bs-target="#projectAttachmentsModal"
                                                class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
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
                                                                id="projectAttachmentsModalLabel">Choose
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
                                    <div class="d-flex flex-column gap-24">
                                        @foreach ($project->contractFiles as $file)
                                            @php
                                                $filePath = asset('/public/storage/' . $file->file_path);
                                                $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                <div class="d-flex align-items-center gap-12">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset('/images/home-eight/icon-pdf.png') }}"
                                                            alt="" class="">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="text-md mb-0 fw-normal">
                                                            {{ Illuminate\Support\Str::limit($file->file_name, 10, '...') }}
                                                        </h6>
                                                        <span
                                                            class="text-xs text-secondary-light fw-normal">{{ $extension }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-align gap-12">
                                                    <button type="button"
                                                        onclick="deleteProjectFile({{ $project->id }}, {{ $file->id }})"
                                                        class="w-32-px h-32-px d-inline-flex justify-content-center align-items-center bg-danger-100 text-danger-600 bg-hover-danger-600 text-hover-white text-md rounded-circle">
                                                        <i class="ri-delete-bin-5-line"></i>
                                                    </button>
                                                    <a href="{{ $filePath }}" download
                                                        class="w-32-px h-32-px d-inline-flex justify-content-center align-items-center bg-success-100 text-success-600 bg-hover-success-600 text-hover-white text-md rounded-circle">
                                                        <i class="ri-download-2-fill"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        @else
                            <p class="text-danger">No contract files attached.</p>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-2 fw-bold text-lg mb-0">Handover Notes</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <textarea readonly required class="w-100 h-100 border-0 bg-transparent p-0 m-0" name="handover_notes"
                                    id="handover_notes">{{ $project->handover_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <span class="offcanvas-title" id="offcanvasRightLabel">Task Detail</span>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" id="taskDetails">
                ...
            </div>
        </div>
        <!-- Task Attachments Modal -->
        <div class="modal fade" id="taskAttachmentsModal" tabindex="-1" aria-labelledby="taskAttachmentsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="taskAttachmentsModalLabel">Upload Task Attachments</span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
        <!-- Modal -->
        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
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
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
            function loadTaskDetails(taskId) {
                $("input[name='task_id']").val(taskId);
                $('#taskDetails').html(
                    '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
                $.ajax({
                    url: '{{ route('tasks.show', ':id') }}'.replace(':id', taskId),
                    method: 'GET',
                    data: {
                        is_ajax: true
                    },
                    success: function(response) {
                        $('#taskDetails').html(response);
                        loadTaskAttachments();
                        loadComments();
                    },
                    error: function() {
                        $('#taskDetails').html('<p class="text-danger">Error fetching task details.</p>');
                    }
                });
            }
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

            // document.getElementById('deleteAllAttachmentsBtn').addEventListener('click', function() {
            //     if (confirm('Are you sure you want to delete all attachments?')) {
            //         // Send DELETE request using AJAX
            //         fetch("{{ route('projects.deleteAllFiles', ['project' => $project->id]) }}", {
            //                 method: 'DELETE',
            //                 headers: {
            //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
            //                         'content'),
            //                     'Content-Type': 'application/json',
            //                 }
            //             })
            //             .then(response => response.json())
            //             .then(data => {
            //                 if (data.success) {
            //                     alert('All attachments deleted successfully!');
            //                     location.reload(); // Refresh the page to reflect the changes
            //                 } else {
            //                     alert('Error: ' + data.message);
            //                 }
            //             })
            //             .catch(error => {
            //                 console.error('Error:', error);
            //                 alert('There was an error processing your request.');
            //             });
            //     }
            // });
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
                $("#showMoreCredentials").on("click", function() {
                    $(".credential-item.d-none").removeClass("d-none");
                    $(this).hide(); // Button hide after clicking
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
    @endif
</x-app-layout>
