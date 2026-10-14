@php
    $c = $b['colors'];
    $accent = $is_final ? $c['warning'] : $c['primary'];
@endphp

@include('emails.partials.header', [
    'label'     => 'Task Due Soon',
    'preheader' => $task_name . ' is due ' . $due_date . ' at ' . $due_time . ' ' . $timezone . ' — ' . $time_remaining . ' left.',
])

@include('emails.partials.hero', [
    'accent'    => $accent,
    'icon'      => '&#9201;',
    'iconColor' => $c['dark'],
    'eyebrow'   => 'Due in ' . $time_remaining,
    'meta'      => 'Due ' . $due_date . ' at ' . $due_time . ' (' . $timezone . ')',
    'title'     => 'Hi ' . $assignee_name . ', heads-up — one of your tasks is due soon.',
    'intro'     => e($task_name) . ' (' . e($task_id) . ') for <strong style="color:#ffffff;">' . e($client) . '</strong> is due in <strong style="color:#ffffff;">' . e($time_remaining) . '</strong>.',
])

@include('emails.partials.task-card', ['accent' => $accent])

@include('emails.partials.details', [
    'heading' => 'Task',
    'rows' => [
        'Task'           => $task_name . ' (' . $task_id . ')',
        'Client'         => $client,
        'Project'        => $project,
        'Due'            => $due_date . ' at ' . $due_time . ' ' . $timezone,
        'Time remaining' => $time_remaining . ' left',
        'Priority'       => $priority,
        'Status'         => $status,
    ],
])

@include('emails.partials.callout', [
    'bg'     => $c['primary_light'],
    'border' => $c['primary'],
    'html'   => '<strong style="color:' . $c['primary_dark'] . ';">If you\'re on track, no action needed.</strong> If you\'ll need more time, update the due date or add a comment on the task so <strong>' . e($account_manager) . '</strong> isn\'t caught out.',
])

@include('emails.partials.cta', [
    'url'   => $task_link,
    'label' => 'Open the task',
    'note'  => 'You are receiving this because the task is assigned to you. <a href="' . e($prefs_link) . '" style="color:' . $c['primary_dark'] . ';">Manage email preferences</a>',
])

@include('emails.partials.footer')
