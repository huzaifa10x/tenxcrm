
<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: <?php echo e($c['primary']); ?>; border-radius: 10px;">
                    <a href="<?php echo e($url); ?>" target="_blank" style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: <?php echo e($c['dark']); ?>; text-decoration: none; letter-spacing: 0.3px;"><?php echo e($label); ?> &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: <?php echo e($c['muted']); ?>;">
            Button not working? <a href="<?php echo e($url); ?>" style="color: <?php echo e($c['primary_dark']); ?>; word-break: break-all;"><?php echo e($url); ?></a>
        </div>
        <?php if(!empty($note)): ?>
            <div style="margin-top: 16px; font-size: 12px; line-height: 18px; color: <?php echo e($c['muted']); ?>;"><?php echo $note; ?></div>
        <?php endif; ?>
    </td>
</tr>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/partials/cta.blade.php ENDPATH**/ ?>