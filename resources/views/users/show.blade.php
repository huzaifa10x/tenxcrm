<x-app-layout>
    <x-slot name="header">
        {{ __('USER - ' . $user->name) }}
    </x-slot>
    @if ($userPermissions->contains('can manage users'))
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="f7:person"></iconify-icon>
                            </span>
                            <input readonly type="text" name="name" class="form-control"
                                value="{{ $user->name }}" placeholder="Enter First Name" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="mage:email"></iconify-icon>
                            </span>
                            <input readonly type="email" name="email" class="form-control" placeholder="Enter Email"
                                value="{{ $user->email }}" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                            </span>
                            <input readonly type="text" name="phone" class="form-control"
                                value="{{ $user->phone }}" placeholder="+1 (555) 000-0000" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input readonly type="text" name="phone" class="form-control"
                            value="{{ $user->roles->pluck('name')->implode(', ') }}">

                    </div>
                </div>
            </div>
        </div>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
