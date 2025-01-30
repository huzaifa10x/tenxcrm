<x-app-layout>
    <x-slot name="header">
        {{ __('Create Customer') }}
    </x-slot>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="text" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Phone Number</label>
                        <input name="phone" type="text" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">City</label>
                        <input name="city" type="text" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Website</label>
                        <input name="website" type="text" class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-control" required>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary mt-3">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
