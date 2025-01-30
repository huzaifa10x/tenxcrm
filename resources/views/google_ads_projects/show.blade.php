<x-app-layout>
    <h1>{{ $googleAdsProject->name }} - Details</h1>

    <table class="table">
        <tr>
            <th>Category</th>
            <td>{{ $googleAdsProject->category }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $googleAdsProject->name }}</td>
        </tr>
        <tr>
            <th>Industry</th>
            <td>{{ $googleAdsProject->industry->industry }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $googleAdsProject->status }}</td>
        </tr>
        <tr>
            <th>Sales Manager</th>
            <td>{{ $googleAdsProject->salesManager->name }}</td>
        </tr>
        <tr>
            <th>Account Manager</th>
            <td>{{ $googleAdsProject->accountManager->name }}</td>
        </tr>
        <tr>
            <th>Contract Start Date</th>
            <td>{{ $googleAdsProject->contract_start_date }}</td>
        </tr>
        <tr>
            <th>Contract Term (months)</th>
            <td>{{ $googleAdsProject->contract_term }}</td>
        </tr>
        <tr>
            <th>Contract Value</th>
            <td>{{ $googleAdsProject->contract_value }}</td>
        </tr>
        <tr>
            <th>Free Month</th>
            <td>{{ $googleAdsProject->free_month }}</td>
        </tr>
        <tr>
            <th>Contract Copy</th>
            <td>
                @if ($googleAdsProject->contract_copy)
                    <ul>
                        @foreach ($googleAdsProject->contract_copy as $file)
                            <li><a href="{{ asset('storage/' . $file) }}" target="_blank">View File</a></li>
                        @endforeach
                    </ul>
                @else
                    No contract files attached.
                @endif
            </td>
        </tr>
        <tr>
            <th>Customer</th>
            <td>{{ $googleAdsProject->customer->name }}</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td>{{ $googleAdsProject->payment_method }}</td>
        </tr>
        <tr>
            <th>Handover Notes</th>
            <td>{{ $googleAdsProject->handover_notes ?? 'No notes available.' }}</td>
        </tr>
    </table>

    <a href="{{ route('google-ads-projects.index') }}" class="btn btn-secondary">Back to Projects</a>
</x-app-layout>
