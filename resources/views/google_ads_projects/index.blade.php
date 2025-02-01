<x-app-layout>
    <x-slot name="header">
        {{ __('Google Ads Projects') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage google-ads-projects'))
            <div class="card-header">
                <a href="{{ route('google-ads-projects.create') }}" class="btn btn-outline-primary">
                    Add New Project
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Sales Manager</th>
                        <th>Account Manager</th>
                        <th>Client</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->category }}</td>
                            <td>{{ $project->status }}</td>
                            <td>{{ $project->salesManager->name }}</td>
                            <td>{{ $project->accountManager->name }}</td>
                            <td>{{ $project->customer->name }}</td>
                            <td>
                                <a href="{{ route('google-ads-projects.show', $project->id) }}"
                                    class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                </a>
                                @if ($userPermissions->contains('can manage google-ads-projects'))
                                    <a href="{{ route('google-ads-projects.edit', $project->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    <form action="{{ route('google-ads-projects.destroy', $project->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="margin-top: -5px;"
                                            onclick="return confirm('Are you sure you want to delete this customer?')"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $projects->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>
