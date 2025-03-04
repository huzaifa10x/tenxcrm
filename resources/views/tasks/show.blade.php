@if (session('is_not_ajax'))
    <x-app-layout>
        <x-slot name="header">
            {{ __('Task') }}
        </x-slot>
        @include('vendor.task-show-data')
    </x-app-layout>
@else
    @include('vendor.task-show-data')
@endif
