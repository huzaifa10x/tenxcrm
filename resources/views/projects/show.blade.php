

<div class="col-lg-12">
    <label class="form-label" for="contract_copy">Contract Copy</label>
    @if (!empty($project->contractFiles) && count($project->contractFiles) > 0)
        <div class="row">
            @foreach ($project->contractFiles as $file)
                @php
                    $filePath = asset('storage/' . $file->file_path);
                    $extension = pathinfo($file->file_type, PATHINFO_EXTENSION);
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                @endphp

                @if (in_array($extension, $imageExtensions))
                    {{-- Image Files --}}
                    <div class="col-lg-6 mb-3">
                        <div
                            class="card overflow-hidden d-flex justify-content-center align-items-center bg-light h-100 border shadow-sm">
                            <img style="width: 100px;" src="{{ $filePath }}" class="card-img-top img-fluid"
                                alt="Contract Image">
                            <div class="card-body text-center">
                                <a href="{{ $filePath }}" target="_blank"
                                    class="btn fs-6 px-3 py-2 btn-sm btn-primary">View Full</a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Non-Image Files --}}
                    <div class="col-lg-6 mb-3">
                        <div
                            class="position-relative card overflow-hidden d-flex justify-content-center align-items-center bg-light border h-100 shadow-sm text-center p-3">
                            <div class="badge position-absolute top-0 end-0 bg-primary text-white">
                                {{ strtoupper($extension) }}
                            </div>
                            <div class="mb-2">
                                <img src="{{ asset('/images/doc.png') }}" width="50" height="50"
                                    alt="{{ strtoupper($extension) }} File">
                            </div>
                            <p class="mb-1 text-dark">{{ basename($file) }}</p>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ $filePath }}" target="_blank"
                                    class="btn fs-6 px-3 py-2 btn-sm btn-primary">View</a>
                                <a href="{{ $filePath }}" download
                                    class="btn fs-6 px-3 py-2 btn-sm btn-success">Download</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p>No contract files attached.</p>
    @endif
</div>
