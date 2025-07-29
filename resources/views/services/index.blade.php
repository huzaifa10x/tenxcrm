<x-app-layout>
    <x-slot name="header">
        {{ __('Services') }}
    </x-slot>
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
                    @csrf
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
                $.get("{{ route('services.getServices') }}", function(data) {
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
                let url = id ? `{{ url('/services') }}/${id}` : `{{ route('services.store') }}`;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}',
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
                let url = `{{ url('/services') }}/${id}`;
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
                    let url = `{{ url('/services') }}/${id}`;
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            fetchServices();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
