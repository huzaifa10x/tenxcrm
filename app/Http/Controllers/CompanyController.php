<?php

// app/Http/Controllers/CompanyController.php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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

    // Store a newly created company
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'required|url',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        Company::create($validated);

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
        ]);

        $company = Company::findOrFail($id);
        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    // Delete a company
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}
