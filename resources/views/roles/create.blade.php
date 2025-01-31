<x-app-layout>
    <x-slot name="header">
        {{ __('Create Role') }}
    </x-slot>
    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-12">
                        <label class="form-label">Role Name</label>
                        <input type="text" placeholder="Enter Role Name" required name="name" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Role Permissions</label>
                        <div class="d-block d-lg-flex flex-wrap">
                            @foreach ($permissions as $permission)
                                <div class="col-lg-4 form-check d-flex align-items-center g-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->id }}" id="permission-{{ $permission->id }}">
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Role</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
