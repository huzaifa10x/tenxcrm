<!-- resources/views/companies/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('Create Company') }}
    </x-slot>
    @if ($userPermissions->contains('can manage companies'))
        <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Website</label>
                            <input type="text" name="website" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company City</label>
                            <input type="text" name="city" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Country</label>
                            <input type="text" name="country" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Company Logo</label>
                            <input type="file" name="logo" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary-600 radius-8">Add Company</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
