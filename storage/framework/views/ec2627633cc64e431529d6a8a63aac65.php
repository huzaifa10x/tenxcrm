<?php
    $c = $b['colors'];

    if (is_null($daysLeft)) {
        $countdown = ['value' => '—', 'label' => 'No due date', 'color' => $c['muted']];
    } elseif ($daysLeft < 0) {
        $countdown = ['value' => abs($daysLeft), 'label' => 'Days overdue', 'color' => $c['danger']];
    } elseif ($daysLeft === 0) {
        $countdown = ['value' => 'Today', 'label' => 'Due', 'color' => $c['warning']];
    } else {
        $countdown = ['value' => $daysLeft, 'label' => $daysLeft === 1 ? 'Day left' : 'Days left', 'color' => $c['primary_dark']];
    }
?>

<?php echo $__env->make('emails.partials.header', [
    'label'     => 'New Task Assigned',
    'preheader' => $assignedByName . ' assigned you "' . $task->title . '" — due ' . $dueDate . '.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: <?php echo e($c['primary']); ?>;">&nbsp;</td>
</tr>


<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: <?php echo e($c['dark']); ?>;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="width: 44px; height: 44px; background-color: <?php echo e($c['primary']); ?>; border-radius: 12px; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: <?php echo e($c['dark']); ?>; font-weight: 800;">+</td>
                <td style="padding-left: 14px;">
                    <div style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: <?php echo e($c['primary']); ?>;">New Assignment</div>
                    <div style="font-size: 13px; color: <?php echo e($c['on_dark_muted']); ?>; margin-top: 2px;"><?php echo e($createdAt); ?> (GST)</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title" style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">Hi <?php echo e($recipient); ?>, you've got a new task.</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: <?php echo e($c['on_dark_soft']); ?>;">
            <strong style="color: #ffffff;"><?php echo e($assignedByName); ?></strong> has assigned you a task on <strong style="color: #ffffff;"><?php echo e($project); ?></strong>. Here's everything you need to get started.
        </p>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: 1px solid <?php echo e($c['border']); ?>; border-left: 5px solid <?php echo e($c['primary']); ?>; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div style="font-size: 12px; font-weight: 700; color: <?php echo e($c['muted']); ?>; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;"><?php echo e($taskCode); ?></div>
                    <div style="font-size: 20px; line-height: 28px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin: 6px 0 14px 0;"><?php echo e($task->title); ?></div>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: <?php echo e($priorityColor); ?>; border-radius: 50px;"><?php echo e($priority); ?></span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['primary_dark']); ?>; background-color: <?php echo e($c['primary_light']); ?>; border-radius: 50px;"><?php echo e($type); ?></span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; background-color: <?php echo e($c['bg']); ?>; border-radius: 50px;"><?php echo e($status); ?></span>
                    <?php if($isRecurring): ?>
                        <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['dark']); ?>; background-color: <?php echo e($c['warning_light']); ?>; border-radius: 50px;">&#8635; Recurring</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($c['bg']); ?>; border-radius: 12px;">
            <tr>
                <td class="stack stack-first" width="33%" style="padding: 18px 12px; text-align: center;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">Start Date</div>
                    <div style="font-size: 16px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 6px;"><?php echo e($startDate); ?></div>
                </td>
                <td class="stack" width="34%" style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">Due Date</div>
                    <div style="font-size: 16px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 6px;"><?php echo e($dueDate); ?></div>
                </td>
                <td class="stack" width="33%" style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;"><?php echo e($countdown['label']); ?></div>
                    <div style="font-size: 22px; font-weight: 800; color: <?php echo e($countdown['color']); ?>; margin-top: 4px;"><?php echo e($countdown['value']); ?></div>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; margin-bottom: 10px;">Task Details</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="padding: 11px 0; width: 42%; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Project</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($project); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Assigned By</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">
                    <?php echo e($assignedByName); ?>

                    <?php if($assignedByEmail): ?>
                        <br><a href="mailto:<?php echo e($assignedByEmail); ?>" style="font-size: 12px; font-weight: 400; color: <?php echo e($c['primary_dark']); ?>; text-decoration: none;"><?php echo e($assignedByEmail); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Collaborators</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($collaboratorsCount > 0 ? $collaboratorsCount . ' team member(s)' : 'Only you'); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>;">Attachments</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>;"><?php echo e($attachmentsCount > 0 ? $attachmentsCount . ' file(s) attached' : 'None'); ?></td>
            </tr>
        </table>
    </td>
</tr>


<?php if($description): ?>
<tr>
    <td class="px" style="padding: 24px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($c['primary_light']); ?>; border-radius: 12px;">
            <tr>
                <td style="padding: 18px 20px;">
                    <div style="font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['primary_dark']); ?>; margin-bottom: 8px;">Brief</div>
                    <div style="font-size: 14px; line-height: 22px; color: <?php echo e($c['text']); ?>;"><?php echo e($description); ?></div>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php endif; ?>


<?php if(!is_null($daysLeft) && $daysLeft <= 2): ?>
<tr>
    <td class="px" style="padding: 16px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($daysLeft < 0 ? $c['danger_light'] : $c['warning_light']); ?>; border-left: 4px solid <?php echo e($daysLeft < 0 ? $c['danger'] : $c['warning']); ?>; border-radius: 8px;">
            <tr>
                <td style="padding: 14px 18px; font-size: 14px; line-height: 21px; color: <?php echo e($c['text']); ?>;">
                    <?php if($daysLeft < 0): ?>
                        <strong style="color: <?php echo e($c['danger']); ?>;">Overdue:</strong> This task passed its due date <strong><?php echo e(abs($daysLeft)); ?> day(s) ago</strong>. Please prioritise it.
                    <?php elseif($daysLeft === 0): ?>
                        <strong style="color: #B7791F;">Due today:</strong> This task needs to be wrapped up by end of day.
                    <?php else: ?>
                        <strong style="color: #B7791F;">Tight deadline:</strong> Only <strong><?php echo e($daysLeft); ?> day(s)</strong> left to complete this task.
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php endif; ?>


<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: <?php echo e($c['primary']); ?>; border-radius: 10px;">
                    <a href="<?php echo e($url); ?>" target="_blank" style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: <?php echo e($c['dark']); ?>; text-decoration: none; letter-spacing: 0.3px;">Open Task &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: <?php echo e($c['muted']); ?>;">
            Button not working? <a href="<?php echo e($url); ?>" style="color: <?php echo e($c['primary_dark']); ?>; word-break: break-all;"><?php echo e($url); ?></a>
        </div>
    </td>
</tr>

<?php echo $__env->make('emails.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/tasks/assigned.blade.php ENDPATH**/ ?>