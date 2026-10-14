<?php
    $__name = $user->name ?? '?';
    $__initials = collect(preg_split('/\s+/', trim((string) $__name)))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') ?: '?';
?>
<span class="xa-avatar <?php echo e($class ?? ''); ?>" style="--hue: <?php echo e(crc32((string) $__name) % 360); ?>" aria-hidden="true">
    <?php if(!empty($user->img)): ?>
        <img src="<?php echo e(asset('/public/storage/' . $user->img)); ?>" alt="" loading="lazy" onerror="this.remove()">
    <?php endif; ?>
    <?php echo e($__initials); ?>

</span>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/partials/avatar.blade.php ENDPATH**/ ?>