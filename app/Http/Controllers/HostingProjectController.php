<?php

// In app/Http/Controllers/HostingProjectController.php
namespace App\Http\Controllers;

use App\Models\HostingProject;
use Illuminate\Http\Request;

class HostingProjectController extends Controller
{
    public function create()
    {
        return view('hosting.create');
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

        $hosting = new HostingProject($validated);
        $hosting->save();

        return redirect()->route('projects.show');
    }

    public function edit($id)
    {
        $hosting = HostingProject::findOrFail($id);
        return view('hosting.edit', compact('hosting'));
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

        $hosting = HostingProject::findOrFail($id);
        $hosting->update($validated);

        return redirect()->route('projects.show', $hosting->project_id);
    }

    public function destroy($id)
    {
        $hosting = HostingProject::findOrFail($id);
        $hosting->delete();

        return redirect()->route('projects.index');
    }
}
