
<div class="crm-card <?php echo e($deal->is_rotting ? 'is-rotting' : ''); ?>" data-deal-id="<?php echo e($deal->id); ?>"
     onclick="if(!window.__dragging) location.href='<?php echo e(route('pipedrive.deals.show', $deal->id)); ?>'">
    <div class="card-title"><?php echo e($deal->title); ?></div>
    <div class="card-sub">
        <?php if($deal->organization): ?>
            <i class="bi bi-building me-1"></i><?php echo e(Str::limit($deal->organization->name, 26)); ?>

        <?php elseif($deal->person): ?>
            <i class="bi bi-person me-1"></i><?php echo e(Str::limit($deal->person->name, 26)); ?>

        <?php else: ?>
            <span class="text-muted">No contact</span>
        <?php endif; ?>
    </div>
    <div class="card-foot">
        <span class="card-value"><?php echo e($deal->currency); ?> <?php echo e(number_format((float) $deal->value, 0)); ?></span>
        <span class="d-flex align-items-center gap-2">
            <?php if($deal->is_rotting): ?>
                <span class="crm-rot-flag" title="Idle — needs attention"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <?php endif; ?>
            <?php if($deal->label): ?>
                <span class="card-label" style="background:var(--ds-accent-soft);color:var(--ds-accent);"><?php echo e($deal->label); ?></span>
            <?php endif; ?>
            <span class="card-avatar" title="<?php echo e($deal->owner?->name); ?>">
                <?php echo e(strtoupper(Str::substr($deal->owner?->name ?? '?', 0, 1))); ?>

            </span>
        </span>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/deals/_card.blade.php ENDPATH**/ ?>