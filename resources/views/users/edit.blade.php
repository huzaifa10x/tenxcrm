<x-app-layout>
    <x-slot name="header">
        {{ __('Edit User') }}
    </x-slot>
    <div class="container">
        <h1>Edit User</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                    required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                    required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="phone" name="phone" id="phone" class="form-control" value="{{ $user->phone }}"
                    required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <div class="form-group">
                <label for="roles">Roles</label>
                <select name="roles[]" id="roles" class="form-control" multiple>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</x-app-layout>
