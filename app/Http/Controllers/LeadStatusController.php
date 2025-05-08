<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{
    public function index()
    {
        return LeadStatus::all();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:lead_statuses,name']);
        LeadStatus::create(['name' => $request->name]);
        return response()->json(['message' => 'Status created']);
    }
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $status = LeadStatus::findOrFail($id);
        $status->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }
}
