<x-app-layout>
    <x-slot name="header">{{ __('CRM — Add Deal') }}</x-slot>
    @include('pipedrive._styles')
    <div class="crm-page">
        <div class="crm-toolbar">
            <h4><i class="bi bi-plus-circle me-2"></i>Add Deal</h4>
            <a href="{{ route('pipedrive.deals.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
        <div class="crm-panel">
            <div class="crm-panel-body">
                <form method="POST" action="{{ route('pipedrive.deals.store') }}">
                    @csrf
                    @include('pipedrive.deals._form')
                    <hr>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('pipedrive.deals.index') }}" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Save Deal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
