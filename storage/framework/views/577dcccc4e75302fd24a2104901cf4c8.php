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
        <?php echo e(__('Lead Sources')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card basic-data-table">
        <div class="card-header">
            <form id="sourceForm" class="d-flex align-items-center justify-content-between gap-10">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="source_id">
                <input type="text" name="name" id="name" placeholder="Source Name" class="form-control mb-2">
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="sourcesTable" data-page-length='10'>
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
    </div>

    <script>
        $(document).ready(function() {
            fetchSources();

            function fetchSources() {
                $.get("<?php echo e(route('lead_sources.getSources')); ?>", function(data) {
                    let rows = '';
                    $.each(data, function(i, source) {
                        rows += `
                            <tr>
                                <td>${source.id}</td>
                                <td>${source.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn" data-id="${source.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${source.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#sourcesTable tbody').html(rows);
                });
            }

            $('#sourceForm').submit(function(e) {
                e.preventDefault();
                let id = $('#source_id').val();
                let name = $('#name').val();
                let method = id ? 'PUT' : 'POST';
                let url = id ? `<?php echo e(url('/lead-sources')); ?>/${id}` : `<?php echo e(route('lead_sources.store')); ?>`;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: name,
                        _token: '<?php echo e(csrf_token()); ?>',
                        _method: method
                    },
                    success: function() {
                        $('#sourceForm')[0].reset();
                        $('#source_id').val('');
                        fetchSources();
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let url = `<?php echo e(url('/lead-sources')); ?>/${id}`;
                $.get(url, function(data) {
                    $('#source_id').val(data.id);
                    $('#name').val(data.name);
                });
            });

            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm('Delete this source?')) {
                    let url = `<?php echo e(url('/lead-sources')); ?>/${id}`;
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function() {
                            fetchSources();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/lead_sources/index.blade.php ENDPATH**/ ?>