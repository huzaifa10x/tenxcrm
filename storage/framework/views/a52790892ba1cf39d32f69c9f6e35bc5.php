<?php if (isset($component)) { $__componentOriginal78f1264da9b3dd3bd04f13df7c14e65f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal78f1264da9b3dd3bd04f13df7c14e65f = $attributes; } ?>
<?php $component = App\View\Components\PublicInvoice::resolve(['isEmail' => 0,'invoice' => $invoice] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public-invoice'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PublicInvoice::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal78f1264da9b3dd3bd04f13df7c14e65f)): ?>
<?php $attributes = $__attributesOriginal78f1264da9b3dd3bd04f13df7c14e65f; ?>
<?php unset($__attributesOriginal78f1264da9b3dd3bd04f13df7c14e65f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal78f1264da9b3dd3bd04f13df7c14e65f)): ?>
<?php $component = $__componentOriginal78f1264da9b3dd3bd04f13df7c14e65f; ?>
<?php unset($__componentOriginal78f1264da9b3dd3bd04f13df7c14e65f); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/invoices/public.blade.php ENDPATH**/ ?>