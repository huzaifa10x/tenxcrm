<x-app-layout>
    <div class="container">
        <h1>Edit Customer</h1>
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone', $customer->phone) }}" required>
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" name="website" class="form-control"
                    value="{{ old('website', $customer->website) }}">
            </div>

            <div class="form-group">
                <label for="company_id">Company</label>
                <select name="company_id" class="form-control" required>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ $company->id == old('company_id', $customer->company_id) ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</x-app-layout>
