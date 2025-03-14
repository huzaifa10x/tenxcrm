<x-app-layout>
    <x-slot name="header">
        {{ __('Project - ' . $project->title) }}
    </x-slot>

    <div class="card border">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-10">
            <div class="d-flex align-items-center gap-3">
                <button data-bs-toggle="modal" data-bs-target="#taskCreateModal"
                    class="btn-sm btn btn-outline-primary fs-7">
                    Create Task
                </button>
                @if ($userPermissions->contains('can update project status'))
                    <div class="d-flex align-items-center gap-10" style="white-space: nowrap;">
                        <label class="form-label fs-7 m-0">Project Status</label>
                        <select class="form-control fs-7 h-auto project-status" data-project-id="{{ $project->id }}"
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
                        <input type="text" readonly class="form-control fs-7 h-auto" value="{{ $project->status }}">
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
                            <div
                                class="text-center d-flex mt-3 flex-column align-items-center justify-content-center border border-top-0 border-start-0 border-end-0">
                                <img style="backdrop-filter: blur(9px);"
                                    src="{{ asset('/public/storage/') }}/{{ $project->companies->logo ?? 'N/A' }}"
                                    alt="" class="mt-10 img-fluid p-10 rounded">
                                @if ($project->companies)
                                    <span
                                        class="text-secondary-light mb-16">{{ optional(optional($project->companies)->details->first())->email ?? '' }}
                                    </span>
                                @endif
                            </div>
                            <div class="my-10">
                                <div class="d-flex flex-column gap-3">
                                    <div class="border p-3 rounded bg-info-50 credential-item">
                                        <div><strong>Website:</strong> {{ $project->companies->website ?? 'N/A' }}
                                        </div>
                                        <div><strong>City:</strong> {{ $project->companies->city ?? 'N/A' }}</div>
                                        <div><strong>Country:</strong> {{ $project->companies->country ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                @if ($project->companies)
                                    @foreach ($project->companies->details as $index => $details)
                                        <div
                                            class="border p-3 rounded bg-light credential-item mb-10 {{ $index > 0 ? 'd-none' : '' }}">
                                            <div><strong>name:</strong> {{ $details->name }}</div>
                                            <div><strong>email:</strong> {{ $details->email }}</div>
                                            <div><strong>phone:</strong> {{ $details->phone }}</div>
                                            <div><strong>city:</strong> {{ $details->city }}</div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if (count($project->credentials) > 1)
                                <button id="showMoreCredentials" class="btn btn-sm fs-7 btn-primary mt-2">Show More
                                    Contacts</button>
                            @endif

                        </div>
                    </div>
                </div>
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        auth()->user()->hasRole('SEO ADMIN') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
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
                                                    class="mt-10 badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">{{ $project->industry->industry ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

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
                @endif
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        auth()->user()->hasRole('SEO ADMIN') ||
                        $userPermissions->contains('can view financial details') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    <div class="col-md-6">
                        <div class="card radius-16">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-2 fw-bold text-lg mb-0">Financial Details</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-orange rounded-pill"></span>
                                        <span class="text-secondary-light">Contract Start</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-primary-light fw-semibold">
                                            {{ $project->contract_start_date }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-base">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Contract End</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $contractEndDate->toDateString() }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Contract Value</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="text-primary-light fw-semibold">{{ number_format($project->contract_value, 2) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-base">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Contract Term</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-primary-light fw-semibold">{{ $project->contract_term }}
                                            MO</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Payment Method</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $project->paymentMethod->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-base">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-cyan-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Free Months</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-primary-light fw-semibold">{{ $project->free_months }}
                                            MO</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-neutral-100">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-warning-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Monthly Invoice</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="text-primary-light fw-semibold">{{ number_format($monthlyInvoice, 2) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-12 bg-base">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Next Invoice Date</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="text-primary-light fw-semibold">{{ $nextInvoiceDate->toDateString() }}</span>
                                    </div>
                                </div>
                                @php
                                    $startDate = \Carbon\Carbon::parse($project->contract_start_date);
                                    $endDate = $startDate->copy()->addMonths($project->contract_term);
                                    $now = \Carbon\Carbon::now();
                                    $remainingMonths = max(floor($now->diffInMonths($endDate, false)), 0);
                                @endphp
                                <div class="d-flex align-items-center justify-content-between p-12 bg-base">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-12-px h-8-px bg-success-600 rounded-pill"></span>
                                        <span class="text-secondary-light">Remaining Months</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
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
                        auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        $userPermissions->contains('can view campaign access') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                    @if ($project->credentials->count())
                        <div class="col-lg-12">
                            <div class="card h-100">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="mb-2 fw-bold text-lg mb-0">Campaign Access</h6>
                                    <button class="btn border-0 p-0 m-0 addmorecred">Add More
                                        Credentials</button>
                                </div>
                                <div class="card-body row" style="max-height: 500px; overflow-y: auto;">
                                    @foreach ($project->credentials as $credential)
                                        <div class="col-lg-4 my-10">
                                            <div class="card border p-3 rounded">
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
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer">
                                    <form id="clientCredentialsForm" class="mt-10" style="display: none">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <div id="credentialsContainer">
                                            <div class="credentialRow row mb-10">
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
                                                <div class="col-lg-3 d-flex align-items-center gap-3">
                                                    <input class="form-control" type="password"
                                                        name="credentials[0][password]" placeholder="Password">
                                                    <button type="button"
                                                        class="removeCredential btn fs-7 btn-danger btn-sm">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start gap-10 mt-10">
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
                <div class="row gy-3 mb-10">
                    <div class="col-lg-12">
                        <div class="card border">
                            <div class="card-header">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Seo Detail</h6>
                            </div>
                            <div class="card-body p-24">
                                <div class="d-flex flex-wrap flex-row gap-24">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <h6 class="text-md mb-0 fw-bold">Number of Keywords</h6>
                                            <span
                                                class="text-sm text-secondary-light fw-normal">{{ $project->seoProject->number_of_keywords ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-warning-100 flex-shrink-0">
                                            1
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="text-md mb-0 fw-bold">
                                                {{ $project->seoProject->milestone_1_percentage ?? '-' }}%</h6>
                                            <span class="text-sm text-secondary-light fw-normal">
                                                {{ $project->seoProject->milestone_1_date ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-lilac-100 flex-shrink-0">
                                            2
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="text-md mb-0 fw-bold">
                                                {{ $project->seoProject->milestone_2_percentage ?? '-' }}%</h6>
                                            <span class="text-sm text-secondary-light fw-normal">
                                                {{ $project->seoProject->milestone_2_date ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-lilac-100 flex-shrink-0">
                                            3
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="text-sm text-secondary-light fw-normal fs-7">Guarantee</span>
                                            <h6 class="text-md mb-0 fw-bold">
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
            @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                    auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                    auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                    auth()->user()->hasRole('ADMIN') ||
                    ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                <div class="row gy-3 mb-10">
                    <div class="col-lg-12">
                        <div
                            class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                            <div
                                class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                                <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center active" id="regular-tab"
                                            data-bs-toggle="pill" data-bs-target="#regular-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-to-do-list" aria-selected="true">
                                            Regular Tasks
                                            <span
                                                class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $regularTasks->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="sequence-tab"
                                            data-bs-toggle="pill" data-bs-target="#sequence-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Sequence Tasks
                                            <span
                                                class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $sequenceTasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count() }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="repeating-tab"
                                            data-bs-toggle="pill" data-bs-target="#repeating-task" type="button"
                                            role="tab" type="button" role="tab"
                                            aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Repeating Tasks
                                            <span
                                                class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $repeatingTasks->count() }}</span>
                                        </button>
                                    </li>
                                </ul>
                                <a href="{{ route('tasks.board.index') }}"
                                    class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                    Tasks
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
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
                                                        <th>Subject</th>
                                                        <th>Status</th>
                                                        <th>Start Date</th>
                                                        <th>Due Date</th>
                                                        <th>Assignee</th>
                                                        {{-- <th>ACTION</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($regularTasks as $task)
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
                                            <p class="text-secondary-light">No Repeating Tasks Available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row gy-3 mb-10">
                <div class="col-lg-6">
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
                                                    <h1 class="modal-title fs-5" id="projectAttachmentsModalLabel">
                                                        Choose
                                                        Project
                                                        Attachments
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
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
                            @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
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
                                                        <a target="_blank"
                                                            href="{{ $filePath }}">{{ Illuminate\Support\Str::limit($file->file_name, 10, '...') }}</a>
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
                            @else
                                <p class="text-danger">No contract files attached.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                        auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                        auth()->user()->hasRole('ADMIN') ||
                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
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
                @endif
                <div class="col-xxl-6 col-md-6">
                    <div class="card border border-secondary p-0 overflow-hidden position-relative radius-12 h-100">
                        <div class="card-header py-16 px-24 bg-base d-flex align-items-center justify-content-between">
                            <span class="fw-bold">Collaborators</span>
                            @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                                    auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                    auth()->user()->id == $project->techManagers->pluck('id')->first() ||
                                    auth()->user()->hasRole('ADMIN') ||
                                    ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                                <select onchange="addProjectCollaborator('{{ $project->id }}')" id="user_id"
                                    class="form-select w-auto">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="card-body p-24">
                            <div class="row gy-3 mb-10" id="collaborators-list"></div>
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
    <!-- Task Attachments Modal -->
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
    <script>
        $('.addmorecred').click(function(e) {
            e.preventDefault();
            $('#clientCredentialsForm').slideDown();
        });
        $("#clientCredentialsForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
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
            <div class="credentialRow row mb-10">
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
                <div class="col-lg-3 d-flex align-items-center gap-3">
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
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{ asset('/public/storage') }}/${collaborator.user.img}" class="w-40-px h-40-px rounded-pill" />
                                    <div class="flex-grow-1 d-inline-flex flex-column">
                                        <h6 class="text-sm mb-0">${collaborator.user.name}</h6>
                                        <span class="text-xs text-secondary-light">${collaborator.user.email}</span>
                                    </div>
                                     @if (auth()->user()->id == $project->salesManagers->pluck('id')->first() ||
                                             auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                             auth()->user()->id == $project->techManagers->pluck('id')->first() ||
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
    <script>
        $('#user_id').select2({
            placeholder: 'select collaborator',
            allowClear: true,
            width: '100%', // Full width
            closeOnSelect: true // Prevent closing on select
        });
    </script>
</x-app-layout>
