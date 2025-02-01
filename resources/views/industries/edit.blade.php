<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT INDUSTRY - ' . $industry->industry) }}
    </x-slot>
    @if ($userPermissions->contains('can manage industries'))
        <form action="{{ route('industries.update', $industry->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Industry Name</label>
                            <input value="{{ old('industry', $industry->industry) }}" type="text"
                                placeholder="Enter industry Name" required name="industry" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update Industry</button>
                            <a href="{{ route('industries.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
