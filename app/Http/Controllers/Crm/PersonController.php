<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Organization;
use App\Models\Crm\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::visibleTo($request->user())->with('organization')->withCount('deals');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%"));
        }

        $persons       = $query->latest()->paginate(20)->withQueryString();
        $organizations = Organization::visibleTo($request->user())->orderBy('name')->get();

        return view('pipedrive.persons.index', compact('persons', 'organizations'));
    }

    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('pipedrive.persons.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePerson($request);
        $data['owner_id'] = $request->user()->id;
        $data['name']     = $data['name'] ?? trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        Person::create($data);

        return redirect()->route('pipedrive.persons.index')->with('success', 'Contact created.');
    }

    public function edit(Person $person)
    {
        $organizations = Organization::orderBy('name')->get();
        return view('pipedrive.persons.edit', compact('person', 'organizations'));
    }

    public function update(Request $request, Person $person)
    {
        $data = $this->validatePerson($request);
        $data['name'] = $data['name'] ?? trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $person->update($data);

        return redirect()->route('pipedrive.persons.index')->with('success', 'Contact updated.');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return back()->with('success', 'Contact deleted.');
    }

    /** AJAX typeahead for the deal form. */
    public function search(Request $request)
    {
        $s = $request->get('q', '');
        $results = Person::visibleTo($request->user())
            ->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"))
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email']);

        return response()->json($results);
    }

    private function validatePerson(Request $request): array
    {
        return $request->validate([
            'name'            => 'nullable|string|max:255',
            'first_name'      => 'nullable|string|max:120',
            'last_name'       => 'nullable|string|max:120',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:60',
            'label'           => 'nullable|string|max:60',
            'organization_id' => 'nullable|exists:crm_organizations,id',
        ]);
    }
}
