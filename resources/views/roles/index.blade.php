<x-app-layout>
    <x-slot name="header">
        {{ __('Roles') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage roles'))
            <div class="card-header">
                <a href="{{ route('roles.create') }}" class="btn btn-outline-primary">
                    Add New Role
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        @if ($userPermissions->contains('can manage roles'))
                            <th>Actions</th>
                        @endif
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
                            @if ($userPermissions->contains('can manage roles'))
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <button class="px-3 py-1 btn btn-sm btn-outline-warning"
                                            onclick="openPermissionsModal({{ $role->id }}, {!! json_encode($role->permissions->pluck('id')) !!})">
                                            Permissions
                                        </button>

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
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->links('vendor.pagination.custom') }}
        </div>
    </div>

    <!-- Permissions Modal -->
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Manage Role Permissions</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="permissionsForm" class="container">
                        <input type="hidden" id="role_id">
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-lg-4 form-check d-flex align-items-center g-2">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        name="permissions[]" value="{{ $permission->id }}"
                                        id="permission-{{ $permission->id }}">
                                    <label class="form-check-label fs-7" for="permission-{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Permissions</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPermissionsModal(roleId, selectedPermissions) {
            $("#role_id").val(roleId);

            $(".permission-checkbox").each(function() {
                let permissionId = $(this).val();
                $(this).prop("checked", selectedPermissions.includes(parseInt(permissionId)));
            });

            $("#permissionsModal").modal("show");
        }

        // Handle form submission
        $("#permissionsForm").submit(function(e) {
            e.preventDefault();

            let roleId = $("#role_id").val();
            let selectedPermissions = $(".permission-checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "{{ route('roles.update.permissions') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    role_id: roleId,
                    permissions: selectedPermissions
                },
                success: function(response) {
                    toastr.success("Permission Updated!", "Success");
                    $("#permissionsModal").modal("hide");
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error updating permissions!");
                }
            });
        });
    </script>

</x-app-layout>
