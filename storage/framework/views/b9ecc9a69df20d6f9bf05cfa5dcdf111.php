
                            </table>
                        </td>
                    </tr>
                    

                    
                    <tr>
                        <td style="padding-top: 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: <?php echo e($c['dark']); ?>; border-radius: 16px;">
                                <tr>
                                    <td class="px" style="padding: 28px 36px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding-bottom: 16px; border-bottom: 1px solid <?php echo e($c['on_dark_line']); ?>;">
                                                    <div style="font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px;"><?php echo e($b['company']); ?></div>
                                                    <div style="font-size: 13px; color: <?php echo e($c['primary']); ?>; margin-top: 4px; font-weight: 600;"><?php echo e($b['tagline']); ?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px; font-size: 13px; line-height: 22px; color: <?php echo e($c['on_dark_soft']); ?>;">
                                                    <?php echo e($b['address']); ?><br>
                                                    <a href="tel:<?php echo e(preg_replace('/\s+/', '', $b['phone'])); ?>" style="color: <?php echo e($c['on_dark_soft']); ?>; text-decoration: none;"><?php echo e($b['phone']); ?></a>
                                                    &nbsp;&middot;&nbsp;
                                                    <a href="mailto:<?php echo e($b['email']); ?>" style="color: <?php echo e($c['on_dark_soft']); ?>; text-decoration: none;"><?php echo e($b['email']); ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 14px; font-size: 13px;">
                                                    <?php $__currentLoopData = $b['socials']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a href="<?php echo e($link); ?>" target="_blank" style="color: <?php echo e($c['primary']); ?>; text-decoration: none; font-weight: 600;"><?php echo e($name); ?></a><?php if(!$loop->last): ?><span style="color: <?php echo e($c['on_dark_sep']); ?>;">&nbsp;&nbsp;|&nbsp;&nbsp;</span><?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <span style="color: <?php echo e($c['on_dark_sep']); ?>;">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                                    <a href="<?php echo e($b['website']); ?>" target="_blank" style="color: <?php echo e($c['primary']); ?>; text-decoration: none; font-weight: 600;">Website</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 18px 20px 0 20px; font-size: 12px; line-height: 18px; color: <?php echo e($c['muted']); ?>;">
                            This is an automated notification from <strong><?php echo e($b['app_name']); ?></strong>, <?php echo e($b['company']); ?>'s internal task manager.<br>
                            <a href="<?php echo e($dashboardUrl); ?>" style="color: <?php echo e($c['primary_dark']); ?>; text-decoration: underline;">Open your task board</a>
                            &nbsp;&middot;&nbsp; &copy; <?php echo e(date('Y')); ?> <?php echo e($b['company']); ?>. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/partials/footer.blade.php ENDPATH**/ ?>