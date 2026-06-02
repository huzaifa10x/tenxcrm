<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::visibleTo($request->user())->withCount(['deals', 'persons']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%"));
        }

        $organizations = $query->latest()->paginate(20)->withQueryString();

        return view('pipedrive.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('pipedrive.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateOrg($request);
        $data['owner_id'] = $request->user()->id;
        Organization::create($data);

        return redirect()->route('pipedrive.organizations.index')
            ->with('success', 'Organization created.');
    }

    public function edit(Organization $organization)
    {
        return view('pipedrive.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $organization->update($this->validateOrg($request));

        return redirect()->route('pipedrive.organizations.index')
            ->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return back()->with('success', 'Organization deleted.');
    }

    /** AJAX typeahead for the deal form. */
    public function search(Request $request)
    {
        $s = $request->get('q', '');
        $results = Organization::visibleTo($request->user())
            ->where('name', 'like', "%{$s}%")
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($results);
    }

    private function validateOrg(Request $request): array
    {
        return $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:60',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);
    }
}
