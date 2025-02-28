<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    @if ($role === 'AM')
        <div class="row gy-4">
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon1.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">Current Portfolio</span>
                                    <h6 class="fw-semibold mt-2">{{ number_format($currentPortfolio, 2) }}</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i> 0%
                            </span> Last month 0.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-2 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon2.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">Current Revenue</span>
                                    <h6 class="fw-semibold mt-2">$35,000</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i> 95%
                            </span> Last month $1,600.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-3 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-lilac-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon3.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">4 Months Avg</span>
                                    <h6 class="fw-semibold mt-2">$50,000</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-danger-focus px-6 py-2 rounded-2 fw-medium text-danger-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-down-line"></i> 70%
                            </span> Last month $24,000.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-4 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-success-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon4.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">New / Renew / UpSell</span>
                                    <h6 class="fw-semibold mt-2">$50,000</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i> 95%
                            </span> Last month $2,500.00
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Managed Projects</h5>
                <ul class="list-group">
                    @forelse($projects as $project)
                        <li class="list-group-item">
                            <strong>{{ $project->name }}</strong> <br>
                            Contract Value: ${{ number_format($project->contract_value, 2) }} <br>
                            Contract Term: {{ $project->contract_term }} months <br>
                            Monthly Value: ${{ number_format($project->contract_value / $project->contract_term, 2) }}
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No projects assigned.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endif
</x-app-layout>
