<x-app-layout>
    <x-slot name="header">
        {{ __('Users') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage users'))
            <div class="card-header">
                <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                    Add New User
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td> {{ $user->id }}</td>
                            <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        <img src="{{ asset('storage/') }}/{{ $user->img }}" class="rounded-pill me-1 w-40-px h-40-px img-fluid"
                                            alt="Logo" width="50"> {{ $user->name }}
                                    </label>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    @if ($userPermissions->contains('can manage users'))
                                        <button class="px-3 py-1 btn btn-sm btn-outline-warning"
                                            onclick="openRolesModal({{ $user->id }}, {!! json_encode($user->roles->pluck('id')) !!})">
                                            Roles
                                        </button>
                                    @endif

                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    @if ($userPermissions->contains('can manage users'))
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    </div>
    {{-- ROLES MANAGE MODAL --}}
    <div class="modal fade" id="manageRolesModal" tabindex="-1" aria-labelledby="manageRolesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="manageRolesLabel">Manage Roles</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateRolesForm">
                        <input type="hidden" id="userId">
                        <div class="row">
                            @foreach ($roles as $role)
                                <div class="col-lg-4 mb-3">
                                    <div class="form-check d-flex align-items-center gap-2">
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]"
                                            value="{{ $role->id }}" id="role_{{ $role->id }}">
                                        <label class="form-check-label fs-7" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Roles</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openRolesModal(userId, selectedRoles) {
            $('#userId').val(userId);

            // Uncheck all checkboxes first
            $('.role-checkbox').prop('checked', false);

            // Check roles assigned to the user
            selectedRoles.forEach(roleId => {
                $('#role_' + roleId).prop('checked', true);
            });

            // Show the modal
            $('#manageRolesModal').modal('show');
        }

        $('#updateRolesForm').submit(function(e) {
            e.preventDefault();

            let userId = $('#userId').val();
            let selectedRoles = $('.role-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "{{ route('users.updateRoles', ['id' => '__USER_ID__']) }}".replace('__USER_ID__',
                    userId),
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    roles: selectedRoles
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success("Role Updated Successfully!", "Success");
                        $('#manageRolesModal').modal('hide');
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            });
        });
    </script>

</x-app-layout>
