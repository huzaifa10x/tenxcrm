<x-app-layout>
    <x-slot name="header">
        {{ __('Create Industry') }}
    </x-slot>
    @if ($userPermissions->contains('can manage industries'))
        <form action="{{ route('industries.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Industry Name</label>
                            <input type="text" placeholder="Enter industry Name" required name="industry"
                                class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Create Industry</button>
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
