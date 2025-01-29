<!-- resources/views/companies/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('companies.create') }}" class="btn btn-primary mb-4">Create Company</a>
                    @if ($message = session('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Website</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->website }}</td>
                                    <td>{{ $company->city }}</td>
                                    <td>{{ $company->country }}</td>
                                    <td>
                                        <a href="{{ route('companies.show', $company->id) }}" class="btn btn-info">View</a>
                                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline;">
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
            </div>
        </div>
    </div>
</x-app-layout>
