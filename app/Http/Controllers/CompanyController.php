<?php

// app/Http/Controllers/CompanyController.php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    // List all companies
    public function index()
    {
        $companies = Company::paginate(15);
        return view('companies.index', compact('companies'));
    }

    // Show the form to create a new company
    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|url',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',

            'detail_name.*' => 'required|string|max:255',
            'detail_email.*' => 'required|email|unique:company_details,email',
            'detail_phone.*' => 'required|string|max:20',
            'detail_city.*' => 'required|string|max:100',
            // 'detail_website.*' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company = Company::create([
            'name' => $validated['name'],
            'website' => $validated['website'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'logo' => $validated['logo'] ?? null,
        ]);

        foreach ($request->detail_name as $key => $name) {
            CompanyDetail::create([
                'company_id' => $company->id,
                'name' => $name,
                'email' => $request->detail_email[$key],
                'phone' => $request->detail_phone[$key],
                'city' => $request->detail_city[$key],
                // 'website' => $request->detail_website[$key] ?? null,
            ]);
        }

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }


    // Show the details of a single company
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    // Show the form to edit an existing company
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    // Update the company
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|url',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'nullable',
        ]);

        $company = Company::findOrFail($id);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    // Delete a company
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}
