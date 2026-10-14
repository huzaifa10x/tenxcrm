@php
    $__me = auth()->user();
    $__pending = \App\Support\Access::approveLeaves($__me) ? \App\Models\LeaveRequest::where('status', 'pending')->count() : 0;
    $__tabs = [['My attendance', route('attendance.index', \App\Support\Access::manageAttendance($__me) ? ['user_id' => $__me->id] : []), 'my-attendance', null]];
    if (\App\Support\Access::manageAttendance($__me)) {
        $__tabs[] = ['Team attendance', route('attendance.index'), 'team', null];
    }
    $__tabs[] = ['My leave', route('leaves.index'), 'my-leave', null];
    if (\App\Support\Access::approveLeaves($__me)) {
        $__tabs[] = ['Leave requests', route('leaves.admin'), 'leave-admin', $__pending];
    }
    if (\App\Support\Access::manageLeavePolicies($__me)) {
        $__tabs[] = ['Leave policies', route('leaves.policies'), 'policies', null];
    }
    if ($__me->hasRole('ADMIN')) {
        $__tabs[] = ['Attendance settings', route('settings.index'), 'settings', null];
    }
@endphp
<nav class="xa-tabs" aria-label="Attendance and leave">
    @foreach ($__tabs as [$label, $url, $key, $count])
        <a href="{{ $url }}" class="xa-tab {{ ($active ?? '') === $key ? 'active' : '' }}" @if (($active ?? '') === $key) aria-current="page" @endif>
            {{ $label }}@if ($count)<span class="xa-count is-hot">{{ $count }}</span>@endif
        </a>
    @endforeach
</nav>
