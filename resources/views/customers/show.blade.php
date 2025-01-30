div<x-app-layout>
    <x-slot name="header">
        {{ __('CUSTOMER - ') . $customer->name }}
    </x-slot>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-lg-6">
                    <label class="form-label">Name</label>
                    <input readonly value="{{ $customer->name }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Email</label>
                    <input readonly value="{{ $customer->email }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Phone Number</label>
                    <input readonly value="{{ $customer->phone }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">City</label>
                    <input readonly value="{{ $customer->city }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Website</label>
                    <input readonly value="{{ $customer->website }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Company</label>
                    <input readonly value="{{ $customer->company->name }}" type="text" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Created at</label>
                    <input readonly value="{{ $customer->created_at }}" type="text" class="form-control">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
