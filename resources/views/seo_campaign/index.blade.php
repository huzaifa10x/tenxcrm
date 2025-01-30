<x-app-layout>
    <div class="container">
        <h1>All SEO Campaigns</h1>
        <a href="{{ route('seo-campaign.create') }}" class="btn btn-primary">Create SEO Campaign</a>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Client</th>
                    <th>Sales Manager</th>
                    <th>Account Manager</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seoCampaigns as $seoCampaign)
                    <tr>
                        <td>{{ $seoCampaign->name }}</td>
                        <td>{{ $seoCampaign->category }}</td>
                        <td>{{ $seoCampaign->status }}</td>
                        <td>{{ $seoCampaign->customer->name ? $seoCampaign->customer->name : '' }}</td>
                        <td>{{ $seoCampaign->salesManager->name }}</td>
                        <td>{{ $seoCampaign->accountManager->name }}</td>
                        <td>
                            <a href="{{ route('seo-campaign.show', $seoCampaign->id) }}" class="btn btn-success">show</a>
                            <a href="{{ route('seo-campaign.edit', $seoCampaign->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('seo-campaign.destroy', $seoCampaign->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
