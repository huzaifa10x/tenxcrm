<x-app-layout>
    <x-slot name="header">
        {{ __('Client Leads') }}
    </x-slot>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="website-selector" class="form-label">
                    <i class="bi bi-globe2"></i> Select Website
                </label>
                <select id="website-selector" class="form-select">
                    <option value="">-- Select Website --</option>
                    @foreach ($websites as $site)
                        <option value="{{ $site }}">{{ $site }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="leads-container"></div>
    </div>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script>
        const baseRoute = @json(route('leads.bySite', ['website_name' => '__PLACEHOLDER__']));

        $('#website-selector').on('change', function() {
            const selected = $(this).val();
            const container = $('#leads-container');

            if (!selected) {
                container.html('');
                return;
            }

            const finalUrl = baseRoute.replace('__PLACEHOLDER__', encodeURIComponent(selected));

            $.get(finalUrl, function(data) {
                if (data.length) {
                    let columns = Object.keys(data[0].form_data);
                    let html = `
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <i class="bi bi-person-lines-fill"></i> Leads for <strong>${selected}</strong>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>`;

                    columns.forEach(col => {
                        html += `<th>${col}</th>`;
                    });
                    html += `<th>Submitted At</th></tr></thead><tbody>`;

                    data.forEach(lead => {
                        html += '<tr>';
                        columns.forEach(col => {
                            html += `<td>${lead.form_data[col] ?? '-'}</td>`;
                        });
                        html += `<td><i class="bi bi-clock"></i> ${lead.created_at}</td></tr>`;
                    });

                    html += '</tbody></table></div></div>';
                    container.html(html);
                } else {
                    container.html(`
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            No leads found for this website.
                        </div>
                    `);
                }
            }).fail(() => {
                container.html(`
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i> Failed to fetch leads. Please try again later.
                    </div>
                `);
            });
        });
    </script>
</x-app-layout>
