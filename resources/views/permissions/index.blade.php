<x-app-layout>
    <x-slot name="header">
        {{ __('Permissions') }}
    </x-slot>
    <div class="container">
        <table class="table">
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
</x-app-layout>
