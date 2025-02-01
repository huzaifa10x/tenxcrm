<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Google Ads Project - ' . $googleAdsProject->name) }}
    </x-slot>
    @if ($userPermissions->contains('can manage google-ads-projects'))
        <form action="{{ route('google-ads-projects.update', $googleAdsProject->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="PLATINUM"
                                    {{ $googleAdsProject->category == 'PLATINUM' ? 'selected' : '' }}>
                                    PLATINUM
                                </option>
                                <option value="GOLD" {{ $googleAdsProject->category == 'GOLD' ? 'selected' : '' }}>
                                    GOLD
                                </option>
                                <option value="SILVER" {{ $googleAdsProject->category == 'SILVER' ? 'selected' : '' }}>
                                    SILVER</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="name">Project Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $googleAdsProject->name }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="industry_id">Industry</label>
                            <select name="industry_id" id="industry_id" class="form-control" required>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}"
                                        {{ $googleAdsProject->industry_id == $industry->id ? 'selected' : '' }}>
                                        {{ $industry->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Current" {{ $googleAdsProject->status == 'Current' ? 'selected' : '' }}>
                                    Current</option>
                                <option value="Paused" {{ $googleAdsProject->status == 'Paused' ? 'selected' : '' }}>
                                    Paused
                                </option>
                                <option value="WFF" {{ $googleAdsProject->status == 'WFF' ? 'selected' : '' }}>WFF
                                </option>
                                <option value="Expired" {{ $googleAdsProject->status == 'Expired' ? 'selected' : '' }}>
                                    Expired</option>
                                <option value="Debt Collection"
                                    {{ $googleAdsProject->status == 'Debt Collection' ? 'selected' : '' }}>
                                    Debt Collection</option>
                                <option value="Cancelled"
                                    {{ $googleAdsProject->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="sales_manager_id">Sales Manager</label>
                            <select name="sales_manager_id" id="sales_manager_id" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $googleAdsProject->sales_manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="account_manager_id">Account Manager</label>
                            <select name="account_manager_id" id="account_manager_id" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $googleAdsProject->account_manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_start_date">Contract Start Date</label>
                            <input type="date" name="contract_start_date" id="contract_start_date"
                                class="form-control" value="{{ $googleAdsProject->contract_start_date }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">Contract Term (in months)</label>
                            <input type="number" name="contract_term" id="contract_term" class="form-control"
                                value="{{ $googleAdsProject->contract_term }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">Contract Value</label>
                            <input type="number" step="0.01" name="contract_value" id="contract_value"
                                class="form-control" value="{{ $googleAdsProject->contract_value }}" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="free_month">Free Month</label>
                            <input type="number" name="free_month" id="free_month" class="form-control"
                                value="{{ $googleAdsProject->free_month }}" required>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label" for="contract_copy">Contract Copy</label>
                            <input type="file" class="form-control" id="contract_copy" name="contract_copy[]"
                                multiple>
                            <div class="card border shadow-sm mt-3">
                                <div class="card-header">
                                    <label class="form-label" for="contract_copy">Current Attachments</label>
                                </div>
                                <div class="cad-body">
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
                                                    <div class="col-lg-6">
                                                        <div
                                                            class="card overflow-hidden d-flex justify-content-center align-items-center bg-light border h-100 shadow-sm text-center p-3">
                                                            <img style="width: 100px;" src="{{ $filePath }}"
                                                                class="card-img-top img-fluid" alt="Contract Image">
                                                            <div class="card-body text-center">
                                                                <a href="{{ $filePath }}" target="_blank"
                                                                    class="btn fs-6 px-3 py-2 btn-sm btn-primary">View
                                                                    Full</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Non-Image Files --}}
                                                    <div class="col-lg-6 mb-3">
                                                        <div
                                                            class="position-relative card overflow-hidden d-flex justify-content-center align-items-center bg-light border h-100 shadow-sm text-center p-3">
                                                            <div
                                                                class="badge position-absolute top-0 end-0 bg-primary text-white">
                                                                {{ strtoupper($extension) }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <img
                                                                    src="{{ asset('/images/doc.png') }}"
                                                                    width="50" height="50"
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
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $googleAdsProject->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="Cash"
                                    {{ $googleAdsProject->payment_method == 'Cash' ? 'selected' : '' }}>
                                    Cash
                                </option>
                                <option value="Credit Card"
                                    {{ $googleAdsProject->payment_method == 'Credit Card' ? 'selected' : '' }}>Credit
                                    Card
                                </option>
                                <option value="Bank Transfer"
                                    {{ $googleAdsProject->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank
                                    Transfer</option>
                                <option value="PayPal"
                                    {{ $googleAdsProject->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label" for="handover_notes">Handover Notes</label>
                            <textarea name="handover_notes" id="handover_notes" class="form-control">{{ $googleAdsProject->handover_notes }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary">Update Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
