<?php
    $c = $b['colors'];
    $sections = [
        ['key' => 'overdue',   'title' => 'Overdue',   'count' => $overdue_count,   'color' => $c['danger'],       'empty' => 'Nothing overdue.'],
        ['key' => 'due_today', 'title' => 'Due today', 'count' => $due_today_count, 'color' => $c['warning'],      'empty' => 'Nothing due today.'],
        ['key' => 'upcoming',  'title' => 'Upcoming',  'count' => $upcoming_count,  'color' => $c['primary_dark'], 'empty' => 'Nothing due in the next few days.'],
    ];
?>

<?php echo $__env->make('emails.partials.header', [
    'label'     => 'My Tasks',
    'preheader' => $overdue_count . ' overdue, ' . $due_today_count . ' due today, ' . $upcoming_count . ' upcoming.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.hero', [
    'accent'    => $c['primary'],
    'icon'      => '&#9776;',
    'iconColor' => $c['dark'],
    'eyebrow'   => 'My Tasks · Daily digest',
    'meta'      => $digest_date . ' (' . $timezone . ')',
    'title'     => 'Good morning, ' . $recipient_name . '.',
    'intro'     => "Here's what's on your plate today.",
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($c['bg']); ?>; border-radius: 12px;">
            <tr>
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="stack<?php echo e($loop->first ? ' stack-first' : ''); ?>" width="33%" style="padding: 18px 12px; text-align: center;<?php echo e($loop->first ? '' : ' border-left: 1px solid ' . $c['border'] . ';'); ?>">
                        <div style="font-size: 26px; font-weight: 800; color: <?php echo e($s['count'] ? $s['color'] : $c['muted']); ?>;"><?php echo e($s['count']); ?></div>
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>; margin-top: 4px;"><?php echo e($s['title']); ?></div>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </table>
    </td>
</tr>

<?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $rows = ${$s['key']}; ?>
    <tr>
        <td class="px" style="padding: 28px 40px 0 40px;">
            <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($s['color']); ?>; margin-bottom: 10px;"><?php echo e($s['title']); ?> (<?php echo e($s['count']); ?>)</div>
            <?php if(count($rows) === 0): ?>
                <div style="font-size: 14px; color: <?php echo e($c['muted']); ?>; padding: 6px 0;"><?php echo e($s['empty']); ?></div>
            <?php else: ?>
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="padding: 12px 0; border-bottom: 1px solid <?php echo e($c['border']); ?>;">
                                <a href="<?php echo e($row['task_link']); ?>" style="font-weight: 700; color: <?php echo e($c['text']); ?>; text-decoration: none;"><?php echo e($row['task_name']); ?></a>
                                <div style="font-size: 12px; color: <?php echo e($c['muted']); ?>; margin-top: 3px;">
                                    <?php echo e($row['task_id']); ?> &middot; <?php echo e($row['client']); ?> &middot; Due <?php echo e($row['due_date']); ?> &middot; <?php echo e($row['status']); ?>

                                </div>
                            </td>
                            <td align="right" valign="top" style="padding: 12px 0 12px 10px; border-bottom: 1px solid <?php echo e($c['border']); ?>; white-space: nowrap;">
                                <span style="display: inline-block; padding: 4px 10px; font-size: 10px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: <?php echo e($row['priority_color']); ?>; border-radius: 50px;"><?php echo e($row['priority']); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                <?php if($s['count'] > count($rows)): ?>
                    <div style="font-size: 13px; color: <?php echo e($c['muted']); ?>; padding-top: 10px;">
                        + <?php echo e($s['count'] - count($rows)); ?> more — <a href="<?php echo e($board_link); ?>" style="color: <?php echo e($c['primary_dark']); ?>;">see them on your board</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php echo $__env->make('emails.partials.cta', [
    'url'   => $board_link,
    'label' => 'Open my task board',
    'note'  => 'Sent to ' . e($recipient_email) . ' every working day. <a href="' . e($prefs_link) . '" style="color:' . $c['primary_dark'] . ';">Manage email preferences</a>',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('emails.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/tasks/daily-digest.blade.php ENDPATH**/ ?>