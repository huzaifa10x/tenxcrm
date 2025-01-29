<x-app-layout>
    <div class="container">
        <h1>Create Customer</h1>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" name="website" class="form-control">
            </div>
            <div class="form-group">
                <label for="company_id">Company</label>
                <select name="company_id" class="form-control" required>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create</button>
        </form>
    </div>
</x-app-layout>
