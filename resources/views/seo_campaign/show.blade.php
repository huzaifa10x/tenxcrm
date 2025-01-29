<x-app-layout>
    <div class="container">
        <h1>SEO Campaign: {{ $seoCampaign->name }}</h1>
        <table class="table mt-4">
            <tr>
                <th>Category</th>
                <td>{{ $seoCampaign->category }}</td>
            </tr>
            <tr>
                <th>Industry</th>
                <td>{{ $seoCampaign->industry->industry ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $seoCampaign->status }}</td>
            </tr>
            <tr>
                <th>Sales Manager</th>
                <td>{{ $seoCampaign->salesManager->name }}</td>
            </tr>
            <tr>
                <th>Account Manager</th>
                <td>{{ $seoCampaign->accountManager->name }}</td>
            </tr>
            <tr>
                <th>Contract Start Date</th>
                <td>{{ $seoCampaign->contract_start_date }}</td>
            </tr>
            <tr>
                <th>Contract Term (months)</th>
                <td>{{ $seoCampaign->contract_term }}</td>
            </tr>
            <tr>
                <th>Contract Value (AED)</th>
                <td>{{ $seoCampaign->contract_value }}</td>
            </tr>
            <tr>
                <th>Free Month</th>
                <td>{{ $seoCampaign->free_month ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Number of Keywords</th>
                <td>{{ $seoCampaign->number_of_keywords }}</td>
            </tr>
            <tr>
                <th>Guarantee First Milestone (%)</th>
                <td>{{ $seoCampaign->guarantee_first_milestone }}</td>
            </tr>
            <tr>
                <th>Guarantee First Milestone Date</th>
                <td>{{ $seoCampaign->guarantee_first_milestone_date }}</td>
            </tr>
            <tr>
                <th>Contract Copy</th>
                <td>
                    @if (!empty($seoCampaign->contract_copy))
                        <ul>
                            @foreach ($seoCampaign->contract_copy as $file)
                                <li>
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">View File</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        No contract files attached.
                    @endif
                </td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{ $seoCampaign->payment_method }}</td>
            </tr>
            <tr>
                <th>Handover Notes</th>
                <td>{{ $seoCampaign->handover_notes }}</td>
            </tr>
        </table>
        <a href="{{ route('seo-campaign.edit', $seoCampaign->id) }}" class="btn btn-warning">Edit</a>
    </div>
</x-app-layout>
