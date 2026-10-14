<div class="col-xxl-6">
    <div class="card h-100">
        <div class="card-header border-bottom-0 pb-0 d-flex align-items-center flex-wrap gap-2 justify-content-between">
            <h6 id="card-title" class="mb-2 fw-bold text-lg mb-0">Today Tasks</h6>
            <select id="taskFilter"
                class="form-select form-select-sm w-auto bg-base border text-secondary-light radius-8">
                <option value="today" selected>Today</option>
                <option value="weekly">Last Week</option>
                <option value="monthly">Last Month</option>
                <option value="yearly">Last Year</option>
            </select>

        </div>
        <div class="card-body">
            <div class="position-relative h-100 min-h-320-px">
                <div class="row g-3 h-100">
                    <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                        <div id="pendingBar"
                            class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-warning"
                            style="min-height: <?php echo e($taskPercentages['pending']); ?>%;">
                            <span
                                class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-warning-600 rounded-circle mb-12">
                                <h6 id="pendingCount" class="mb-0 text-base">
                                    <?php echo e($pendingTasksCountGlobal); ?>

                                </h6>
                            </span>
                            <span class="text-secondary-light">Pending</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                        <div id="completedBar"
                            class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-lilac"
                            style="min-height: <?php echo e($taskPercentages['completed']); ?>%;">
                            <span
                                class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-lilac-600 rounded-circle mb-12">
                                <h6 id="completedCount" class="mb-0 text-base">
                                    <?php echo e($completedTasksCountGlobal); ?>

                                </h6>
                            </span>
                            <span class="text-secondary-light">Completed</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                        <div id="createdBar"
                            class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-primary"
                            style="min-height: <?php echo e($taskPercentages['created']); ?>%;">
                            <span
                                class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-primary-600 rounded-circle mb-12">
                                <h6 id="createdCount" class="mb-0 text-base">
                                    <?php echo e($createdTasksCountGlobal); ?>

                                </h6>
                            </span>
                            <span class="text-secondary-light">Assigned</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                        <div id="collaboratorBar"
                            class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-success"
                            style="min-height: <?php echo e($taskPercentages['collaborator']); ?>%;">
                            <span
                                class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-success-600 rounded-circle mb-12">
                                <h6 id="collaboratorCount" class="mb-0 text-base">
                                    <?php echo e($collaboratorTasksCountGlobal); ?></h6>
                            </span>
                            <span class="text-secondary-light">Collaborator</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#taskFilter').change(function() {
            let filter = $(this).val();
            let selectedText = $('#taskFilter option:selected').text();
            $('#card-title').text(selectedText + " Tasks");

            $.ajax({
                url: "<?php echo e(route('dashboard')); ?>",
                type: "GET",
                data: {
                    filter: filter
                },
                success: function(response) {
                    // Update task counts
                    $('#pendingCount').text(response.pendingTasksCountGlobal);
                    $('#completedCount').text(response.completedTasksCountGlobal);
                    $('#createdCount').text(response.createdTasksCountGlobal);
                    $('#collaboratorCount').text(response.collaboratorTasksCountGlobal);

                    // Update bar heights based on new percentages
                    $('#pendingBar').css('min-height', response.taskPercentages.pending +
                        '%');
                    $('#completedBar').css('min-height', response.taskPercentages
                        .completed + '%');
                    $('#createdBar').css('min-height', response.taskPercentages.created +
                        '%');
                    $('#collaboratorBar').css('min-height', response.taskPercentages
                        .collaborator + '%');
                }
            });
        });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/task-counts.blade.php ENDPATH**/ ?>