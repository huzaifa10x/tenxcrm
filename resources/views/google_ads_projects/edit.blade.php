<x-app-layout>
    <h1>Edit Google Ads Project: {{ $googleAdsProject->name }}</h1>

    <form action="{{ route('google-ads-projects.update', $googleAdsProject->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="PLATINUM" {{ $googleAdsProject->category == 'PLATINUM' ? 'selected' : '' }}>PLATINUM</option>
                <option value="GOLD" {{ $googleAdsProject->category == 'GOLD' ? 'selected' : '' }}>GOLD</option>
                <option value="SILVER" {{ $googleAdsProject->category == 'SILVER' ? 'selected' : '' }}>SILVER</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Project Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $googleAdsProject->name }}" required>
        </div>

        <div class="form-group">
            <label for="industry_id">Industry</label>
            <select name="industry_id" id="industry_id" class="form-control" required>
                @foreach($industries as $industry)
                    <option value="{{ $industry->id }}" {{ $googleAdsProject->industry_id == $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Current" {{ $googleAdsProject->status == 'Current' ? 'selected' : '' }}>Current</option>
                <option value="Paused" {{ $googleAdsProject->status == 'Paused' ? 'selected' : '' }}>Paused</option>
                <option value="WFF" {{ $googleAdsProject->status == 'WFF' ? 'selected' : '' }}>WFF</option>
                <option value="Expired" {{ $googleAdsProject->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                <option value="Debt Collection" {{ $googleAdsProject->status == 'Debt Collection' ? 'selected' : '' }}>Debt Collection</option>
                <option value="Cancelled" {{ $googleAdsProject->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sales_manager_id">Sales Manager</label>
            <select name="sales_manager_id" id="sales_manager_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $googleAdsProject->sales_manager_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="account_manager_id">Account Manager</label>
            <select name="account_manager_id" id="account_manager_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $googleAdsProject->account_manager_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="contract_start_date">Contract Start Date</label>
            <input type="date" name="contract_start_date" id="contract_start_date" class="form-control" value="{{ $googleAdsProject->contract_start_date }}" required>
        </div>

        <div class="form-group">
            <label for="contract_term">Contract Term (in months)</label>
            <input type="number" name="contract_term" id="contract_term" class="form-control" value="{{ $googleAdsProject->contract_term }}" required>
        </div>

        <div class="form-group">
            <label for="contract_value">Contract Value</label>
            <input type="number" step="0.01" name="contract_value" id="contract_value" class="form-control" value="{{ $googleAdsProject->contract_value }}" required>
        </div>

        <div class="form-group">
            <label for="free_month">Free Month</label>
            <input type="number" name="free_month" id="free_month" class="form-control" value="{{ $googleAdsProject->free_month }}" required>
        </div>

        <div class="form-group">
            <label for="contract_copy">Contract Copy</label>
            <input type="file" name="contract_copy[]" id="contract_copy" class="form-control" multiple>
            @if ($googleAdsProject->contract_copy)
                <ul>
                    @foreach ($googleAdsProject->contract_copy as $file)
                        <li>
                            <a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $googleAdsProject->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="Cash" {{ $googleAdsProject->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Credit Card" {{ $googleAdsProject->payment_method == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                <option value="Bank Transfer" {{ $googleAdsProject->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="PayPal" {{ $googleAdsProject->payment_method == 'PayPal' ? 'selected' : '' }}>PayPal</option>
            </select>
        </div>

        <div class="form-group">
            <label for="handover_notes">Handover Notes</label>
            <textarea name="handover_notes" id="handover_notes" class="form-control">{{ $googleAdsProject->handover_notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Project</button>
        <a href="{{ route('google-ads-projects.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-app-layout>
