<x-app-layout>
    <x-slot name="header">
        {{ __('Projects') }}
    </x-slot>
    <div class="card basic-data-table">
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
            @endif
        </div>
        <style>
            th {
                cursor: grab;
                background: #f8f9fa;
            }
        </style>

        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="project-data-table">
                <thead>
                    <tr>
                        <th data-key="p-id">#</th>
                        <th data-key="Project">Project</th>
                        <th data-key="Service">Service</th>
                        <th data-key="Status">Status</th>
                        <th data-key="Account_Manager">Account Manager</th>
                        <th data-key="Contract_End_Date">Contract End Date</th>
                        <th data-key="Monthly_Revenue">Monthly Revenue (w/o VAT)</th>
                        <th data-key="Tech">Tech</th>
                        <th data-key="Created_By">Created By</th>
                        <th data-key="sales_manager">Sales Manager</th>
                        <th data-key="Pending_Tasks">Pending Tasks</th>
                        <th data-key="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        @php
                            $contractEndDate = Carbon\Carbon::parse($project->contract_start_date)->addMonths(
                                $project->contract_term,
                            );
                            $isExpiringSoon =
                                $contractEndDate->isBefore(Carbon\Carbon::now()->addMonths(2)) &&
                                $contractEndDate->isAfter(Carbon\Carbon::now());
                        @endphp
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a> 
                                @if ($isExpiringSoon)
                                    <span class="text-white bg-danger-600 fs-7 border border-danger-600 radius-4 px-8 py-4 text-sm line-height-1 fw-medium">Expiring soon!</span>
                                @endif
                            </td>
                            <td>{{ $project->project_type }}</td>
                            <td>
                                @if ($userPermissions->contains('can update project status'))
                                    @if (auth()->user()->hasRole('ADMIN') || ($project->createdBy && $project->createdBy->id == auth()->user()->id))
                                        <select class="form-control project-status"
                                            data-project-id="{{ $project->id }}" required>
                                            <option selected disabled value="">Select status</option>
                                            <option value="Current"
                                                {{ $project->status == 'Current' ? 'selected' : '' }}>
                                                Current
                                            </option>
                                            <option value="Paused"
                                                {{ $project->status == 'Paused' ? 'selected' : '' }}>
                                                Paused
                                            </option>
                                            <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>
                                                WFF
                                            </option>
                                            <option value="Expired"
                                                {{ $project->status == 'Expired' ? 'selected' : '' }}>
                                                Expired
                                            </option>
                                            <option value="Debt Collection"
                                                {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt
                                                Collection
                                            </option>
                                            <option value="Cancelled"
                                                {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>
                                            <option value="Completed"
                                                {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                        </select>
                                    @else
                                        {{ $project->status }}
                                    @endif
                                @else
                                    {{ $project->status }}
                                @endif
                            </td>
                            <td>{{ $project->accountManagers->pluck('name')->first() ?? '' }}</td>
                            <td>{{ Carbon\Carbon::parse($project->contract_start_date)->addMonths($project->contract_term)->format('Y-m-d') ?? '' }}
                            </td>
                            <td>{{ $project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? '' }}
                            </td>
                            <td>{{ $project->techManagers->pluck('name')->first() ?? '' }}</td>
                            <td>
                                {{ $project->createdBy->name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $project->salesManagers->first()->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span
                                    class="badge text-sm fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border border-danger-600 text-danger-600 radius-4 text-white">
                                    {{ $project->tasks->where('status', 'Todo')->count() ?? 0 }}
                                </span>
                            </td>
                            <td class="">
                                <div class="d-flex align-items-center gap-10">
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>

                                    @if ($userPermissions->contains('can edit projects'))
                                        @if (auth()->user()->hasRole('ADMIN') || ($project->createdBy && $project->createdBy->id == auth()->user()->id))
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
