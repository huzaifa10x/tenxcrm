
<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['text']); ?>; margin-bottom: 10px;"><?php echo e($heading); ?></div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="padding: 11px 0; width: 42%; color: <?php echo e($c['muted']); ?>;<?php echo e($loop->last ? '' : ' border-bottom: 1px solid ' . $c['border'] . ';'); ?>"><?php echo e($label); ?></td>
                    <td style="padding: 11px 0; font-weight: 600; color: <?php echo e($c['text']); ?>;<?php echo e($loop->last ? '' : ' border-bottom: 1px solid ' . $c['border'] . ';'); ?>"><?php echo e($value); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </td>
</tr>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/partials/details.blade.php ENDPATH**/ ?>