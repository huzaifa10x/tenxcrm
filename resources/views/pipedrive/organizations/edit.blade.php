<x-app-layout>
    <x-slot name="header">{{ __('CRM — Edit Organization') }}</x-slot>
    @include('pipedrive._styles')
    <div class="crm-page">
        <div class="crm-toolbar"><h4><i class="bi bi-pencil-square me-2"></i>Edit Organization</h4>
            <a href="{{ route('pipedrive.organizations.index') }}" class="btn btn-sm btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
        <div class="crm-panel"><div class="crm-panel-body">
            <form method="POST" action="{{ route('pipedrive.organizations.update', $organization) }}">@csrf @method('PUT')
                @include('pipedrive.organizations._form')
                <hr><div class="text-end"><button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button></div>
            </form>
        </div></div>
    </div>
</x-app-layout>
