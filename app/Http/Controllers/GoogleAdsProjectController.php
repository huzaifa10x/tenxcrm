<?php

// In app/Http/Controllers/GoogleAdsProjectController.php
namespace App\Http\Controllers;

use App\Models\GoogleAdsProject;
use Illuminate\Http\Request;

class GoogleAdsProjectController extends Controller
{
    public function create()
    {
        return view('google_ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_copy' => 'required|array',
            'payment_method' => 'required|string',
            'handover_notes' => 'nullable|string',
            'category' => 'required|string',
            'name' => 'required|string',
            'industry' => 'required|string',
            'status' => 'required|string',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_month' => 'required|numeric',
        ]);
        $googleAds = new GoogleAdsProject($validated);
        $googleAds->save();

        return redirect()->route('projects.show');
    }

    public function edit($id)
    {
        $googleAds = GoogleAdsProject::findOrFail($id);
        return view('google_ads.edit', compact('googleAds'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'contract_copy' => 'required|array',
            'payment_method' => 'required|string',
            'handover_notes' => 'nullable|string',
            'category' => 'required|string',
            'name' => 'required|string',
            'industry' => 'required|string',
            'status' => 'required|string',
            'sales_manager_id' => 'required|exists:users,id',
            'account_manager_id' => 'required|exists:users,id',
            'contract_start_date' => 'required|date',
            'contract_term' => 'required|integer',
            'contract_value' => 'required|numeric',
            'free_month' => 'required|numeric',
        ]);

        $googleAds = GoogleAdsProject::findOrFail($id);
        $googleAds->update($validated);

        return redirect()->route('projects.show', $googleAds->project_id);
    }

    public function destroy($id)
    {
        $googleAds = GoogleAdsProject::findOrFail($id);
        $googleAds->delete();

        return redirect()->route('projects.index');
    }
}
