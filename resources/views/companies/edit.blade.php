<!-- resources/views/companies/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT COMPNAY - ') . $company->name }}
    </x-slot>
    <form method="POST" action="{{ route('companies.update', $company->id) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <label class="form-label">Company Name</label>
                        <input value="{{ $company->name }}" type="text" name="name" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Company Website</label>
                        <input value="{{ $company->website }}" type="text" name="website" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Company City</label>
                        <input value="{{ $company->city }}" type="text" name="city" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Company Country</label>
                        <input value="{{ $company->country }}" type="text" name="country" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary-600 radius-8">Update Company</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
