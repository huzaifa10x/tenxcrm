<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    public function index()
    {
        return view('lead_sources.index');
    }

    public function getSources()
    {
        return LeadSource::all();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        LeadSource::create($request->only('name'));
        return response()->json(['success' => true]);
    }

    public function show(LeadSource $leadSource)
    {
        return $leadSource;
    }

    public function update(Request $request, LeadSource $leadSource)
    {
        $request->validate(['name' => 'required|string']);
        $leadSource->update($request->only('name'));
        return response()->json(['success' => true]);
    }

    public function destroy(LeadSource $leadSource)
    {
        $leadSource->delete();
        return response()->json(['success' => true]);
    }
}
