<?php
    $c = $b['colors'];
?>

<?php echo $__env->make('emails.partials.header', [
    'label'     => 'Task Overdue',
    'preheader' => $task_name . ' for ' . $client . ' is ' . $days_overdue . ' past due and still ' . $status . '.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.hero', [
    'accent'  => $c['danger'],
    'icon'    => '!',
    'eyebrow' => $escalated ? 'Overdue · Escalated' : 'Overdue',
    'meta'    => 'Was due ' . $due_date . ' at ' . $due_time . ' (' . $timezone . ')',
    'title'   => 'Hi ' . $assignee_name . ', this task is past due.',
    'intro'   => e($task_name) . ' (' . e($task_id) . ') for <strong style="color:#ffffff;">' . e($client) . '</strong> is now <strong style="color:#ffffff;">' . e($days_overdue) . '</strong> past due and still marked <strong style="color:#ffffff;">' . e($status) . '</strong>.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.task-card', ['accent' => $c['danger']], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.details', [
    'heading' => 'Task',
    'rows' => [
        'Task'     => $task_name,
        'Client'   => $client,
        'Was due'  => $due_date . ' at ' . $due_time . ' ' . $timezone,
        'Overdue'  => $days_overdue,
        'Priority' => $priority,
        'Status'   => $status,
    ],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if($escalated): ?>
    <?php echo $__env->make('emails.partials.callout', [
        'bg'     => $c['danger_light'],
        'border' => $c['danger'],
        'html'   => '<strong style="color:' . $c['danger'] . ';">Escalated.</strong> This task has been open for more than ' . e($escalation_threshold) . ' day(s) past its due date'
            . ($escalated_to_name ? ', so <strong>' . e($escalated_to_name) . '</strong> has been copied.' : '.'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php echo $__env->make('emails.partials.steps', [
    'heading' => 'Please do one of two things',
    'steps' => [
        'Close it out if the work is done, or',
        'Set a realistic new due date so ' . $project . ' stays accurate.',
    ],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<tr>
    <td class="px" style="padding: 16px 40px 0 40px; font-size: 14px; line-height: 22px; color: <?php echo e($c['muted']); ?>;">
        If something's blocking you, add a comment on the task and we'll help unblock it.
    </td>
</tr>

<?php echo $__env->make('emails.partials.cta', [
    'url'   => $task_link,
    'label' => 'Open the task',
    'note'  => $account_manager_copied
        ? e($account_manager) . ' has been copied as this task affects a client deliverable.'
        : null,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/tasks/overdue.blade.php ENDPATH**/ ?>