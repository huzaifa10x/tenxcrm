<x-app-layout>
    <x-slot name="header">
        {{ __('Industries') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage industries'))
            <div class="card-header">
                <a href="{{ route('industries.create') }}" class="btn btn-outline-primary">
                    Add New Industry
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Industry Name</th>
                        @if ($userPermissions->contains('can manage industries'))
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($industries as $industry)
                        <tr>
                            <td> {{ $industry->id }}</td>
                            <td>{{ $industry->industry }}</td>
                            @if ($userPermissions->contains('can manage industries'))
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('industries.edit', $industry->id) }}"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST"
                                            style="display: inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $industries->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>



