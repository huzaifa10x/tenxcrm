<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Image Converter</h2>
     <?php $__env->endSlot(); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <form action="<?php echo e(route('image.convert')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="text-center mb-4">
                            <label class="form-label d-block fw-bold text-muted small mb-3">CONVERT TO:</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="target_format" id="webp"
                                    value="webp" checked>
                                <label class="btn btn-outline-primary py-2" for="webp">WEBP</label>

                                <input type="radio" class="btn-check" name="target_format" id="png"
                                    value="png">
                                <label class="btn btn-outline-primary py-2" for="png">PNG</label>

                                <input type="radio" class="btn-check" name="target_format" id="jpg"
                                    value="jpg">
                                <label class="btn btn-outline-primary py-2" for="jpg">JPG</label>
                            </div>
                        </div>

                        <div
                            class="upload-zone p-5 border border-2 border-dashed rounded-3 text-center bg-light mb-4 position-relative">
                            <input type="file" name="images[]" id="imageInput"
                                class="position-absolute opacity-0 w-100 h-100 top-0 start-0" style="cursor: pointer;"
                                multiple required>
                            <div id="placeholder">
                                <i class="bi bi-file-earmark-arrow-up display-5 text-primary"></i>
                                <p class="mt-2 mb-0">Drop PNG/JPG files here or click to browse</p>
                            </div>
                            <div id="file-list" class="mt-2 fw-bold text-primary" style="display:none;"></div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Start Conversion <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>

                <?php if(session('urls')): ?>
                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold m-0">Converted Files</h5>
                            <button onclick="downloadAll()" class="btn btn-success btn-sm px-4 rounded-pill">
                                <i class="bi bi-cloud-download me-2"></i>Download All
                            </button>
                        </div>
                        <div class="row g-3">
                            <?php $__currentLoopData = session('urls'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                        <div class="bg-light d-flex align-items-center justify-content-center p-3"
                                            style="height: 140px;">
                                            <img src="<?php echo e(asset('/public/' . $url)); ?>" class="img-fluid rounded shadow-sm"
                                                style="max-height: 100%;">
                                        </div>
                                        <div class="card-footer bg-white border-0 text-center pb-3">
                                            <a href="<?php echo e(asset('/public/' . $url)); ?>" download
                                                class="btn btn-link btn-sm text-decoration-none text-muted">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Simple UI feedback
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const count = e.target.files.length;
            if (count > 0) {
                document.getElementById('placeholder').style.display = 'none';
                document.getElementById('file-list').style.display = 'block';
                document.getElementById('file-list').innerText = count + ' images ready to convert';
            }
        });

        // Trigger individual downloads sequentially
        function downloadAll() {
            const links = document.querySelectorAll('a[download]');
            links.forEach((link, index) => {
                setTimeout(() => link.click(), index * 250);
            });
        }
    </script>

    <style>
        .upload-zone:hover {
            border-color: #0d6efd !important;
            background-color: #f1f6ff !important;
        }

        .btn-check:checked+.btn-outline-primary {
            background-color: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/compressor/converter.blade.php ENDPATH**/ ?>