<x-app-layout>
    <x-slot name="header">
        {{ __('Payment Methods') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage payment methods'))
            <div class="card-header">
                <a href="{{ route('payment-methods.create') }}" class="btn btn-outline-primary">
                    Add New Payment Method
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Method Name</th>
                        @if ($userPermissions->contains('can manage payment methods'))
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($PaymentMethods as $PaymentMethod)
                        <tr>
                            <td> {{ $PaymentMethod->id }}</td>
                            <td>{{ $PaymentMethod->name }}</td>
                            @if ($userPermissions->contains('can manage payment methods'))
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-10 justify-content-center">
                                        <a href="{{ route('payment-methods.edit', $PaymentMethod->id) }}"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="{{ route('payment-methods.destroy', $PaymentMethod->id) }}"
                                            method="POST" style="display: inline-block;"
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
            {{ $PaymentMethods->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>
