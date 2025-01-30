<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT CUSTOMER - ') . $customer->name }}
    </x-slot>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <label class="form-label">Name</label>
                        <input name="name" value="{{ old('name', $customer->name) }}" type="text"
                            class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Email</label>
                        <input name="email" value="{{ old('email', $customer->email) }}" type="text"
                            class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Phone Number</label>
                        <input name="phone" value="{{ old('phone', $customer->phone) }}" type="text"
                            class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">City</label>
                        <input name="city" value="{{ old('city', $customer->city) }}" type="text"
                            class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Website</label>
                        <input name="website" value="{{ old('website', $customer->website) }}" type="text"
                         class="form-control">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Company</label>
                        <select name="company_id" class="form-control" required>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $company->id == old('company_id', $customer->company_id) ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</x-app-layout>
