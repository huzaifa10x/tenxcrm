<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Company;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('company')->get();  // Load customers with their company
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $companies = Company::all();  // Get all companies for the dropdown
        return view('customers.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'website' => 'nullable|url',
            'company_id' => 'required|exists:companies,id',  // Ensure the company exists
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $companies = Company::all();  // Get companies for the dropdown
        return view('customers.edit', compact('customer', 'companies'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'website' => 'nullable|url',
            'company_id' => 'required|exists:companies,id',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
