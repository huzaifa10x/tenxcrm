<x-app-layout>
    <x-slot name="header">
        {{ __('Customers') }}
    </x-slot>
    <div class="card basic-data-table">
        <div class="card-header">
            <a class="btn btn-outline-primary" href="{{ route('customers.create') }}">Create New Customer</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->city }}</td>
                            <td>{{ $customer->website }}</td>
                            <td>{{ $customer->company->name }}</td>
                            <td>
                                <a href="{{ route('customers.show', $customer->id) }}"
                                    class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="margin-top: -5px;"
                                        onclick="return confirm('Are you sure you want to delete this customer?')"
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="fluent:delete-24-regular" class="menu-icon"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $customers->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>
