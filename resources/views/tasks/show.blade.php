@if (auth()->user()->hasRole('ADMIN') ||
        ($task->createdBy && auth()->user()->id == $task->createdBy->id) ||
        auth()->user()->id == $task->project->accountManagers[0]->id ||
        auth()->user()->id == $task->project->seoAdmins[0]->id ||
        ($task->assignedTo && auth()->user()->id == $task->assignedTo->id) ||
        ($task->collaborators && $task->collaborators->contains('user_id', auth()->user()->id)))
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
@else
    <x-app-layout>
        @include('vendor.not-allowed')
    </x-app-layout>
@endif
