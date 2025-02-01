<!-- resources/views/companies/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        {{ __('Companies') }}
    </x-slot>

    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage companies'))
            <div class="card-header">
                <a class="btn btn-outline-primary" href="{{ route('companies.create') }}">Create New Company</a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
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
                            <td><a href="javascript:void(0)" class="text-primary-600">{{ $company->id }}</a></td>
                            <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        {{ $company->name }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="text-decoration-underline" href="{{ $company->website }}">Website</a>
                            </td>
                            <td>{{ $company->city }}</td>
                            <td>{{ $company->country }}</td>
                            <td>
                                <a href="{{ route('companies.show', $company->id) }}"
                                    class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                </a>
                                @if ($userPermissions->contains('can manage companies'))
                                    <a href="{{ route('companies.edit', $company->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="margin-top: -5px;"
                                            onclick="return confirm('Are you sure you want to delete this company?')"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="fluent:delete-24-regular"
                                                class="menu-icon"></iconify-icon>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $companies->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
