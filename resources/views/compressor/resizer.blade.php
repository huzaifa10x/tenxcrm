<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pro Image Resizer') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form action="{{ route('image.resize') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">WIDTH (PX)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="bi bi-arrows-expand"></i></span>
                                        <input type="number" name="width" class="form-control form-control-lg"
                                            value="120">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small">HEIGHT (PX)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="bi bi-arrows-vertical"></i></span>
                                        <input type="number" name="height" class="form-control form-control-lg"
                                            value="120">
                                    </div>
                                </div>
                            </div>

                            <div
                                class="upload-zone p-5 border border-2 border-dashed rounded-3 text-center bg-light mb-4 position-relative">
                                <input type="file" name="images[]"
                                    class="position-absolute opacity-0 w-100 h-100 top-0 start-0"
                                    style="cursor: pointer;" multiple required id="imageInput">
                                <div id="upload-placeholder">
                                    <i class="display-4 bi bi-cloud-arrow-up text-primary"></i>
                                    <p class="mt-2 mb-0 fw-semibold">Click to upload or drag and drop</p>
                                    <p class="text-muted small">PNG, JPG or WebP (Max 2MB per file)</p>
                                </div>
                                <div id="file-count" class="mt-2 fw-bold text-success" style="display:none;"></div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                <i class="bi bi-magic me-2"></i>Resize Images
                            </button>
                        </form>
                    </div>
                </div>

                @if (session('urls'))
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Processed Images ({{ count(session('urls')) }})</h6>
                            <button onclick="downloadAll()" class="btn btn-sm btn-success px-3">
                                <i class="bi bi-download me-2"></i>Download All
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach (session('urls') as $file)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="position-relative group">
                                            <img src="{{ $file['url'] }}" class="img-fluid rounded border shadow-sm">
                                            <a href="{{ $file['url'] }}" download="{{ $file['original'] }}"
                                                class="btn btn-dark btn-sm position-absolute bottom-0 end-0 m-1 opacity-75">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Update file count text when selected
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const count = e.target.files.length;
            const placeholder = document.getElementById('upload-placeholder');
            const countDiv = document.getElementById('file-count');
            if (count > 0) {
                placeholder.style.display = 'none';
                countDiv.style.display = 'block';
                countDiv.innerText = count + ' images selected';
            }
        });

        // Loop through all images and trigger individual downloads
        function downloadAll() {
            const links = document.querySelectorAll('a[download]');
            links.forEach((link, index) => {
                setTimeout(() => {
                    link.click();
                }, index * 200); // Small delay to prevent browser block
            });
        }
    </script>

    <style>
        .upload-zone:hover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff !important;
        }

        .resizer-preview {
            transition: transform 0.2s;
            height: 120px;
            width: 100%;
            object-fit: contain;
            background: #eee;
        }

        .resizer-preview:hover {
            transform: scale(1.05);
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</x-app-layout>
