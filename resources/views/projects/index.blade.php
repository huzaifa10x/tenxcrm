<x-app-layout>
    <x-slot name="header">
        {{ __('Projects') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can add projects'))
            <div class="card-header">
                <a href="{{ route('projects.create') }}" class="btn btn-outline-primary">
                    Add New Project
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Project Type</th>
                        <th>Project Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->category }}</td>
                            <td>{{ $project->status }}</td>
                            <td>{{ $project->project_type }}</td>
                            <td>
                                <select class="form-control" id="status" name="status" required>
                                    <option selected disabled value="">Select status</option>
                                    <option value="Current" {{ $project->status == 'Current' ? 'selected' : '' }}>
                                        Current
                                    </option>
                                    <option value="Paused" {{ $project->status == 'Paused' ? 'selected' : '' }}>Paused
                                    </option>
                                    <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>WFF
                                    </option>
                                    <option value="Expired" {{ $project->status == 'Expired' ? 'selected' : '' }}>
                                        Expired
                                    </option>
                                    <option value="Debt Collection"
                                        {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt Collection
                                    </option>
                                    <option value="Cancelled" {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </td>
                            <td class="">
                                <div class="d-flex align-items-center gap-10">
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    @if ($userPermissions->contains('can edit projects'))
                                        <a href="{{ route('projects.edit', $project->id) }}"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
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

</x-app-layout>
