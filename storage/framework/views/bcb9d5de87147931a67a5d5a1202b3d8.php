<div class="row gy-3 mb-10">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header show-daily-attendance-report">
                <h6 class="mb-2 fw-bold text-lg mb-0">Daily Attendance Report</h6>
            </div>
            <div class="card-body p-24 daily-attendance-report-wrapper table-responsive" style="display: none">
                <input placeholder="select date" type="date" id="attendanceDate" class="form-control mb-3">
                <table class="table bordered-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '.show-daily-attendance-report', function() {
            $('.daily-attendance-report-wrapper').slideToggle();
        })

        let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        $('#attendanceDate').val(today); // Set default value in date picker

        function fetchAttendance(date) {
            $.ajax({
                url: "<?php echo e(route('admin.attendance.filter')); ?>",
                type: "GET",
                data: {
                    date: date
                },
                success: function(response) {
                    let tbody = '';
                    if (response.attendance.length > 0) {
                        response.attendance.forEach(attendance => {
                            // Using 'attendance.status' instead of 'status_labels'
                            let status = attendance.status || 'Pending';

                            tbody += `
                         <tr>
                             <td>${attendance.user.name}</td>
                             <td>${attendance.date}</td>
                             <td>${attendance.punch_in || 'Not Available'}</td>
                             <td>${attendance.punch_out ?? 'Currently Working'}</td>
                             <td>${attendance.total_hours ?? 'Pending'}</td>
                             <td>${status}</td>
                         </tr>
                     `;
                        });
                    } else {
                        tbody =
                            `<tr><td colspan="6" class="text-center">No attendance records found.</td></tr>`;
                    }
                    $('#attendanceTable tbody').html(tbody);
                },
                error: function(response) {
                    $('#attendanceTable tbody').html(
                        `<tr><td colspan="6" class="text-center">This is a holiday or weekend.</td></tr>`
                    );
                }
            });
        }

        fetchAttendance(today);

        $('#attendanceDate').on('change', function() {
            let selectedDate = $(this).val();
            fetchAttendance(selectedDate);
        });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/admin-dashboard.blade.php ENDPATH**/ ?>