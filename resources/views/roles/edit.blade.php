<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT ROLE - ' . $role->name) }}
    </x-slot>
    @if ($userPermissions->contains('can manage roles'))
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Role Name</label>
                            <input type="text" value="{{ $role->name }}" placeholder="Enter Role Name" required
                                name="name" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Role Permissions</label>
                            <div class="d-block d-lg-flex flex-wrap">
                                @foreach ($permissions as $permission)
                                    <div class="col-lg-4 form-check d-flex align-items-center g-2">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                            @if (in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))) checked @endif>
                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
