<x-app-layout>
    <x-slot name="header">
        {{ __('Website Projects') }}
    </x-slot>
    <div class="container py-4">
        <div class="row">
            {{-- DASHBOARD STATS --}}
            <section class="mb-5 col-12">
                <h2 class="mb-4 text-2xl font-semibold text-gray-900">Overview</h2>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-3">

                    {{-- Total Projects --}}
                    <div class="col">
                        <div class="shadow-sm card bg-primary-subtle border-primary-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-primary">
                                    Total Projects
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-primary-emphasis">
                                    {{ $stats['total_projects'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- In Design --}}
                    <div class="col">
                        <div class="shadow-sm card bg-warning-subtle border-warning-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-warning">
                                    In Design
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-warning-emphasis">
                                    {{ $stats['in_design'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- In Development --}}
                    <div class="col">
                        <div class="shadow-sm card bg-info-subtle border-info-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-info">
                                    In Development
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-info-emphasis">
                                    {{ $stats['in_development'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Awaiting Approval --}}
                    <div class="col">
                        <div class="shadow-sm card bg-secondary-subtle border-secondary-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-secondary">
                                    Awaiting Approval
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-secondary-emphasis">
                                    {{ $stats['awaiting_approval'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Ready for Go Live --}}
                    <div class="col">
                        <div class="shadow-sm card bg-success-subtle border-success-subtle h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-success">
                                    Ready for Go Live
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-success-emphasis">
                                    {{ $stats['ready_for_go_live'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Delivered --}}
                    <div class="col">
                        <div class="shadow-sm card bg-light border-light h-100">
                            <div class="p-3 card-body">
                                <p class="mb-1 text-sm text-uppercase font-weight-bold text-muted">
                                    Delivered
                                </p>
                                <p class="mb-0 fs-4 fw-bold text-dark">
                                    {{ $stats['delivered'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="my-4">

            {{-- FILTERS --}}
            <section class="mb-5 col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h2 class="text-xl font-semibold text-gray-900">Filters</h2>
                    @if (request()->hasAny(['platform', 'design_status', 'developer_id', 'ready_for_go_live', 'overdue']))
                        <span class="border badge text-bg-success border-success-subtle rounded-pill">
                            Filters active
                        </span>
                    @endif
                </div>

                <form method="GET" action="{{ route('website-projects.index') }}"
                    class="p-4 shadow-sm card border-light">
                    <div class="row g-3">
                        {{-- Platform Filter --}}
                        <div class="col-md-3">
                            <label for="platform"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Platform</label>
                            <select name="platform" id="platform" class="form-select form-select-sm">
                                <option value="">All Platforms</option>
                                <option value="Wordpress" {{ request('platform') == 'Wordpress' ? 'selected' : '' }}>
                                    Wordpress</option>
                                <option value="Shopify" {{ request('platform') == 'Shopify' ? 'selected' : '' }}>Shopify
                                </option>
                                {{-- Add more as needed --}}
                            </select>
                        </div>

                        {{-- Design Status Filter --}}
                        <div class="col-md-3">
                            <label for="design_status"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Design
                                Status</label>
                            <select name="design_status" id="design_status" class="form-select form-select-sm">
                                <option value="">All Design Statuses</option>
                                <option value="In Progress"
                                    {{ request('design_status') == 'In Progress' ? 'selected' : '' }}>In Progress
                                </option>
                                <option value="Completed"
                                    {{ request('design_status') == 'Completed' ? 'selected' : '' }}>
                                    Completed</option>
                                {{-- Add more as needed --}}
                            </select>
                        </div>

                        {{-- Developer Filter --}}
                        <div class="col-md-3">
                            <label for="developer_id"
                                class="text-sm form-label text-uppercase fw-bold text-muted">Developer</label>
                            <select name="developer_id" id="developer_id" class="form-select form-select-sm">
                                <option value="">All Developers</option>
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}"
                                        {{ request('developer_id') == $developer->id ? 'selected' : '' }}>
                                        {{ $developer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ready for Go Live Checkbox --}}
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="ready_for_go_live" id="ready_for_go_live" value="1"
                                    {{ request('ready_for_go_live') ? 'checked' : '' }}
                                    class="form-check-input text-success">
                                <label class="text-sm text-gray-700 form-check-label" for="ready_for_go_live">
                                    Ready for Go Live
                                </label>
                            </div>
                        </div>

                        {{-- Overdue Checkbox --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" name="overdue" id="overdue" value="1"
                                    {{ request('overdue') ? 'checked' : '' }} class="form-check-input text-danger">
                                <label class="text-sm text-gray-700 form-check-label" for="overdue">
                                    Overdue
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex-wrap gap-3 mt-4 d-flex">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Apply Filters
                        </button>

                        <a href="{{ route('website-projects.index') }}" class="btn btn-outline-secondary btn-sm">
                            Reset Filters
                        </a>
                    </div>
                </form>
            </section>

            <hr class="my-4">

            {{-- PROJECT LIST --}}
            <section class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h2 class="text-xl font-semibold text-gray-900">Project List</h2>
                    <p class="text-sm text-muted">
                        Showing
                        <span
                            class="fw-bold text-dark">{{ $websiteProjects->firstItem() }}-{{ $websiteProjects->lastItem() }}</span>
                        of
                        <span class="fw-bold text-dark">{{ $websiteProjects->total() }}</span> projects
                    </p>
                </div>

                <div class="shadow-sm card border-light">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Project
                                        Name
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Platform
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">
                                        Developer
                                    </th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Design
                                        Status</th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">
                                        Development
                                        Status</th>
                                    <th scope="col" class="py-3 text-sm text-uppercase fw-bold text-muted">Target
                                        Go-Live</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($websiteProjects as $project)
                                    <tr style="cursor: pointer"
                                        onclick="console.log(window.location.href = '{{ route('website-projects.show', $project->id) }}')">
                                        <td class="align-middle">
                                            <div class="text-sm fw-medium text-dark">
                                                {{ $project->project->title ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $badgeClass = 'bg-secondary text-white'; // Default
                                                if ($project->platform === 'Wordpress') {
                                                    $badgeClass = 'bg-primary text-white';
                                                } elseif ($project->platform === 'Shopify') {
                                                    $badgeClass = 'bg-success text-white';
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $badgeClass }}">{{ $project->platform ?? 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                {{ $project->developer->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                {{ $project->design_status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                {{ $project->development_status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-sm text-dark">
                                                {{ $project->target_go_live_date
                                                    ? \Carbon\Carbon::parse($project->target_go_live_date)->format('M d, Y')
                                                    : 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-muted">
                                            No website projects found matching the criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="border-0 card-footer bg-light">
                        {{ $websiteProjects->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
