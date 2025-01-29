<x-app-layout>
    <div class="container">
        <h1>Customers</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Create Customer</a>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>City</th>
                    <th>Website</th>
                    <th>Company</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->city }}</td>
                        <td>{{ $customer->website }}</td>
                        <td>{{ $customer->company->name }}</td>
                        <td>
                            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
