<x-app-layout>
    <x-slot name="header">
        {{ __('Create User') }}
    </x-slot>
    <div class="container">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <div class="icon-field has-validation">
                                    <span class="icon">
                                        <iconify-icon icon="f7:person"></iconify-icon>
                                    </span>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Enter First Name" required="">
                                    <div class="invalid-feedback">
                                        Please provide name
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="icon-field has-validation">
                                    <span class="icon">
                                        <iconify-icon icon="mage:email"></iconify-icon>
                                    </span>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Email"
                                        required="">
                                    <div class="invalid-feedback">
                                        Please provide email address
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <div class="icon-field has-validation">
                                    <span class="icon">
                                        <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                                    </span>
                                    <input type="text" name="phone" class="form-control"
                                        placeholder="+1 (555) 000-0000" required="">
                                    <div class="invalid-feedback">
                                        Please provide phone number
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="icon-field has-validation">
                                    <span class="icon">
                                        <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="*******"
                                        required="">
                                    <div class="invalid-feedback">
                                        Please provide password
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <div class="icon-field has-validation">
                                    <span class="icon">
                                        <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                    </span>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="*******" required="">
                                    <div class="invalid-feedback">
                                        Please confirm password
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label">Roles</label>
                                <div class="form-group">
                                    <div class="row">
                                        @foreach ($roles as $role)
                                            <div class="col-lg-3">
                                                <div class="form-check d-flex align-items-center gap-2">
                                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                                        value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                        @if (in_array($role->id, old('roles', []))) checked @endif>
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary-600" type="submit">Submit form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
