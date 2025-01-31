<?php

namespace App\Http\Controllers;

use App\Models\GoogleAdsProject;
use App\Models\Industry;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleAdsProjectController extends Controller
{
    public function index()
    {
        $projects = GoogleAdsProject::paginate(10);
        return view('google_ads_projects.index', compact('projects'));
    }

    public function create()
    {
        $industries = Industry::all();
        $users = User::all();
        $customers = Customer::all();
        return view('google_ads_projects.create', compact('industries', 'users', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required',
            'name' => 'required|string',
            'industry_id' => 'required|exists:industries,id',
            'status' => 'required',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_month' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required',
            'handover_notes' => 'nullable|string',
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }

        GoogleAdsProject::create([...$validated, 'contract_copy' => $uploadedFiles,]);

        return redirect()->route('google-ads-projects.index')->with('success', 'Google Ads Project created successfully.');
    }

    public function edit(GoogleAdsProject $googleAdsProject)
    {
        $industries = Industry::all();
        $users = User::all();
        $customers = Customer::all();
        return view('google_ads_projects.edit', compact('googleAdsProject', 'industries', 'users', 'customers'));
    }

    public function update(Request $request, GoogleAdsProject $googleAdsProject)
    {
        $validated = $request->validate([
            'category' => 'required',
            'name' => 'required|string',
            'industry_id' => 'required|exists:industries,id',
            'status' => 'required',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_month' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required',
            'handover_notes' => 'nullable|string',
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }

        if ($request->hasFile('contract_copy')) {
            foreach ($googleAdsProject->contract_copy as $oldFile) {
                Storage::delete($oldFile);
            }
            $validated['contract_copy'] = $uploadedFiles;
        }

        $googleAdsProject->update($validated);

        return redirect()->route('google-ads-projects.index')->with('success', 'Google Ads Project updated successfully.');
    }

    public function show(GoogleAdsProject $googleAdsProject)
    {
        return view('google_ads_projects.show', compact('googleAdsProject'));
    }
    public function destroy(GoogleAdsProject $googleAdsProject)
    {
        // Deleting the project
        $googleAdsProject->delete();

        return redirect()->route('google-ads-projects.index')->with('success', 'Google Ads Project deleted successfully.');
    }
}
