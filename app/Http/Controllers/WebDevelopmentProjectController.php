<?php

// In app/Http/Controllers/WebDevelopmentProjectController.php
namespace App\Http\Controllers;

use App\Models\WebDevelopmentProject;
use Illuminate\Http\Request;

class WebDevelopmentProjectController extends Controller
{
    public function create($projectId)
    {
        return view('web_development.create', compact('project'));
    }

    public function store(Request $request, $projectId)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string',
            'remaining_payment' => 'required|numeric',
            'project_type' => 'required|string',
            'contract_copy' => 'required|array',
            'payment_method' => 'required|string',
            'handover_notes' => 'nullable|string',
        ]);

        $webDev = new WebDevelopmentProject($validated);
        $webDev->save();

        return redirect()->route('projects.show', $projectId);
    }

    public function edit($id)
    {
        $webDev = WebDevelopmentProject::findOrFail($id);
        return view('web_development.edit', compact('webDev'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string',
            'remaining_payment' => 'required|numeric',
            'project_type' => 'required|string',
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

        $webDev = WebDevelopmentProject::findOrFail($id);
        $webDev->update($validated);

        return redirect()->route('projects.show', $webDev->project_id);
    }

    public function destroy($id)
    {
        $webDev = WebDevelopmentProject::findOrFail($id);
        $webDev->delete();

        return redirect()->route('projects.index');
    }
}
