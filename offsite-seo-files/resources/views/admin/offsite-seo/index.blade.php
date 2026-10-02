@php
    $pageUrl = $member ? route('admin.offsite-seo.show', $member->id) : route('admin.offsite-seo.index');
    $linksUrl = route('admin.offsite-seo.links.index', $query + array_filter(['user_id' => $member->id ?? $selectedUser]));
    $ratedRel = $quality['dofollow'] + $quality['nofollow'];
    $cards = [
        ['Total Tasks', $stats['total'], 'Assigned in this period', '#0d6efd'],
        ['Completed', $stats['completed'], 'Completed in this period', '#198754'],
        ['Pending', $stats['pending'], 'Still open', '#ffc107'],
        ['Overdue', $stats['overdue'], 'Open and past due date', '#dc3545'],
        ['Links Created', $stats['links'], $stats['links_per_day'] . ' per working day', '#6f42c1'],
        ['Manual Links', $stats['manual'], 'From Manual Links tasks', '#20c997'],
    ];
@endphp
<x-app-layout>
    <x-slot name="header">
        {{ __($member ? $member->name . ' — Offsite SEO Performance' : 'Offsite SEO Performance') }}
    </x-slot>

    <style>
        .os-stat { border: 0; border-left: 4px solid var(--os-color, #0d6efd); }
        .os-stat .os-value { font-size: 1.75rem; font-weight: 700; line-height: 1.1; }
        .os-stat .os-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; color: #6c757d; }
        .os-stat .os-sub { font-size: .75rem; color: #6c757d; }
    </style>

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            @if ($member)
                <a href="{{ route('admin.offsite-seo.index', $query) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>All employees</a>
            @endif
        </div>
        <a href="{{ $linksUrl }}" class="btn btn-sm btn-success"><i class="bi bi-link-45deg me-1"></i>Manage links</a>
    </div>

    {{-- Filters --}}
    @component('admin.offsite-seo._filters', ['action' => $pageUrl, 'range' => $range, 'ranges' => $ranges, 'from' => $from, 'to' => $to])
        @unless ($member)
            <div class="col-md-3 col-6">
                <label class="form-label fw-semibold">Employee</label>
                <select name="user_id" class="form-select">
                    <option value="">All Offsite SEO employees</option>
                    @foreach ($team as $user)
                        <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        @endunless
    @endcomponent

    @if ($team->isEmpty())
        <div class="alert alert-warning">No users have the OFFSITE role yet. Assign the role under Users to see them here.</div>
    @endif

    {{-- Summary cards --}}
    <div class="mb-4 row g-3">
        @foreach ($cards as [$label, $value, $sub, $color])
            <div class="col-6 col-md-4 col-xl-2">
                <div class="shadow-sm card h-100 os-stat" style="--os-color: {{ $color }}">
                    <div class="card-body">
                        <div class="os-label">{{ $label }}</div>
                        <div class="os-value">{{ number_format($value) }}</div>
                        <div class="os-sub">{{ $sub }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mb-4 row g-3">
        {{-- Productivity chart --}}
        <div class="col-lg-8">
            <div class="shadow-sm card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>{{ $group === 'month' ? 'Monthly' : 'Daily' }} Productivity</h6>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ request()->fullUrlWithQuery(['group' => 'day']) }}"
                            class="btn {{ $group === 'day' ? 'btn-primary' : 'btn-outline-primary' }}">Daily</a>
                        <a href="{{ request()->fullUrlWithQuery(['group' => 'month']) }}"
                            class="btn {{ $group === 'month' ? 'btn-primary' : 'btn-outline-primary' }}">Monthly</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="productivityChart"></div>
                </div>
            </div>
        </div>

        {{-- Link quality --}}
        <div class="col-lg-4">
            <div class="shadow-sm card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-award me-2"></i>Link Quality</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <tbody>
                            <tr>
                                <td>Unique referring domains</td>
                                <td class="text-end fw-bold">{{ $quality['domains'] }} <small class="text-muted fw-normal">of {{ $stats['links'] }} links</small></td>
                            </tr>
                            <tr>
                                <td>Duplicate links</td>
                                <td class="text-end fw-bold {{ $quality['duplicates'] ? 'text-danger' : '' }}">{{ $quality['duplicates'] }}</td>
                            </tr>
                            <tr>
                                <td>Dofollow</td>
                                <td class="text-end fw-bold">
                                    @if ($ratedRel)
                                        {{ round($quality['dofollow'] / $ratedRel * 100) }}% <small class="text-muted fw-normal">({{ $quality['dofollow'] }} of {{ $ratedRel }})</small>
                                    @else
                                        <span class="text-muted fw-normal">Not recorded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Average DR</td>
                                <td class="text-end fw-bold">
                                    @if ($quality['avg_dr'] !== null)
                                        {{ $quality['avg_dr'] }} <small class="text-muted fw-normal">({{ $quality['dr_count'] }} links)</small>
                                    @else
                                        <span class="text-muted fw-normal">Not recorded</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Live / Lost</td>
                                <td class="text-end fw-bold">
                                    <span class="text-success">{{ $quality['live'] }}</span> /
                                    <span class="{{ $quality['lost'] ? 'text-danger' : '' }}">{{ $quality['lost'] }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Dofollow, DR and status come from what is entered with each link.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Employees (team page) --}}
    @unless ($member)
        <div class="mb-4 shadow-sm card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-people-fill me-2"></i>Employees</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th class="text-center">Tasks</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Overdue</th>
                            <th class="text-center">Links</th>
                            <th class="text-center">Manual</th>
                            <th class="text-center">Links / day</th>
                            <th class="text-center">Unique domains</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $employee['name'] }}</div>
                                    <small class="text-muted">{{ $employee['email'] }}</small>
                                </td>
                                <td class="text-center">{{ $employee['total'] }}</td>
                                <td class="text-center text-success fw-semibold">{{ $employee['completed'] }}</td>
                                <td class="text-center">{{ $employee['pending'] }}</td>
                                <td class="text-center {{ $employee['overdue'] ? 'text-danger fw-semibold' : '' }}">{{ $employee['overdue'] }}</td>
                                <td class="text-center fw-semibold">{{ $employee['links'] }}</td>
                                <td class="text-center">{{ $employee['manual'] }}</td>
                                <td class="text-center">{{ $employee['links_per_day'] }}</td>
                                <td class="text-center">{{ $employee['domains'] }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.offsite-seo.show', ['user' => $employee['id']] + $query) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-4 text-center text-muted">No Offsite SEO employees found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endunless

    {{-- Overdue tasks --}}
    <div class="mb-4 shadow-sm card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Overdue Tasks
                @if ($stats['overdue'] > $overdue->count())
                    <small class="text-muted">(top {{ $overdue->count() }} of {{ $stats['overdue'] }})</small>
                @endif
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Task</th>
                        <th>Project</th>
                        @unless ($member)
                            <th>Employee</th>
                        @endunless
                        <th>Status</th>
                        <th>Due date</th>
                        <th class="text-end">Days overdue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($overdue as $task)
                        <tr>
                            <td><a href="{{ route('tasks.show', $task->id) }}" class="text-decoration-none">#{{ $task->id }} {{ Str::limit($task->title, 60) }}</a></td>
                            <td>{{ $task->project->title ?? '—' }}</td>
                            @unless ($member)
                                <td>{{ $task->assignee_name }}</td>
                            @endunless
                            <td><span class="badge bg-secondary">{{ $task->status }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                            <td class="text-end text-danger fw-bold">{{ $task->days_overdue }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-muted">No overdue tasks.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent links (employee page) --}}
    @if ($member)
        <div class="mb-4 shadow-sm card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Latest Links in This Period</h6>
                <a href="{{ $linksUrl }}" class="btn btn-sm btn-outline-primary">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th>Keyword</th>
                            <th>Link URL</th>
                            <th>DR</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentLinks as $link)
                            <tr>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($link->created_at)->format('d M Y') }}</td>
                                <td>{{ $link->project ?? '—' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $link->link_type }}</span></td>
                                <td>{{ $link->keyword }}</td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener">{{ Str::limit($link->url, 50) }}</a>
                                    @if ($link->is_duplicate)
                                        <span class="badge bg-danger">Duplicate</span>
                                    @endif
                                </td>
                                <td>{{ $link->dr ?? '—' }}</td>
                                <td>{{ $link->link_status ? ucfirst($link->link_status) : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-muted">No links logged in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const series = @json($series);
            const keys = Object.keys(series);

            new ApexCharts(document.querySelector('#productivityChart'), {
                chart: { height: 320, type: 'line', toolbar: { show: false } },
                series: [
                    { name: 'Links created', type: 'column', data: keys.map(k => series[k].links) },
                    { name: 'Manual links', type: 'column', data: keys.map(k => series[k].manual) },
                    { name: 'Tasks completed', type: 'line', data: keys.map(k => series[k].completed) }
                ],
                xaxis: { categories: keys, tickAmount: Math.min(keys.length, 15), labels: { rotate: -45, style: { fontSize: '10px' } } },
                yaxis: { min: 0, forceNiceScale: true, labels: { formatter: v => Math.round(v) } },
                colors: ['#6f42c1', '#20c997', '#198754'],
                stroke: { width: [0, 0, 3], curve: 'smooth' },
                plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top' },
                noData: { text: 'No activity in this period' }
            }).render();
        });
    </script>
</x-app-layout>
