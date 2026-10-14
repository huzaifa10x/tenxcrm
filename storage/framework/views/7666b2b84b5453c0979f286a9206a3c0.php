<?php
    $c = $b['colors'];
    $row = 'padding: 11px 0; border-bottom: 1px solid ' . $c['border'] . ';';
    $label = 'width: 42%; color: ' . $c['muted'] . ';';
    $val = 'font-weight: 600; color: ' . $c['text'] . ';';
    $section =
        'font-size: 13px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: ' .
        $c['text'] .
        '; margin-bottom: 10px;';
?>

<?php echo $__env->make('emails.partials.header', [
    'label' => 'Cancellation Request',
    'preheader' =>
        $requesterName . ' requested to cancel ' . $accountName . ' (' . $accountMrr . '/mo). Review needed.',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<tr>
    <td style="height: 6px; line-height: 6px; font-size: 0; background-color: <?php echo e($c['danger']); ?>;">&nbsp;</td>
</tr>


<tr>
    <td class="px" style="padding: 36px 40px 8px 40px; background-color: <?php echo e($c['dark']); ?>;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td
                    style="width: 44px; height: 44px; background-color: <?php echo e($c['danger']); ?>; border-radius: 12px; text-align: center; vertical-align: middle; font-size: 22px; line-height: 44px; color: #ffffff; font-weight: 800;">
                    !</td>
                <td style="padding-left: 14px;">
                    <div
                        style="font-size: 12px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: <?php echo e($c['danger']); ?>;">
                        Action Required</div>
                    <div style="font-size: 13px; color: <?php echo e($c['on_dark_muted']); ?>; margin-top: 2px;"><?php echo e($requestDate); ?>

                        at <?php echo e($requestTime); ?> (<?php echo e($timezone); ?>)</div>
                </td>
            </tr>
        </table>
        <h1 class="hero-title"
            style="margin: 22px 0 8px 0; font-size: 26px; line-height: 34px; font-weight: 800; color: #ffffff;">Hi
            <?php echo e($adminName); ?>, a cancellation needs your review.</h1>
        <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 24px; color: <?php echo e($c['on_dark_soft']); ?>;">
            <strong style="color: #ffffff;"><?php echo e($requesterName); ?></strong> submitted a request to cancel <strong
                style="color: #ffffff;"><?php echo e($accountName); ?></strong>. Nothing changes until you approve it.
        </p>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 32px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="border: 1px solid <?php echo e($c['border']); ?>; border-left: 5px solid <?php echo e($c['danger']); ?>; border-radius: 12px;">
            <tr>
                <td style="padding: 22px 24px;">
                    <div
                        style="font-size: 12px; font-weight: 700; color: <?php echo e($c['muted']); ?>; letter-spacing: 1px; font-family: Consolas, Monaco, monospace;">
                        <?php echo e($accountId); ?></div>
                    <div
                        style="font-size: 20px; line-height: 28px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin: 6px 0 4px 0;">
                        <?php echo e($accountName); ?></div>
                    <?php if($clientName): ?>
                        <div style="font-size: 14px; color: <?php echo e($c['muted']); ?>; margin-bottom: 14px;">
                            <?php echo e($clientName); ?></div>
                    <?php else: ?>
                        <div style="height: 10px; line-height: 10px; font-size: 0;">&nbsp;</div>
                    <?php endif; ?>
                    <span
                        style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #ffffff; background-color: <?php echo e($c['danger']); ?>; border-radius: 50px;">Cancellation
                        Pending</span>
                    <span
                        style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['primary_dark']); ?>; background-color: <?php echo e($c['primary_light']); ?>; border-radius: 50px;"><?php echo e($planName); ?></span>
                    <?php if($isHighValue): ?>
                        <span
                            style="display: inline-block; padding: 5px 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: <?php echo e($c['dark']); ?>; background-color: <?php echo e($c['warning']); ?>; border-radius: 50px;">&#9733;
                            High Value</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 20px 40px 0 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="background-color: <?php echo e($c['bg']); ?>; border-radius: 12px;">
            <tr>
                <td class="stack stack-first" width="33%" style="padding: 18px 12px; text-align: center;">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">
                        Monthly Value</div>
                    <div style="font-size: 17px; font-weight: 800; color: <?php echo e($c['danger']); ?>; margin-top: 6px;">
                        <?php echo e($accountMrr); ?></div>
                </td>
                <td class="stack" width="34%"
                    style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">
                        Client For</div>
                    <div style="font-size: 16px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 6px;">
                        <?php echo e($tenure ?? 'N/A'); ?></div>
                </td>
                <td class="stack" width="33%"
                    style="padding: 18px 12px; text-align: center; border-left: 1px solid <?php echo e($c['border']); ?>;">
                    <div
                        style="font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>;">
                        Open Tasks</div>
                    <div style="font-size: 20px; font-weight: 800; color: <?php echo e($c['text']); ?>; margin-top: 4px;">
                        <?php echo e($openTasks); ?></div>
                </td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="<?php echo e($section); ?>">Request</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Requested By</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($requesterName); ?><br><a
                        href="mailto:<?php echo e($requesterEmail); ?>"
                        style="font-size: 12px; font-weight: 400; color: <?php echo e($c['primary_dark']); ?>; text-decoration: none;"><?php echo e($requesterEmail); ?></a>
                </td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Role</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($requesterRole); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; <?php echo e($label); ?>">Submitted</td>
                <td style="padding: 11px 0; <?php echo e($val); ?>"><?php echo e($requestDate); ?> at <?php echo e($requestTime); ?>

                    <?php echo e($timezone); ?></td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="<?php echo e($section); ?>">Account</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Plan / Service</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($planName); ?></td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Contract Value</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($contractValue); ?></td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Monthly Value</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($accountMrr); ?></td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Client Since</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($startDate); ?></td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Current Contract Ends</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($contractEnd); ?></td>
            </tr>
            <tr>
                <td style="<?php echo e($row); ?> <?php echo e($label); ?>">Project Status</td>
                <td style="<?php echo e($row); ?> <?php echo e($val); ?>"><?php echo e($projectStatus); ?></td>
            </tr>
            <tr>
                <td style="padding: 11px 0; <?php echo e($label); ?>">Account Owner (AM)</td>
                <td style="padding: 11px 0; <?php echo e($val); ?>"><?php echo e($accountManager); ?></td>
            </tr>
        </table>
    </td>
</tr>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="<?php echo e($section); ?>">Cancellation Details</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size: 14px;">
            <tr>
                <td style="padding: 11px 0; <?php echo e($label); ?>">Requested Effective Date</td>
                <td style="padding: 11px 0; <?php echo e($val); ?>"><?php echo e($effectiveDate); ?></td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="background-color: <?php echo e($c['danger_light']); ?>; border-radius: 12px; margin-top: 8px;">
            <tr>
                <td style="padding: 18px 20px;">
                    <div
                        style="font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['danger']); ?>; margin-bottom: 8px;">
                        Reason Given</div>
                    <div style="font-size: 14px; line-height: 22px; color: <?php echo e($c['text']); ?>;"><?php echo e($reason); ?>

                    </div>
                </td>
            </tr>
        </table>
        <?php if($additionalNotes): ?>
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                style="background-color: <?php echo e($c['bg']); ?>; border-radius: 12px; margin-top: 12px;">
                <tr>
                    <td style="padding: 18px 20px;">
                        <div
                            style="font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: <?php echo e($c['muted']); ?>; margin-bottom: 8px;">
                            Notes</div>
                        <div style="font-size: 14px; line-height: 22px; color: <?php echo e($c['text']); ?>;">
                            <?php echo e($additionalNotes); ?></div>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    </td>
</tr>


<?php if($isHighValue): ?>
    <tr>
        <td class="px" style="padding: 20px 40px 0 40px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                style="background-color: <?php echo e($c['warning_light']); ?>; border-left: 4px solid <?php echo e($c['warning']); ?>; border-radius: 8px;">
                <tr>
                    <td style="padding: 14px 18px; font-size: 14px; line-height: 21px; color: <?php echo e($c['text']); ?>;">
                        <strong style="color: #B7791F;">High-value account.</strong> At <?php echo e($accountMrr); ?>/month,
                        loop in <strong><?php echo e($accountManager); ?></strong> for a retention attempt before approving.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php endif; ?>


<tr>
    <td class="px" style="padding: 28px 40px 0 40px;">
        <div style="<?php echo e($section); ?>">Next Steps</div>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
            style="font-size: 14px; line-height: 22px; color: <?php echo e($c['text']); ?>;">
            <?php $__currentLoopData = ['Review the request and confirm the account details above.', 'If this is a high-value or at-risk account, loop in ' . $accountManager . ' for a retention attempt before processing.', 'Approve or decline the cancellation in 10X One.' . ($openTasks > 0 ? ' Approving will block ' . $openTasks . ' open task(s).' : '')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td valign="top" style="width: 34px; padding: 6px 0;">
                        <div
                            style="width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: <?php echo e($c['primary_light']); ?>; color: <?php echo e($c['primary_dark']); ?>; font-size: 12px; font-weight: 800; text-align: center;">
                            <?php echo e($i + 1); ?></div>
                    </td>
                    <td valign="top" style="padding: 7px 0;"><?php echo e($step); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </td>
</tr>


<tr>
    <td class="px" align="center" style="padding: 32px 40px 36px 40px;">
        <table cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="background-color: <?php echo e($c['primary']); ?>; border-radius: 10px;">
                    <a href="<?php echo e($url); ?>" target="_blank"
                        style="display: inline-block; padding: 15px 38px; font-size: 15px; font-weight: 800; color: <?php echo e($c['dark']); ?>; text-decoration: none; letter-spacing: 0.3px;">Review
                        Request &rarr;</a>
                </td>
            </tr>
        </table>
        <div style="margin-top: 14px; font-size: 12px; color: <?php echo e($c['muted']); ?>;">
            Button not working? <a href="<?php echo e($url); ?>"
                style="color: <?php echo e($c['primary_dark']); ?>; word-break: break-all;"><?php echo e($url); ?></a>
        </div>
    </td>
</tr>

<?php echo $__env->make('emails.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/emails/projects/cancellation-requested.blade.php ENDPATH**/ ?>