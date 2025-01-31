<x-app-layout>
    <x-slot name="header">
        {{ __('Create Google Ads Project') }}
    </x-slot>
    <form action="{{ route('google-ads-projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <label class="form-label" for="category">Category</label>
                        <select class="form-control" name="category" id="category" required>
                            <option value="PLATINUM">PLATINUM</option>
                            <option value="GOLD">GOLD</option>
                            <option value="SILVER">SILVER</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="name">Project Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="industry_id">Industry</label>
                        <select class="form-control" name="industry_id" id="industry_id" required>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->industry }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="Current">Current</option>
                            <option value="Paused">Paused</option>
                            <option value="WFF">WFF</option>
                            <option value="Expired">Expired</option>
                            <option value="Debt Collection">Debt Collection</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="sales_manager_id">Sales Manager</label>
                        <select class="form-control" name="sales_manager_id" id="sales_manager_id" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="account_manager_id">Account Manager</label>
                        <select class="form-control" name="account_manager_id" id="account_manager_id" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="contract_start_date">Contract Start Date</label>
                        <input type="date" class="form-control" name="contract_start_date"
                            value="{{ old('contract_start_date') }}" required>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="contract_term">Contract Term (months)</label>
                        <input type="number" class="form-control" name="contract_term"
                            value="{{ old('contract_term') }}" required>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="contract_value">Contract Value</label>
                        <input type="number" class="form-control" name="contract_value"
                            value="{{ old('contract_value') }}" required>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="free_month">Free Month</label>
                        <input type="number" class="form-control" name="free_month" value="{{ old('free_month') }}">
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="contract_copy">Contract Copy</label>
                        <input type="file" class="form-control" name="contract_copy[]" multiple>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="customer_id">Customer</label>
                        <select class="form-control" name="customer_id" id="customer_id" required>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="payment_method">Payment Method</label>
                        <select class="form-control" name="payment_method" id="payment_method" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <label class="form-label" for="handover_notes">Handover Notes</label>
                        <textarea class="form-control" name="handover_notes">{{ old('handover_notes') }}</textarea>
                    </div>

                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary">Save Project</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</x-app-layout>
