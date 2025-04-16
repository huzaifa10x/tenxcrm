<x-app-layout>
    <x-slot name="header">
        {{ __('Projects') }}
    </x-slot>
    <div class="card basic-data-table container-fluid">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-start gap-10">
                @if ($userPermissions->contains('can add projects'))
                    <a href="{{ route('projects.create') }}" class="btn btn-neutral-900">
                        Add New Project
                    </a>
                @endif
                <div>
                    <input type="text" id="searchProject" class="form-control" placeholder="Search Project..."
                        autocomplete="off">
                    <ul id="searchResults" class="dropdown-menu show" style="display: none;"></ul>
                </div>
                @if (auth()->user()->hasRole('ADMIN'))
                    <form method="GET" action="{{ route('projects.index') }}">
                        {{-- Preserve other query params --}}
                        @if (request('sales_manager_id'))
                            <input type="hidden" name="sales_manager_id" value="{{ request('sales_manager_id') }}">
                        @endif
                        <select class="form-control w-auto" name="account_manager_id" onchange="this.form.submit()">
                            <option value="">-- Filter by AM --</option>
                            @foreach ($accountManagers as $am)
                                <option value="{{ $am->id }}"
                                    {{ request('account_manager_id') == $am->id ? 'selected' : '' }}>
                                    {{ $am->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <form method="GET" action="{{ route('projects.index') }}">
                        {{-- Preserve other query params --}}
                        @if (request('sales_manager_id'))
                            <input type="hidden" name="sales_manager_id" value="{{ request('sales_manager_id') }}">
                        @endif
                        <input type="hidden" name="account_manager_id" value="{{ auth()->user()->id }}">
                        <button class="btn btn-info-700 btn-sm" type="submit">Projects I Manage</button>
                    </form>
                @endif
                <form method="GET" action="{{ route('projects.index') }}">
                    {{-- Preserve other query params --}}
                    @if (request('account_manager_id'))
                        <input type="hidden" name="account_manager_id" value="{{ request('account_manager_id') }}">
                    @endif
                    <select class="form-control w-auto" name="sales_manager_id" onchange="this.form.submit()">
                        <option value="">-- Filter by Sales Manager --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ request('sales_manager_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <form method="GET" action="{{ route('projects.index') }}">
                    <input type="hidden" name="sales_manager_id" value="">
                    <input type="hidden" name="account_manager_id" value="">
                    <button class="btn btn-danger-500 btn-sm" type="submit">Reset</button>
                </form>
            </div>
            @if (request('account_manager_id'))
                <div class="row mt-10">
                    <div class="row row-cols-lg-5 mt-2 gy-4">
                        @foreach ($statusCounts as $status => $count)
                            <div class="col mt-1">
                                <div class="card shadow-none border bg-gradient-start-1 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <div>
                                                <p class="fw-medium text-primary-light mb-1">{{ $status }}</p>
                                                <h6 class="mb-0">{{ $count }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- card end -->
                            </div>
                        @endforeach
                    </div>
                    <div class="row row-cols-lg-5 mt-2 gy-4">
                        @foreach ($typeCounts as $type => $count)
                            <div class="col mt-1">
                                <div class="card shadow-none border bg-gradient-start-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <div>
                                                <p class="fw-medium text-primary-light mb-1">{{ $type }}</p>
                                                <h6 class="mb-0">{{ $count }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- card end -->
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row gy-3 my-10">
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
                                            <span class="fw-medium text-secondary-light text-md">Current Portfolio /
                                                MO</span>
                                            <h6 class="fw-semibold mt-2">{{ number_format($currentPortfolio, 2) }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                                    <span
                                        class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                        <i class="ri-arrow-right-up-line"></i> 00
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
                                            <span class="fw-medium text-secondary-light text-md">New / Renew /
                                                UpSell</span>
                                            <h6 class="fw-semibold mt-2">{{ number_format($newSell, 2) }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                                    <span
                                        class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                        <i class="ri-arrow-right-up-line"></i>00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <style>
            th {
                cursor: grab;
                background: #f8f9fa;
            }
        </style>

        <div class="row gy-4">
            @foreach ($projects as $project)
                @php
                    $contractEndDate = Carbon\Carbon::parse($project->contract_start_date)->addMonths(
                        $project->contract_term,
                    );
                    $isExpiringSoon =
                        $contractEndDate->isBefore(now()->addMonths(2)) && $contractEndDate->isAfter(now());
                    $pendingTasks = $project->tasks->where('status', 'Todo')->count();
                @endphp

                <div class="col-lg-4 p-3">
                    <div class="card h-100 radius-12 text-center">
                        <div class="card-body p-24">
                            <div
                                class="d-inline-flex align-items-center justify-content-center bg-gradient-danger text-danger-600 mb-16 p-1 radius-12">
                                <img src="{{ asset('/public/storage/') }}/{{ $project->companies->logo ?? '' }}"
                                    alt="" class="img-fluid rounded object-fit-cover rounded-end-4"
                                    style="max-width: 50px;">
                            </div>

                            <h6 class="mb-8 fw-semibold">
                                <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none">
                                    {{ $project->title }}
                                </a>
                                @if ($isExpiringSoon)
                                    <span class="badge bg-danger text-white fs-8 ms-1">Expiring Soon</span>
                                @endif
                            </h6>

                            <hr class="mb-1">

                            <p class="card-text mb-8 text-secondary-light small">
                                <strong>#{{ $project->id }}</strong> • {{ $project->project_type }}<br>
                                <strong>Service:</strong> {{ $project->project_type }}<br>
                                <strong>Account Manager:</strong>
                                {{ $project->accountManagers->pluck('name')->first() ?? 'N/A' }}<br>
                                @if (auth()->user()->id == $project->accountManagers->pluck('id')->first() ||
                                        auth()->user()->hasRole('ADMIN') ||
                                        ($project->createdBy && auth()->user()->id == $project->createdBy->id))
                                    <strong>Status:</strong> {{ $project->status }}<br>
                                    <strong>End Date:</strong> {{ $contractEndDate->format('Y-m-d') }}<br>
                                    <strong>Monthly Revenue:</strong>
                                    {{ $project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 }}<br>
                                    <strong>Tech:</strong>
                                    {{ $project->techManagers->pluck('name')->first() ?? 'N/A' }}<br>
                                    <strong>Created By:</strong> {{ optional($project->createdBy)->name ?? 'N/A' }}<br>
                                    <strong>Sales Manager:</strong>
                                    {{ $project->salesManagers->pluck('name')->first() ?? 'N/A' }}<br>
                                    <strong>Pending Tasks:</strong>
                                    <span class="badge text-danger-600 border border-danger-600 radius-4 px-6 py-2">
                                        {{ $pendingTasks }}
                                    </span>
                                @endif
                            </p>

                            <div class="d-flex justify-content-center align-items-center gap-2 mt-3 flex-wrap">
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="btn text-danger-600 hover-text-danger px-0 py-10 d-inline-flex align-items-center gap-2">
                                    View <iconify-icon icon="iconamoon:eye-light" class="text-xl"></iconify-icon>
                                </a>

                                @if (
                                    $userPermissions->contains('can edit projects') &&
                                        (auth()->user()->hasRole('ADMIN') || optional($project->createdBy)->id == auth()->user()->id))
                                    <a href="{{ route('projects.edit', $project->id) }}"
                                        class="btn text-success-600 hover-text-success px-0 py-10 d-inline-flex align-items-center gap-2">
                                        Edit <iconify-icon icon="lucide:edit" class="text-xl"></iconify-icon>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $projects->links('vendor.pagination.custom') }}
        </div>

    </div>
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
                                toastr.success("Project status updated!", "Success");
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.dragtable@2.0.15/jquery.dragtable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.dragtable@2.0.15/dragtable.min.css">
    <script>
        $(document).ready(function() {
            let table = $('#project-data-table');
            let storageKey = 'tableColumnOrder';

            function saveColumnOrder(sortOrder) {
                localStorage.setItem(storageKey, JSON.stringify(sortOrder));
            }

            function loadColumnOrder() {
                let savedOrder = localStorage.getItem(storageKey);
                if (!savedOrder) return;
                savedOrder = JSON.parse(savedOrder);
                let headerRow = table.find('thead tr');
                let bodyRows = table.find('tbody tr');
                let newHeader = [];
                let headerMap = {};
                let headers = headerRow.find('th');
                headers.each(function(index) {
                    let key = $(this).attr('data-key');
                    if (key) headerMap[key] = index;
                });
                savedOrder.forEach((key) => {
                    let th = headers.filter(`[data-key="${key}"]`);
                    if (th.length) newHeader.push(th.detach());
                });
                headerRow.append(newHeader);
                bodyRows.each(function() {
                    let row = $(this);
                    let newCells = new Array(savedOrder.length); // Initialize array to maintain order
                    row.children().each(function(index) {
                        let key = headers.eq(index).attr(
                            'data-key'); // Find key based on original order
                        let newIndex = savedOrder.indexOf(key); // Find where it should be placed
                        if (newIndex !== -1) {
                            newCells[newIndex] = $(this).detach(); // Place at correct index
                        }
                    });
                    row.append(newCells);
                });
            }
            table.dragtable({
                persistState: function(table) {
                    let sortOrder = {};
                    table.el.find('th').each(function(i) {
                        if (this.dataset.key) { // Use 'data-key' instead of 'id'
                            sortOrder[this.dataset.key] = i;
                        }
                    });
                    saveColumnOrder(Object.keys(sortOrder)); // Save order in localStorage
                }
            });
            loadColumnOrder();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#searchProject').on('input', function() {
                let query = $(this).val();

                if (query.length < 2) {
                    $('#searchResults').hide();
                    return;
                }

                $.ajax({
                    url: "{{ route('projects.search') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(response) {
                        let dropdown = $('#searchResults');
                        dropdown.empty();

                        if (response.length > 0) {
                            response.forEach(function(project) {
                                dropdown.append(`
                                <li class="dropdown-item">
                                    <a href="{{ url('/projects/${project.id}') }}" class="text-dark d-block">
                                        ${project.title}
                                    </a>
                                </li>
                            `);
                            });
                            dropdown.show();
                        } else {
                            dropdown.hide();
                        }
                    }
                });
            });

            $(document).on('click', '#searchResults li', function() {
                window.location.href = $(this).find('a').attr('href');
            });
        });
    </script>

</x-app-layout>
