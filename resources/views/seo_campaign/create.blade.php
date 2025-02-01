<x-app-layout>
    <x-slot name="header">
        {{ __('Create SEO Campaign') }}
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
            <form action="{{ route('seo-campaign.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <label class="form-label" for="name">Project Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="PLATINUM">PLATINUM</option>
                            <option value="GOLD">GOLD</option>
                            <option value="SILVER">SILVER</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="customer_id">Select Customer</label>
                        <select class="form-control" id="customer_id" name="customer_id" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="industry">Industry</label>
                        <select class="form-control" id="industry" name="industry_id" required>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->industry }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Current">Current</option>
                            <option value="Paused">Paused</option>
                            <option value="WFF">WFF</option>
                            <option value="Expired">Expired</option>
                            <option value="Debt Collection">Debt Collection</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="account_manager_id">Account Manager</label>
                        <select class="form-control" id="account_manager_id" name="account_manager_id"
                            required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="contract_term">Contract Term (in months)</label>
                        <input type="number" class="form-control" id="contract_term" name="contract_term"
                            required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="free_month">Free Month</label>
                        <input class="form-control" type="number" id="free_month" name="free_month">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="sales_manager_id">Sales Manager</label>
                        <select class="form-control" id="sales_manager_id" name="sales_manager_id" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="contract_start_date">Contract Start Date</label>
                        <input type="date" class="form-control" id="contract_start_date"
                            name="contract_start_date" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="contract_value">Contract Value (AED)</label>
                        <input type="number" class="form-control" id="contract_value" name="contract_value"
                            required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="number_of_keywords">Number of Keywords</label>
                        <input type="number" class="form-control" id="number_of_keywords"
                            name="number_of_keywords" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_first_milestone">Guarantee First
                            Milestone
                            (%)</label>
                        <input type="number" class="form-control" id="guarantee_first_milestone"
                            name="guarantee_first_milestone" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_first_milestone_date">Guarantee First
                            Milestone Date</label>
                        <input type="date" class="form-control" id="guarantee_first_milestone_date"
                            name="guarantee_first_milestone_date" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_second_milestone">Guarantee Second
                            Milestone
                            (%)</label>
                        <input type="number" class="form-control" id="guarantee_second_milestone"
                            name="guarantee_second_milestone" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_second_milestone_date">Guarantee Second
                            Milestone Date</label>
                        <input type="date" class="form-control" id="guarantee_second_milestone_date"
                            name="guarantee_second_milestone_date" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_third_milestone">Guarantee Third
                            Milestone
                            (%)</label>
                        <input type="number" class="form-control" id="guarantee_third_milestone"
                            name="guarantee_third_milestone" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="guarantee_third_milestone_date">Guarantee Third
                            Milestone Date</label>
                        <input type="date" class="form-control" id="guarantee_third_milestone_date"
                            name="guarantee_third_milestone_date" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="contract_copy">Contract Copy</label>
                        <input type="file" class="form-control" id="contract_copy"
                            name="contract_copy[]" multiple>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label" for="handover_notes">Handover Notes</label>
                        <textarea class="form-control" id="handover_notes" name="handover_notes" required></textarea>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-primary-600" type="submit">Save Campaign</button>
                </div>
            </form>
        </div>
    </div>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
