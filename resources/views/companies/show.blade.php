<!-- resources/views/companies/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('COMPANY - ') . $company->name }}
    </x-slot>
    <div class="card">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-lg-12">
                    <label class="form-label">Company Name</label>
                    <input readonly value="{{ $company->name }}" type="text" name="name" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company Website</label>
                    <input readonly value="{{ $company->website }}" type="text" name="website" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company City</label>
                    <input readonly value="{{ $company->city }}" type="text" name="city" class="form-control">
                </div>
                <div class="col-lg-12">
                    <label class="form-label">Company Country</label>
                    <input readonly value="{{ $company->country }}" type="text" name="country" class="form-control">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
