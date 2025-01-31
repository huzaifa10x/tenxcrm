div<x-app-layout>
    <x-slot name="header">
        {{ __('SEO CAMPAIGN - ' . $seoCampaign->name) }}
    </x-slot>
    <div class="container">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="name">Project Name</label>
                            <input readonly type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $seoCampaign->name) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <input readonly type="text" class="form-control" id="name" name="name"
                                value="{{ $seoCampaign->category }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="customer_id">Customer</label>
                            <input type="text" class="form-control" readonly
                                value="{{ $seoCampaign->customer->name ? $seoCampaign->customer->name : '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="industry">Industry</label>
                            <input type="text" readonly class="form-control"
                                value="{{ $seoCampaign->industry->industry ? $seoCampaign->industry->industry : '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <input readonly type="text" class="form-control" id="contract_term" name="contract_term"
                                value="{{ $seoCampaign->status }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="account_manager_id">Account Manager</label>
                            <input readonly type="text" class="form-control" id="contract_term" name="contract_term"
                                value="{{ $seoCampaign->accountManager->name }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">Contract Term (in months)</label>
                            <input readonly type="number" class="form-control" id="contract_term" name="contract_term"
                                value="{{ old('contract_term', $seoCampaign->contract_term) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="free_month">Free Month</label>
                            <input readonly type="number" class="form-control" id="free_month" name="free_month"
                                value="{{ old('free_month', $seoCampaign->free_month) }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="sales_manager_id">Sales Manager</label>
                            <input readonly type="text" class="form-control" id="free_month" name="free_month"
                                value="{{ $seoCampaign->salesManager->name }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_start_date">Contract Start Date</label>
                            <input readonly type="date" class="form-control" id="contract_start_date"
                                name="contract_start_date"
                                value="{{ old('contract_start_date', $seoCampaign->contract_start_date) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">Contract Value (AED)</label>
                            <input readonly type="number" class="form-control" id="contract_value"
                                name="contract_value" value="{{ old('contract_value', $seoCampaign->contract_value) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                            <input readonly type="number" class="form-control" id="number_of_keywords"
                                name="number_of_keywords"
                                value="{{ old('number_of_keywords', $seoCampaign->number_of_keywords) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_first_milestone">Guarantee First Milestone
                                (%)</label>
                            <input readonly type="number" class="form-control" id="guarantee_first_milestone"
                                name="guarantee_first_milestone"
                                value="{{ old('guarantee_first_milestone', $seoCampaign->guarantee_first_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_first_milestone_date">Guarantee First
                                Milestone
                                Date</label>
                            <input readonly type="date" class="form-control" id="guarantee_first_milestone_date"
                                name="guarantee_first_milestone_date"
                                value="{{ old('guarantee_first_milestone_date', $seoCampaign->guarantee_first_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_second_milestone">Guarantee Second Milestone
                                (%)</label>
                            <input readonly type="number" class="form-control" id="guarantee_second_milestone"
                                name="guarantee_second_milestone"
                                value="{{ old('guarantee_second_milestone', $seoCampaign->guarantee_second_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_second_milestone_date">Guarantee Second
                                Milestone
                                Date</label>
                            <input readonly type="date" class="form-control" id="guarantee_second_milestone_date"
                                name="guarantee_second_milestone_date"
                                value="{{ old('guarantee_second_milestone_date', $seoCampaign->guarantee_second_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_third_milestone">Guarantee Third Milestone
                                (%)</label>
                            <input readonly type="number" class="form-control" id="guarantee_third_milestone"
                                name="guarantee_third_milestone"
                                value="{{ old('guarantee_third_milestone', $seoCampaign->guarantee_third_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_third_milestone_date">Guarantee Third
                                Milestone
                                Date</label>
                            <input readonly type="date" class="form-control" id="guarantee_third_milestone_date"
                                name="guarantee_third_milestone_date"
                                value="{{ old('guarantee_third_milestone_date', $seoCampaign->guarantee_third_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method">Payment Method</label>
                            <input readonly type="text" class="form-control" id="payment_method"
                                name="payment_method"
                                value="{{ old('payment_method', $seoCampaign->payment_method) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="handover_notes">Handover Notes</label>
                            <textarea class="form-control" id="handover_notes" name="handover_notes" required>{{ old('handover_notes', $seoCampaign->handover_notes) }}</textarea>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label" for="contract_copy">Contract Copy</label>
                            @if (!empty($seoCampaign->contract_copy) && count($seoCampaign->contract_copy) > 0)
                                <div class="row">
                                    @foreach ($seoCampaign->contract_copy as $file)
                                        @php
                                            $filePath = asset('storage/' . $file);
                                            $extension = pathinfo($file, PATHINFO_EXTENSION);
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                        @endphp

                                        @if (in_array($extension, $imageExtensions))
                                            {{-- Image Files --}}
                                            <div class="col-lg-6 mb-3">
                                                <div
                                                    class="card overflow-hidden d-flex justify-content-center align-items-center bg-light h-100 border shadow-sm">
                                                    <img style="width: 200px;" src="{{ $filePath }}"
                                                        class="card-img-top img-fluid" alt="Contract Image">
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
                                                    <div
                                                        class="badge position-absolute top-0 end-0 bg-primary text-white">
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
        </div>
    </div>
</x-app-layout>
