
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; margin-bottom: 10px;"><?php echo e($heading); ?></div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px; line-height: 22px; color: <?php echo e($c['text']); ?>;">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td valign="top" style="width: 34px; padding: 6px 0;">
                        <div style="width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: <?php echo e($c['primary_light']); ?>; color: <?php echo e($c['primary_dark']); ?>; font-size: 12px; font-weight: 800; text-align: center;"><?php echo e($i + 1); ?></div>
                    </td>
                    <td valign="top" style="padding: 7px 0;"><?php echo e($step); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </td>
</tr>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/partials/steps.blade.php ENDPATH**/ ?>