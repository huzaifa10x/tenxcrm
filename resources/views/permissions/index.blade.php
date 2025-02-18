<x-app-layout>
    <x-slot name="header">
        {{ __('Permissions') }}
    </x-slot>
    <div class="card basic-data-table">
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $permissions->links('vendor.pagination.custom') }}
        </div>
    </div>
</x-app-layout>
