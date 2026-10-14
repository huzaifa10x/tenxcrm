
<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: 1px solid <?php echo e($c['border']); ?>; border-left: 5px solid <?php echo e($accent); ?>; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div style="font-size: 12px; font-weight: 700; color: <?php echo e($c['muted']); ?>; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;"><?php echo e($task_id); ?></div>
                    <div style="font-size: 20px; line-height: 28px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin: 6px 0 4px 0;"><?php echo e($task_name); ?></div>
                    <div style="font-size: 14px; color: <?php echo e($c['muted']); ?>; margin-bottom: 14px;"><?php echo e($client); ?></div>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: <?php echo e($priority_color); ?>; border-radius: 50px;"><?php echo e($priority); ?></span>
                    <span style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; background-color: <?php echo e($c['bg']); ?>; border-radius: 50px;"><?php echo e($status); ?></span>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/partials/task-card.blade.php ENDPATH**/ ?>