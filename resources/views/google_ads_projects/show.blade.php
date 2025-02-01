<x-app-layout>
    <x-slot name="header">
        {{ __('Google Ads Project - ' . $googleAdsProject->name) }}
    </x-slot>
    <div class="card ">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-lg-6">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->category }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="name">Project Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->name }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="industry_id">Industry</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->industry->industry }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="status">Status</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->status }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="sales_manager_id">Sales Manager</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->salesManager->name }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="account_manager_id">Account Manager</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ $googleAdsProject->accountManager->name }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_start_date">Contract Start Date</label>
                    <input type="date" name="contract_start_date" id="contract_start_date" class="form-control"
                        value="{{ $googleAdsProject->contract_start_date }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_term">Contract Term (in months)</label>
                    <input type="number" name="contract_term" id="contract_term" class="form-control"
                        value="{{ $googleAdsProject->contract_term }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="contract_value">Contract Value</label>
                    <input type="number" step="0.01" name="contract_value" id="contract_value" class="form-control"
                        value="{{ $googleAdsProject->contract_value }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="free_month">Free Month</label>
                    <input type="number" name="free_month" id="free_month" class="form-control"
                        value="{{ $googleAdsProject->free_month }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="customer_id">Customer</label>
                    <input type="text" name="free_month" id="free_month" class="form-control"
                        value="{{ $googleAdsProject->customer->name }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="payment_method">Payment Method</label>
                    <input type="text" name="free_month" id="free_month" class="form-control"
                        value="{{ $googleAdsProject->payment_method }}" required>
                </div>

                <div class="col-lg-6">
                    <label class="form-label" for="handover_notes">Handover Notes</label>
                    <textarea name="handover_notes" id="handover_notes" class="form-control">{{ $googleAdsProject->handover_notes }}</textarea>
                </div>
                <div class="col-lg-12">
                    <label class="form-label" for="contract_copy">Contract Copy</label>
                    @if (!empty($googleAdsProject->contract_copy) && count($googleAdsProject->contract_copy) > 0)
                        <div class="row">
                            @foreach ($googleAdsProject->contract_copy as $file)
                                @php
                                    $filePath = asset('storage/' . $file);
                                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                @endphp

                                @if (in_array($extension, $imageExtensions))
                                    {{-- Image Files --}}
                                    <div class="col-lg-6 mb-3">
                                        <div class="card overflow-hidden d-flex justify-content-center align-items-center bg-light h-100 border shadow-sm">
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
                                                <img src="{{ asset('/images/doc.png') }}" width="50"
                                                    height="50" alt="{{ strtoupper($extension) }} File">
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
            </div>
        </div>
    </div>
</x-app-layout>
