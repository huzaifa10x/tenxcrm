<x-app-layout>
    <div class="container">
        <h1>Customer Details</h1>
        <div class="card">
            <div class="card-header">
                <strong>{{ $customer->name }}</strong>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Phone Number:</strong> {{ $customer->phone }}</p>
                <p><strong>City:</strong> {{ $customer->city }}</p>
                <p><strong>Website:</strong> <a href="{{ $customer->website }}"
                        target="_blank">{{ $customer->website }}</a></p>
                <p><strong>Company:</strong> {{ $customer->company->name }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
