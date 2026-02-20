<x-app-layout>
    <x-slot name="header">
        Image Compressor
    </x-slot>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="compress-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="images" class="form-label fw-semibold">
                            <i class="bi bi-images"></i> Select Images
                        </label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple required>
                    </div>

                    <div class="mb-3">
                        <label for="target_size" class="form-label fw-semibold">
                            <i class="bi bi-arrows-collapse"></i> Target Size (KB)
                        </label>
                        <input type="number" class="form-control" id="target_size" name="target_size" min="10"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-compress"></i> Compress
                    </button>
                </form>
                <div id="result" class="mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        // Helper function to trigger multiple downloads
        function downloadAll(files) {
            files.forEach((file, index) => {
                // Small timeout prevents some browsers from blocking multiple concurrent downloads
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = file.url;
                    link.download = file.original;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 200);
            });
        }
        $('#compress-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#result').html(`
                                <div class="alert alert-info d-flex align-items-start" role="alert">
                                        <i class="bi bi-hourglass-split me-3 fs-4"></i>
                                        <div>
                                            <strong>Compressing your images...</strong><br>
                                            This may take <strong>3 to 4 minutes</strong> depending on the size and number of files.
                                            Please don’t close the page.
                                        </div>
                                </div>
                            `);

            $.ajax({
                url: "{{ route('compress.run') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.files.length > 0) {
                        let html = `
                       <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
                            <span><i class="bi bi-check-circle me-2"></i> Images compressed!</span>
                           ${res.files.length > 1 ? `
        <button type="button" class="btn btn-success btn-sm" id="btn-download-all">
            <i class="bi bi-archive"></i> Download All
        </button>
        ` : ''}
                        </div>
                        <ul class="list-group mt-3">
                    `;
                        res.files.forEach(file => {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                ${file.original}
                                <a href="${file.url}" download="${file.original}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </li>
                        `;
                        });
                        html += '</ul>';
                        $('#result').html(html);
                        $('#btn-download-all').on('click', function() {
                            downloadAll(res.files);
                        });

                    } else {
                        $('#result').html(`
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-circle"></i> No files were compressed.
                        </div>
                    `);
                    }
                },
                error: function() {
                    $('#result').html(`
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-x-circle me-2"></i>
                            Something went wrong while compressing your images.
                        </div>
                    `);
                }
            });
        });
    </script>
</x-app-layout>
