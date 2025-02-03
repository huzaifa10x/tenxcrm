<x-app-layout>
    <x-slot name="header">
        {{ __('Create Payment Method') }}
    </x-slot>
    @if ($userPermissions->contains('can manage payment methods'))
        <form action="{{ route('payment-methods.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Method Name</label>
                            <input type="text" placeholder="Enter Method Name" required name="name"
                                class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Create Method</button>
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
