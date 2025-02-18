<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;

class CompanyDetailsController extends Controller
{
    public function index($id)
    {
        $company = Company::with('details')->findOrFail($id);
        return response()->json($company->details);
    }

    // Store New Company Detail (AJAX)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_details,email',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'website' => 'nullable|url',
            'company_id' => 'required|exists:companies,id',
        ]);

        $detail = CompanyDetail::create($validated);

        return response()->json(['success' => true, 'detail' => $detail]);
    }

    // Update Company Detail (AJAX)
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:company_details,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_details,email,' . $request->id,
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'website' => 'nullable|url',
        ]);

        $detail = CompanyDetail::findOrFail($request->id);
        $detail->update($validated);

        return response()->json(['success' => true, 'detail' => $detail]);
    }

    public function destroy($id)
    {
        $detail = CompanyDetail::findOrFail($id);
        $detail->delete();

        return response()->json(['success' => 'Company detail deleted successfully.']);
    }
}
