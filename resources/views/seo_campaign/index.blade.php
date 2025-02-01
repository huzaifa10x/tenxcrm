<x-app-layout>
    <x-slot name="header">
        {{ __('SEO Campaigns') }}
    </x-slot>
    <div class="card basic-data-table">
        @if ($userPermissions->contains('can manage seo-campaign'))
            <div class="card-header">
                <a href="{{ route('seo-campaign.create') }}" class="btn btn-outline-primary">
                    Add New Campaign
                </a>
            </div>
        @endif
        <div class="card-body table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
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
                            <td>{{ $seoCampaign->customer->name ? $seoCampaign->customer->email : '' }}</td>
                            <td>{{ $seoCampaign->salesManager->name }}</td>
                            <td>{{ $seoCampaign->accountManager->name }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-10 justify-content-center">
                                    <a href="{{ route('seo-campaign.show', $seoCampaign->id) }}"
                                        class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                        <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
                                    </a>
                                    @if ($userPermissions->contains('can manage seo-campaign'))
                                        <a href="{{ route('seo-campaign.edit', $seoCampaign->id) }}"
                                            class="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="lucide:edit" class="menu-icon"></iconify-icon>
                                        </a>
                                        <form action="{{ route('seo-campaign.destroy', $seoCampaign->id) }}"
                                            method="POST" style="display: inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="remove-item-btn bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                                <iconify-icon icon="fluent:delete-24-regular"
                                                    class="menu-icon"></iconify-icon>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $seoCampaigns->links('vendor.pagination.custom') }}
        </div>
    </div>

</x-app-layout>
