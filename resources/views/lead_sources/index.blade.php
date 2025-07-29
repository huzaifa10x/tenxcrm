<x-app-layout>
    <x-slot name="header">
        {{ __('Lead Sources') }}
    </x-slot>
    <div class="card basic-data-table">
        <div class="card-header">
            <form id="sourceForm" class="d-flex align-items-center justify-content-between gap-10">
                @csrf
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
                $.get("{{ route('lead_sources.getSources') }}", function(data) {
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
                let url = id ? `{{ url('/lead-sources') }}/${id}` : `{{ route('lead_sources.store') }}`;

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}',
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
                let url = `{{ url('/lead-sources') }}/${id}`;
                $.get(url, function(data) {
                    $('#source_id').val(data.id);
                    $('#name').val(data.name);
                });
            });

            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm('Delete this source?')) {
                    let url = `{{ url('/lead-sources') }}/${id}`;
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            fetchSources();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
