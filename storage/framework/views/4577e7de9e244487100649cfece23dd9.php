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
        <?php echo e(__('Services')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card basic-data-table">
        <div class="card-body row">
            <div class="col-lg-6 table-responsive">
                <table class="table bordered-table mb-0" id="servicesTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="col-lg-6" id="edit-form-wrapper">
                <form id="serviceForm" class="d-flex align-items-center justify-content-between gap-10">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="service_id">
                    <input type="text" name="name" id="name" placeholder="Service Name"
                        class="form-control mb-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            fetchServices();

            function fetchServices() {
                $.get("<?php echo e(route('services.getServices')); ?>", function(data) {
                    let rows = '';
                    $.each(data, function(i, service) {
                        rows += `
                            <tr>
                                <td>${service.id}</td>
                                <td>${service.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn" data-id="${service.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${service.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#servicesTable tbody').html(rows);
                });
            }

            $('#serviceForm').submit(function(e) {
                e.preventDefault();
                let id = $('#service_id').val();
                let name = $('#name').val();
                let method = id ? 'PUT' : 'POST';
                let url = id ? `<?php echo e(url('/services')); ?>/${id}` : `<?php echo e(route('services.store')); ?>`;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: name,
                        _token: '<?php echo e(csrf_token()); ?>',
                        _method: method
                    },
                    success: function() {
                        $('#serviceForm')[0].reset();
                        $('#service_id').val('');
                        fetchServices();
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let url = `<?php echo e(url('/services')); ?>/${id}`;
                $.get(url, function(data) {
                    $('#service_id').val(data.id);
                    $('#name').val(data.name);
                });
                $('html, body').animate({
                    scrollTop: $('#edit-form-wrapper').offset().top
                }, 500);
            });

            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm('Delete this service?')) {
                    let url = `<?php echo e(url('/services')); ?>/${id}`;
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function() {
                            fetchServices();
                        }
                    });
                }
            });
        });
    </script>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/services/index.blade.php ENDPATH**/ ?>