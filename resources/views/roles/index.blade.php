<x-app-layout>
    <x-slot name="header">
        {{ __('Roles') }}
    </x-slot>
    <div class="card basic-data-table">
        <div class="card-header">
            <a href="{{ route('roles.create') }}" class="btn btn-outline-primary">
                Add New Role
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td> {{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 overflow-auto" style="max-width: 300px">
                                    @foreach ($role->permissions as $permission)
                                        <div class="badge fw-light text-dark bg-warning border p-1">
                                            {{ $permission->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                        class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>
