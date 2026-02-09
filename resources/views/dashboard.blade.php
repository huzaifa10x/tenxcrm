<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    <x-dashboards.punch-in-out />
    @if ($role === 'ADMIN' || $role === 'SEO ADMIN')
        <div class="row mb-4 gy-3">
            <!-- Total SEO Revenue -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="bi bi-cash-stack fs-3 text-dark"></i>
                            </div>
                            <div>
                                <span class="mb-0 fs-6 text-dark fw-bold">Total SEO Revenue</h6>
                                <small class="opacity-75">All Active SEO Campaigns</small>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-2 text-success mb-0">
                            AED {{ number_format($totalSeoRevenue, 2) }}
                        </h2>
                        <small class="opacity-75">Monthly Recurring</small>
                    </div>
                </div>
            </div>

            <!-- Current SEO Clients Revenue -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient"
                    style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="bi bi-check-circle fs text-dark-3"></i>
                            </div>
                            <div>
                                <span class="mb-0 fs-6 text-dark fw-bold">Current SEO Revenue</h6>
                                <small class="opacity-75">Active Campaigns Only</small>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-2 text-success mb-0">
                            AED {{ number_format($currentSeoRevenue, 2) }}
                        </h2>
                        <small class="opacity-75">{{ $currentSeoProjectsCount }} Active Projects</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient"
                    style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="bi bi-check-circle fs text-dark-3"></i>
                            </div>
                            <div>
                                <span class="mb-0 fs-6 text-dark fw-bold">Monthly SEO Revenue</h6>
                                <small class="opacity-75">Average Revenue Per Day</small>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-2 text-success mb-0">
                            AED {{ number_format($monthlySeoRevenue, 2) }}
                        </h2>
                        <small class="opacity-75">{{ $currentSeoProjectsCount }} Active Projects</small>
                    </div>
                </div>
            </div>

            <!-- WFF Projects Revenue -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient"
                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="bi bi-hourglass-split text-dark fs-3"></i>
                            </div>
                            <div>
                                <span class="mb-0 fs-6 text-dark fw-bold">WFF Revenue</h6>
                                <small class="opacity-75">Waiting For Funds</small>
                            </div>
                        </div>
                        <h2 class="fw-bold fs-2 text-success mb-0">
                            AED {{ number_format($wffRevenue, 2) }}
                        </h2>
                        <small class="opacity-75">{{ $wffProjectsCount }} WFF Projects</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light">
                        <i class="bi bi-search me-2"></i>SEO Projects Management
                    </h5>
                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#seoProjectsSearch">
                        <i class="bi bi-funnel"></i> Toggle Filters
                    </button>
                </div>
            </div>
            <div class="collapse show" id="seoProjectsSearch">
                <div class="card-body">
                    <form id="seoProjectFilterForm">
                        <div class="row g-3 mb-3">
                            <!-- Global Search -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-search me-1"></i>Global Search
                                </label>
                                <input type="text" class="form-control" id="globalSearch" name="search"
                                    placeholder="Search by project name, client, keywords...">
                            </div>

                            <!-- Account Manager Filter -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge me-1"></i>Account Manager
                                </label>
                                <select class="form-select" id="amFilter" name="account_manager_id">
                                    <option value="">All Account Managers</option>
                                    @foreach ($accountManagers as $am)
                                        <option value="{{ $am->id }}">{{ $am->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-flag me-1"></i>Project Status
                                </label>
                                <select class="form-select" id="statusFilter" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="Current">Current</option>
                                    <option value="WFF">WFF</option>
                                    <option value="Debt Collection">Debt Collection</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>

                            <!-- Project Type Filter -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-tag me-1"></i>Project Type
                                </label>
                                <select class="form-select" id="projectTypeFilter" name="project_type">
                                    <option value="">All Types</option>
                                    <option value="SEO">SEO</option>
                                    <option value="Google Ads">Google Ads</option>
                                    <option value="Social Media Marketing">Social Media Marketing</option>
                                    <option value="Content Marketing">Content Marketing</option>
                                </select>
                            </div>

                            <!-- Start Date Filter -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1"></i>Contract Start From
                                </label>
                                <input type="date" class="form-control" id="startDateFrom" name="start_date_from">
                            </div>

                            <!-- End Date Filter -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check me-1"></i>Contract Start To
                                </label>
                                <input type="date" class="form-control" id="startDateTo" name="start_date_to">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill me-1"></i>Apply Filters
                            </button>
                            <button type="button" class="btn btn-secondary" id="resetFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                            <button type="button" class="btn btn-success" id="exportProjects">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export to Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- SEO Projects Results Table -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light">
                        <i class="bi bi-graph-up me-2 text-info"></i>SEO Projects
                    </h5>
                    <span class="badge bg-secondary rounded-pill" id="projectCount">
                        Total: <span id="totalProjects">{{ $seoProjects->count() }}</span>
                    </span>
                </div>
            </div>

            <div class="bg-light border-bottom p-3">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-3">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Total Contract Value
                                </small>
                                <h5 class="mb-0 fw-bold">
                                    <span class="text-muted fs-6 fw-normal">AED</span>
                                    <span
                                        id="totalRevenue">{{ number_format($seoProjects->sum('contract_value'), 2) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                            <div class="card-body p-3">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Monthly Recurring (MRR)
                                </small>
                                <h5 class="mb-0 text-success fw-bold">
                                    <span class="fs-6 fw-normal">AED</span>
                                    <span
                                        id="monthlyRevenue">{{ number_format($seoProjects->sum(fn($p) => $p->contract_value / ($p->contract_term ?: 1)), 2) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px;">
                    <table class="table table-hover align-middle mb-0" id="seoProjectsTable">
                        <thead class="table-dark small">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Project Details</th>
                                <th>Client Info</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Assignees</th>
                                <th>Revenue</th>
                                <th>Timeline</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="projectsTableBody">
                            @forelse($seoProjects as $index => $project)
                                <tr data-project-id="{{ $project->id }}">
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $project->title }}</div>
                                        @if ($project->website_url)
                                            <small class="text-muted">
                                                <i class="bi bi-link-45deg"></i>
                                                <a href="{{ $project->website_url }}" target="_blank"
                                                    class="text-decoration-none">
                                                    {{ Str::limit($project->website_url, 30) }}
                                                </a>
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $project->companies->name ?? 'N/A' }}</div>
                                        @if ($project->companies->name ?? false)
                                            <small
                                                class="text-muted">{{ $project->companies->details->first()->email ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Current' => 'success',
                                                'WFF' => 'warning',
                                                'Debt Collection' => 'danger',
                                                'Completed' => 'info',
                                                'Cancelled' => 'secondary',
                                                'Expired' => 'dark',
                                            ];
                                            $color = $statusColors[$project->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $project->status }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $project->project_type }}</span>
                                    </td>
                                    <td>
                                        @if ($project->accountManagers->isNotEmpty())
                                            @foreach ($project->accountManagers as $am)
                                                <div class="mb-1">
                                                    <i class="bi bi-person-circle me-1"></i>{{ $am->name }}
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            AED
                                            {{ number_format($project->contract_value / ($project->contract_term ?: 1), 2) }}
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            Total: AED {{ number_format($project->contract_value, 2) }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($project->contract_start_date)
                                            {{ \Carbon\Carbon::parse($project->contract_start_date)->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $project->contract_term }} months
                                            </small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('projects.show', $project->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit Project">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No SEO projects found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('seoProjectFilterForm');
                const resetBtn = document.getElementById('resetFilters');
                const exportBtn = document.getElementById('exportProjects');

                // Apply Filters with AJAX
                form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const params = new URLSearchParams(formData);

                        fetch(`{{ route('dashboard.seo-projects.filter') }}?${params.toString()}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                updateProjectsTable(data.projects);
                                updateProjectCount(data.total);
                                document.getElementById('totalRevenue').textContent = Number(data.totalRevenue)
                                    .toLocaleString(undefined, {
                                        minimumFractionDigits: 2
                                    });
                                document.getElementById('monthlyRevenue').textContent = Number(data
                                    .monthlyRevenue).toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                });
                            })
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while filtering projects');
                    });
            });

            // Reset Filters
            resetBtn.addEventListener('click', function() {
                form.reset();
                form.dispatchEvent(new Event('submit'));
            });

            // Export Projects
            exportBtn.addEventListener('click', function() {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                window.location.href = `{{ route('dashboard.seo-projects.export') }}?${params.toString()}`;
            });

            function updateProjectsTable(projects) {
                const tbody = document.getElementById('projectsTableBody');

                if (projects.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No SEO projects found
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = projects.map((project, index) => {
                    const statusColors = {
                        'Current': 'success',
                        'WFF': 'warning',
                        'Debt Collection': 'danger',
                        'Completed': 'info',
                        'Cancelled': 'secondary',
                        'Expired': 'dark'
                    };
                    const color = statusColors[project.status] || 'secondary';

                    return `
                <tr data-project-id="${project.id}">
                    <td class="ps-3">${index + 1}</td>
                    <td>
                        <div class="fw-semibold">${project.title}</div>
                        ${project.website_url ? `
                                                                                                    <small class="text-muted">
                                                                                                        <i class="bi bi-link-45deg"></i>
                                                                                                        <a href="${project.website_url}" target="_blank" class="text-decoration-none">
                                                                                                            ${project.website_url.substring(0, 30)}${project.website_url.length > 30 ? '...' : ''}
                                                                                                        </a>
                                                                                                    </small>
                                                                                                ` : ''}
                    </td>
                    <td>
                        <div>${project.client_name || 'N/A'}</div>
                        ${project.client_email ? `<small class="text-muted">${project.client_email}</small>` : ''}
                    </td>
                    <td><span class="badge bg-${color}">${project.status}</span></td>
                    <td><span class="badge bg-primary">${project.project_type}</span></td>
                    <td>
                        ${project.account_managers.length > 0 
                            ? project.account_managers.map(am => `<div class="mb-1"><i class="bi bi-person-circle me-1"></i>${am.name}</div>`).join('')
                            : '<span class="text-muted">Not Assigned</span>'
                        }
                    </td>
                    <td>
                        <strong class="text-success">AED ${Number(project.monthly_value).toFixed(2)}</strong>
                        <br>
                        <small class="text-muted">Total: AED ${Number(project.contract_value).toFixed(2)}</small>
                    </td>
                    <td>
                        ${project.contract_start_date 
                            ? `${new Date(project.contract_start_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}<br><small class="text-muted">${project.contract_term} months</small>`
                            : '<span class="text-muted">N/A</span>'
                        }
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="/projects/${project.id}" class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/projects/${project.id}/edit" class="btn btn-sm btn-outline-warning" title="Edit Project">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
                }).join('');
            }

            function updateProjectCount(total) {
                document.getElementById('totalProjects').textContent = total;
            }
            });
        </script>
        <style>
            .table thead.sticky-top {
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
            }

            .card-header h5 {
                font-weight: 600;
            }

            .form-label {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }

            .table-responsive {
                max-height: 600px;
                overflow-y: auto;
            }
        </style>
    @endif
    <div class="my-5 row gy-4">
        <!-- Yesterday -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-success text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-day fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed Yesterday</h6>
                            <small class="text-light">({{ \Carbon\Carbon::yesterday()->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedYesterday }}</h3>
                </div>
            </div>
        </div>

        <!-- Last 7 Days -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-success text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-week fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed in Last 7 Days</h6>
                            <small class="text-light">(Since
                                {{ \Carbon\Carbon::now()->subDays(7)->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedLast7Days }}</h3>
                </div>
            </div>
        </div>

        <!-- Last 30 Days -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-info text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-range fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed in Last 30 Days</h6>
                            <small class="text-light">(Since
                                {{ \Carbon\Carbon::now()->subDays(30)->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedLast30Days }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-10 row gy-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="px-20 py-20 card-header office-timing-button">
                    <h6 class="mb-0 mb-2 text-lg fw-bold">Office Timings & Attendance Rules</h6>
                </div>
                <div class="p-0 card-body office-timing-body table-responsive" style="display: none">
                    <table class="table mb-0 bordered-table">
                        <tbody>
                            <tr>
                                <td>Office Open Time</td>
                                <td>{{ $office_open_time }}</td>
                            </tr>
                            <tr>
                                <td>Office Close Time</td>
                                <td>{{ $office_close_time }}</td>
                            </tr>
                            <tr>
                                <td>Late Mark After</td>
                                <td>{{ $late_threshold }} minutes</td>
                            </tr>
                            <tr>
                                <td>Half Day If Arrived After</td>
                                <td>{{ $half_day_threshold }}</td>
                            </tr>
                            <tr>
                                <td>Break Ends At</td>
                                <td>{{ $break_end_time }}</td>
                            </tr>
                            <tr>
                                <td>Early Out If Left Before</td>
                                <td>{{ $early_out_threshold }}</td>
                            </tr>
                            <tr>
                                <td>Number of late arrivals that result in one deduction</td>
                                <td>{{ $late_deduction_threshold }}</td>
                            </tr>
                            <tr>
                                <td>Number of half-days that result in one deduction</td>
                                <td>{{ $half_day_deduction_threshold }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($role === 'AM')
        <x-dashboards.am-dashboard :totalRenewals="$totalRenewals" :monthlyStats="$monthlyStats" :monthlyData="$monthlyData" :newSellProjects="$newSellProjects"
            :newSell="$newSell" :typeCounts="$typeCounts" :statusCounts="$statusCounts" :currentPortfolio="$currentPortfolio" :projects="$projects" :pendingTasks="$pendingTasks"
            :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role === 'CONTENT')
        <x-dashboards.content-dashboard :pendingTasksCount="$pendingTasksCount" :completedTasksCount="$completedTasksCount" :pendingWords="$pendingWords" :completedWords="$completedWords"
            :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role == 'ADMIN')
        <div class="row">
            @foreach ($WebsiteProjects as $project)
                <div class="mb-10 col-lg-4 col-md-6 col-sm-12">
                    <div class="shadow-sm card h-100">
                        <div
                            class="p-3 text-white card-header bg-dark d-flex align-items-center justify-content-between">
                            <span class="mb-0 fw-bold">
                                <i class="bi bi-globe2 me-1"></i> {{ $project->project->title }}
                            </span>
                            @if (
                                !$project->websiteProject &&
                                    (auth()->user()->hasRole('ADMIN') || auth()->user()->id == $project->accountManagers->pluck('id')->first()))
                                <a href="{{ route('website-projects.create') }}?project_id={{ $project->id }}"
                                    class="btn btn-sm btn-light text-success">
                                    <i class="bi bi-plus-circle"></i> Add
                                </a>
                            @endif
                        </div>
                        <div class="p-3 card-body">
                            @php $wp = $project; @endphp

                            <div class="flex-wrap gap-2 mb-3 d-flex justify-content-between">
                                <span class="badge bg-secondary">
                                    <i
                                        class="bi bi-{{ $wp->platform === 'WordPress' ? 'wordpress' : 'code-square' }}"></i>
                                    {{ $wp->platform }}
                                </span>
                                <span class="badge bg-warning">
                                    <i class="bi bi-palette"></i> Design: {{ $wp->design_status }}
                                </span>
                                <span class="badge bg-info">
                                    <i class="bi bi-code-slash"></i> Dev: {{ $wp->development_status }}
                                </span>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center small text-muted">
                                @if ($wp->target_go_live_date)
                                    <div class="{{ $wp->isOverdue() ? 'text-danger' : 'text-info' }}">
                                        <i class="bi bi-calendar-event me-1"></i> Target:
                                        {{ $wp->target_go_live_date->format('M d, Y') }}
                                    </div>
                                @endif
                                @if ($wp->developer)
                                    <div>
                                        <i class="bi bi-person-circle me-1"></i> Dev: {{ $wp->developer->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                @if ($wp->go_live_date)
                                    <span class="px-3 py-2 badge bg-success fw-bold">
                                        <i class="bi bi-rocket-takeoff me-1"></i> LIVE
                                    </span>
                                @elseif($wp->approved_for_go_live)
                                    <span class="px-3 py-2 badge bg-warning fw-bold">
                                        <i class="bi bi-hourglass-split me-1"></i> Ready to Launch
                                    </span>
                                @else
                                    <span class="px-3 py-2 badge bg-secondary fw-bold">
                                        <i class="bi bi-clock-history me-1"></i> In Progress
                                    </span>
                                @endif

                                <a href="{{ route('website-projects.show', $wp->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-eye"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card">
            <div class="card-header">
                <span class="fw-bold">Project Cancellation Request</span>
            </div>
            <div class="card-body">
                <table class="table align-middle table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"><i class="bi bi-hash"></i> #</th>
                            <th scope="col"><i class="bi bi-folder2-open"></i> Project</th>
                            <th scope="col"><i class="bi bi-chat-left-text"></i> Reason</th>
                            <th scope="col"><i class="bi bi-tools"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingCancellations as $index => $cancel)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cancel->project->title }}</td>
                                <td>
                                    <button class="btn btn-outline-info btn-sm view-reason-btn" data-bs-toggle="modal"
                                        data-bs-target="#reasonModal" data-project="{{ $cancel->project->title }}"
                                        data-url="{{ route('projects.show', $cancel->project->id) }}"
                                        data-reason="{{ $cancel->reason }}">
                                        <i class="bi bi-eye"></i> View Reason
                                    </button>
                                </td>
                                <td>
                                    <form action="{{ route('admin.approveCancellation', $cancel->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm">
                                            <i class="bi bi-check2-circle"></i> Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No pending cancellations.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Reason Modal -->
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="shadow modal-content">
                    <div class="text-white modal-header bg-info">
                        <span class="modal-title" id="reasonModalLabel">
                            <i class="bi bi-exclamation-circle me-1"></i> Cancellation Reason
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <a class="fw-bold fs-7 text-dark project-title"></a>
                        <p class="mt-2 text-secondary reason-text"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-dashboards.admin-dashboard />
        <button class="mt-24 btn btn-warning show-partner-links-info">Show Partner Links Data</button>
        @foreach ($partnerLinksByAM as $amData)
            <div class="mt-4 card partner-links-info" style="display: none">
                <div class="text-white card-header bg-success">
                    <i class="bi bi-person-badge me-1"></i> {{ $amData['am_name'] }}
                </div>
                <div class="p-0 card-body">
                    <table class="table mb-0 table-bordered table-striped small">
                        <thead class="text-center table-light">
                            <tr>
                                <th>Month</th>
                                <th>Total Links</th>
                                <th>Total Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($amData['data'] as $row)
                                <tr>
                                    <td>{{ $row['month'] }}</td>
                                    <td>{{ $row['links'] }}</td>
                                    <td>{{ number_format($row['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="mt-6 card">
            <div class="text-white card-header bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light">
                        <i class="bi bi-pencil-square me-2"></i>Content Writers Performance
                    </h5>
                    <a href="{{ route('admin.content-writers.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-bar-chart-line me-1"></i>View All Writers
                    </a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $topWriters = \App\Models\User::whereHas('roles', function ($query) {
                        $query->where('name', 'CONTENT');
                    })
                        ->withCount([
                            'assignedTasks as completed_this_month' => function ($query) {
                                $query
                                    ->where('status', 'Completed')
                                    ->whereMonth('completed_date', now()->month)
                                    ->whereYear('completed_date', now()->year);
                            },
                        ])
                        ->orderBy('completed_this_month', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Writer</th>
                                <th>Tasks This Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topWriters as $writer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="text-white avatar-sm bg-success rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($writer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $writer->name }}</div>
                                                <small class="text-muted">{{ $writer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $writer->completed_this_month }}
                                            completed</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.content-writers.show', $writer->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <style>
            .avatar-sm {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        </style>
    @endif
    <div class="mt-10 mb-10 row gy-3">
        <x-dashboards.task-counts :taskPercentages="$taskPercentages" :pendingTasksCountGlobal="$pendingTasksCountGlobal" :completedTasksCountGlobal="$completedTasksCountGlobal" :createdTasksCountGlobal="$createdTasksCountGlobal"
            :collaboratorTasksCountGlobal="$collaboratorTasksCountGlobal" />
        <x-dashboards.latest-notifications :latestNotifications="$latestNotifications" />
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Tasks',
                    data: {!! json_encode(array_values($taskData->toArray())) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_keys($taskData->toArray())) !!}
                },
                colors: ['#008FFB'],
                stroke: {
                    curve: 'smooth'
                }
            };

            var chart = new ApexCharts(document.querySelector("#tasksChart"), options);
            chart.render();
        });
    </script>
    <script>
        $(document).on('click', '.view-reason-btn', function() {
            let reason = $(this).data('reason');
            let project = $(this).data('project');
            let projectUrl = $(this).data('url');
            $('.project-title').attr('href', projectUrl);
            $('.project-title').text(project);
            $('.reason-text').text(reason);
        });
        $(document).on('click', '.office-timing-button', function() {
            $('.office-timing-body').slideToggle();
        });
        $(document).on('click', '.show-partner-links-info', function() {
            $('.partner-links-info').slideToggle();
        });
    </script>

</x-app-layout>
