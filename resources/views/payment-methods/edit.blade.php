<x-app-layout>
    <x-slot name="header">
        {{ __('Method - ' . $paymentMethod->name) }}
    </x-slot>
    @if ($userPermissions->contains('can manage payment methods'))
        <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Method Name</label>
                            <input value="{{ old('name', $paymentMethod->name) }}" type="text"
                                placeholder="Method Name" required name="name" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update method</button>
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
