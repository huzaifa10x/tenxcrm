<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Industry;
use App\Models\SEOCampaignProject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SeoCampaignProjectController extends Controller
{
    public function index()
    {
        $seoCampaigns = SeoCampaignProject::with(['salesManager', 'accountManager'])->paginate(10);
        return view('seo_campaign.index', compact('seoCampaigns'));
    }
    public function show($id)
    {
        // Fetch the SEO Campaign by ID with its related data
        $seoCampaign = SEOCampaignProject::findOrFail($id);

        // Return the 'show' view with the fetched SEO Campaign
        return view('seo_campaign.show', compact('seoCampaign'));
    }

    public function create()
    {
        $users = User::all();
        $industries = Industry::all();
        $customers = Customer::all();
        return view('seo_campaign.create', compact('users', 'industries', 'customers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category' => 'required|string',
            'industry_id' => 'required|exists:industries,id',
            'status' => 'required|string',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'customer_id' => 'required|exists:customers,id',
            'contract_value' => 'required|numeric',
            'free_month' => 'nullable|numeric',
            'number_of_keywords' => 'required|integer',
            'guarantee_first_milestone' => 'required|numeric',
            'guarantee_first_milestone_date' => 'required|date',
            'guarantee_second_milestone' => 'required|numeric',
            'guarantee_second_milestone_date' => 'required|date',
            'guarantee_third_milestone' => 'required|numeric',
            'guarantee_third_milestone_date' => 'required|date',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg,gif,webp',
            'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer,PayPal',
            'handover_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('seo-campaign.create')->with('error', $validator->errors()->all());
        }

        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }

        $seoCampaign = SeoCampaignProject::create([
            ...$validator->validated(),
            'contract_copy' => $uploadedFiles,
        ]);

        return redirect()->route('seo-campaign.index')->with('success', 'SEO Campaign created successfully.');
    }

    public function edit($id)
    {
        $seoCampaign = SEOCampaignProject::findOrFail($id);
        $users = User::all();
        $customers = Customer::all();
        $industries = Industry::all();
        return view('seo_campaign.edit', compact('seoCampaign', 'users', 'customers', 'industries'));
    }

    public function update(Request $request, $id)
    {
        $seoCampaign = SeoCampaignProject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'industry_id' => 'required|exists:industries,id',
            'status' => 'required|string',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_month' => 'nullable|numeric', // Should match the migration validation
            'number_of_keywords' => 'required|integer',
            'guarantee_first_milestone' => 'required|numeric',
            'guarantee_first_milestone_date' => 'required|date',
            'guarantee_second_milestone' => 'required|numeric',
            'guarantee_second_milestone_date' => 'required|date',
            'guarantee_third_milestone' => 'required|numeric',
            'guarantee_third_milestone_date' => 'required|date',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg,gif,webp',
            'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer,PayPal', // Should be required
            'handover_notes' => 'nullable|string',
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }

        // Remove previous contract copies before adding new ones
        if ($request->hasFile('contract_copy')) {

            foreach ($seoCampaign->contract_copy as $oldFile) {
                Storage::delete($oldFile);
            }
            $validated['contract_copy'] = $uploadedFiles;
        }

        $seoCampaign->update($validated);

        return redirect()->route('seo-campaign.index')->with('success', 'SEO Campaign updated successfully.');
    }

    public function destroy($id)
    {
        $seoCampaign = SEOCampaignProject::findOrFail($id);
        $seoCampaign->delete();

        return redirect()->route('seo-campaign.index')->with('success', 'SEO Campaign deleted successfully.');
    }
}
