<?php
    $c = $b['colors'];

    if ($onTime === true) {
        $delivery = ['value' => 'On Time', 'color' => $c['primary_dark']];
    } elseif ($onTime === false) {
        $delivery = ['value' => $lateDays . 'd Late', 'color' => $c['danger']];
    } else {
        $delivery = ['value' => 'No Deadline', 'color' => $c['muted']];
    }
?>

<?php echo $__env->make('emails.partials.header', [
    'label'     => 'Task Completed',
    'preheader' => $completedByName . ' completed "' . $task->title . '" on ' . $completedDate . '.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: <?php echo e($c['primary']); ?>;">&nbsp;</td>
</tr>


<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: <?php echo e($c['dark']); ?>;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td style="width: 44px; height: 44px; background-color: <?php echo e($c['primary']); ?>; border-radius: 50%; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: <?php echo e($c['dark']); ?>; font-weight: 800;">&#10003;</td>
                <td style="padding-left: 14px;">
                    <div style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: <?php echo e($c['primary']); ?>;">Task Completed</div>
                    <div style="font-size: 13px; color: <?php echo e($c['on_dark_muted']); ?>; margin-top: 2px;"><?php echo e($completedDate); ?> at <?php echo e($completedTime); ?> (GST)</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title" style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">Great news, <?php echo e($recipient); ?>!</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: <?php echo e($c['on_dark_soft']); ?>;">
            <strong style="color: #ffffff;"><?php echo e($completedByName); ?></strong> has marked a task you created on <strong style="color: #ffffff;"><?php echo e($project); ?></strong> as complete. It's ready for your review.
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
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['dark']); ?>; background-color: <?php echo e($c['primary']); ?>; border-radius: 50px;">&#10003; Completed</span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: <?php echo e($priorityColor); ?>; border-radius: 50px;"><?php echo e($priority); ?></span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['primary_dark']); ?>; background-color: <?php echo e($c['primary_light']); ?>; border-radius: 50px;"><?php echo e($type); ?></span>
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
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">Delivery</div>
                    <div style="font-size: 18px; font-weight: 800; color: <?php echo e($delivery['color']); ?>; margin-top: 6px;"><?php echo e($delivery['value']); ?></div>
                </td>
                <td class="stack" width="34%" style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">Time Taken</div>
                    <div style="font-size: 16px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 6px;"><?php echo e($timeTaken); ?></div>
                </td>
                <td class="stack" width="33%" style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">Deliverables</div>
                    <div style="font-size: 16px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 6px;"><?php echo e($attachmentsCount); ?> file(s)</div>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; margin-bottom: 10px;">Summary</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="padding: 11px 0; width: 42%; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Project</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($project); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Assigned To</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($assignee); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Completed By</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($completedByName); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Start Date</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($startDate); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Due Date</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($dueDate); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;">Completed On</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['primary_dark']); ?>; border-bottom: 1px solid <?php echo e($c['border']); ?>;"><?php echo e($completedDate); ?>, <?php echo e($completedTime); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; color: <?php echo e($c['muted']); ?>;">Comments</td>
                <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>;"><?php echo e($commentsCount > 0 ? $commentsCount . ' comment(s) on this task' : 'No comments'); ?></td>
            </tr>
        </table>
    </td>
</tr>


<?php if(!is_null($onTime)): ?>
<tr>
    <td class="px" style="padding: 24px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($onTime ? $c['primary_light'] : $c['danger_light']); ?>; border-left: 4px solid <?php echo e($onTime ? $c['primary'] : $c['danger']); ?>; border-radius: 8px;">
            <tr>
                <td style="padding: 14px 18px; font-size: 14px; line-height: 21px; color: <?php echo e($c['text']); ?>;">
                    <?php if($onTime): ?>
                        <strong style="color: <?php echo e($c['primary_dark']); ?>;">Delivered on time.</strong> Completed on or before the <?php echo e($dueDate); ?> deadline.
                    <?php else: ?>
                        <strong style="color: <?php echo e($c['danger']); ?>;">Delivered late.</strong> Completed <strong><?php echo e($lateDays); ?> day(s)</strong> after the <?php echo e($dueDate); ?> deadline.
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php endif; ?>


<tr>
    <td class="px" style="padding: 24px 40px 0 40px; font-size: 14px; line-height: 22px; color: <?php echo e($c['muted']); ?>;">
        <strong style="color: <?php echo e($c['text']); ?>;">Next step:</strong> review the deliverables and leave feedback, or reopen the task if anything needs another pass.
    </td>
</tr>


<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: <?php echo e($c['primary']); ?>; border-radius: 10px;">
                    <a href="<?php echo e($url); ?>" target="_blank" style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: <?php echo e($c['dark']); ?>; text-decoration: none; letter-spacing: 0.3px;">Review Task &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: <?php echo e($c['muted']); ?>;">
            Button not working? <a href="<?php echo e($url); ?>" style="color: <?php echo e($c['primary_dark']); ?>; word-break: break-all;"><?php echo e($url); ?></a>
        </div>
    </td>
</tr>

<?php echo $__env->make('emails.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/tasks/completed.blade.php ENDPATH**/ ?>