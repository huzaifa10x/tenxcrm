<x-app-layout>
    <x-slot name="header">{{ __('CRM — Contacts') }}</x-slot>
    @include('pipedrive._styles')
    <div class="crm-page">
        @if (session('success'))<div class="alert alert-success py-2" style="font-size:.85rem;">{{ session('success') }}</div>@endif
        <div class="crm-toolbar">
            <h4><i class="bi bi-people me-2"></i>Contacts</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search…" value="{{ request('search') }}" style="width:220px;">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <a href="{{ route('pipedrive.persons.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add</a>
            </div>
        </div>
        <div class="crm-panel">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8f9fc;"><tr><th>Name</th><th>Phone</th><th>Email</th><th>Organization</th><th>Deals</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($persons as $p)
                            <tr>
                                <td class="fw-semibold">{{ $p->name }}</td>
                                <td>{{ $p->phone ?: '—' }}</td>
                                <td>{{ $p->email ?: '—' }}</td>
                                <td>{{ $p->organization?->name ?: '—' }}</td>
                                <td><span class="badge bg-primary">{{ $p->deals_count }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('pipedrive.persons.edit', $p) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('pipedrive.persons.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="crm-empty"><i class="bi bi-people"></i>No contacts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $persons->links() }}</div>
    </div>
</x-app-layout>
