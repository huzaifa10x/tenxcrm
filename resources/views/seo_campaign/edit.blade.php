<x-app-layout>
    <x-slot name="header">
        {{ __('EDIT CAMPAIGN - ' . $seoCampaign->name) }}
    </x-slot>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($userPermissions->contains('can manage seo-campaign'))
        <div class="card">
            <div class="card-body">
                <form action="{{ route('seo-campaign.update', $seoCampaign->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="name">Project Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $seoCampaign->name) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="PLATINUM" {{ $seoCampaign->category == 'PLATINUM' ? 'selected' : '' }}>
                                    PLATINUM</option>
                                <option value="GOLD" {{ $seoCampaign->category == 'GOLD' ? 'selected' : '' }}>
                                    GOLD
                                </option>
                                <option value="SILVER" {{ $seoCampaign->category == 'SILVER' ? 'selected' : '' }}>
                                    SILVER
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="customer_id">Select Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $seoCampaign->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="industry">Industry</label>
                            <select class="form-control" id="industry" name="industry_id" required>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry->id }}"
                                        {{ $seoCampaign->industry_id == $industry->id ? 'selected' : '' }}>
                                        {{ $industry->industry }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Current" {{ $seoCampaign->status == 'Current' ? 'selected' : '' }}>
                                    Current</option>
                                <option value="Paused" {{ $seoCampaign->status == 'Paused' ? 'selected' : '' }}>
                                    Paused
                                </option>
                                <option value="WFF" {{ $seoCampaign->status == 'WFF' ? 'selected' : '' }}>
                                    WFF
                                </option>
                                <option value="Expired" {{ $seoCampaign->status == 'Expired' ? 'selected' : '' }}>
                                    Expired</option>
                                <option value="Debt Collection"
                                    {{ $seoCampaign->status == 'Debt Collection' ? 'selected' : '' }}>Debt
                                    Collection
                                </option>
                                <option value="Cancelled" {{ $seoCampaign->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="account_manager_id">Account Manager</label>
                            <select class="form-control" id="account_manager_id" name="account_manager_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $seoCampaign->account_manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_term">Contract Term (in months)</label>
                            <input type="number" class="form-control" id="contract_term" name="contract_term"
                                value="{{ old('contract_term', $seoCampaign->contract_term) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="free_month">Free Month</label>
                            <input type="number" class="form-control" id="free_month" name="free_month"
                                value="{{ old('free_month', $seoCampaign->free_month) }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="sales_manager_id">Sales Manager</label>
                            <select class="form-control" id="sales_manager_id" name="sales_manager_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $seoCampaign->sales_manager_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_start_date">Contract Start Date</label>
                            <input type="date" class="form-control" id="contract_start_date"
                                name="contract_start_date"
                                value="{{ old('contract_start_date', $seoCampaign->contract_start_date) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_value">Contract Value (AED)</label>
                            <input type="number" class="form-control" id="contract_value" name="contract_value"
                                value="{{ old('contract_value', $seoCampaign->contract_value) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                            <input type="number" class="form-control" id="number_of_keywords"
                                name="number_of_keywords"
                                value="{{ old('number_of_keywords', $seoCampaign->number_of_keywords) }}" required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_first_milestone">Guarantee First
                                Milestone
                                (%)</label>
                            <input type="number" class="form-control" id="guarantee_first_milestone"
                                name="guarantee_first_milestone"
                                value="{{ old('guarantee_first_milestone', $seoCampaign->guarantee_first_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_first_milestone_date">Guarantee First
                                Milestone
                                Date</label>
                            <input type="date" class="form-control" id="guarantee_first_milestone_date"
                                name="guarantee_first_milestone_date"
                                value="{{ old('guarantee_first_milestone_date', $seoCampaign->guarantee_first_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_second_milestone">Guarantee Second
                                Milestone
                                (%)</label>
                            <input type="number" class="form-control" id="guarantee_second_milestone"
                                name="guarantee_second_milestone"
                                value="{{ old('guarantee_second_milestone', $seoCampaign->guarantee_second_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_second_milestone_date">Guarantee Second
                                Milestone
                                Date</label>
                            <input type="date" class="form-control" id="guarantee_second_milestone_date"
                                name="guarantee_second_milestone_date"
                                value="{{ old('guarantee_second_milestone_date', $seoCampaign->guarantee_second_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_third_milestone">Guarantee Third
                                Milestone
                                (%)</label>
                            <input type="number" class="form-control" id="guarantee_third_milestone"
                                name="guarantee_third_milestone"
                                value="{{ old('guarantee_third_milestone', $seoCampaign->guarantee_third_milestone) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="guarantee_third_milestone_date">Guarantee Third
                                Milestone
                                Date</label>
                            <input type="date" class="form-control" id="guarantee_third_milestone_date"
                                name="guarantee_third_milestone_date"
                                value="{{ old('guarantee_third_milestone_date', $seoCampaign->guarantee_third_milestone_date) }}"
                                required>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="payment_method">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="Cash" {{ $seoCampaign->payment_method == 'Cash' ? 'selected' : '' }}>
                                    Cash</option>
                                <option value="Credit Card"
                                    {{ $seoCampaign->payment_method == 'Credit Card' ? 'selected' : '' }}>
                                    Credit
                                    Card
                                </option>
                                <option value="Bank Transfer"
                                    {{ $seoCampaign->payment_method == 'Bank Transfer' ? 'selected' : '' }}>
                                    Bank
                                    Transfer</option>
                                <option value="PayPal"
                                    {{ $seoCampaign->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="contract_copy">Contract Copy</label>
                            <input type="file" class="form-control" id="contract_copy" name="contract_copy[]"
                                multiple>
                        </div>
                        <div class="col-lg-12">
                            <div class="card border shadow-sm mt-3">
                                <div class="card-header">
                                    <label class="form-label" for="contract_copy">Current Attachments</label>
                                </div>
                                <div class="cad-body">
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
                                                    <div class="col-lg-6">
                                                        <div
                                                            class="card overflow-hidden display-flex align-items-center justify-content-center bg-light h-100 border shadow-sm">
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
                                                    <div class="col-lg-6">
                                                        <div
                                                            class="position-relative card overflow-hidden display-flex align-items-center justify-content-center bg-light border h-100 shadow-sm text-center p-3">
                                                            <div
                                                                class="badge position-absolute top-0 end-0 bg-primary text-white">
                                                                {{ strtoupper($extension) }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <img src="{{ asset('/images/doc.png') }}"
                                                                    width="50" height="50"
                                                                    alt="{{ strtoupper($extension) }} File">
                                                            </div>
                                                            <p class="mb-1 text-dark">{{ basename($file) }}
                                                            </p>
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
                        <div class="col-lg-12">
                            <label class="form-label" for="handover_notes">Handover Notes</label>
                            <textarea class="form-control" id="handover_notes" name="handover_notes" required>{{ old('handover_notes', $seoCampaign->handover_notes) }}</textarea>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
