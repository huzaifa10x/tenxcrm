<?php

// In app/Http/Controllers/SocialMediaProjectController.php
namespace App\Http\Controllers;

use App\Models\SocialMediaProject;
use Illuminate\Http\Request;

class SocialMediaProjectController extends Controller
{
    public function create()
    {
        return view('social_media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'channels' => 'required|string',
            'dark_posts_per_month' => 'required|boolean',
            'static_posts_count' => 'nullable|integer',
            'video_reels_count' => 'nullable|integer',
            'video_shoot_per_month' => 'nullable|integer',
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

        $socialMedia = new SocialMediaProject($validated);
        $socialMedia->save();

        return redirect()->route('projects.show');
    }

    public function edit($id)
    {
        $socialMedia = SocialMediaProject::findOrFail($id);
        return view('social_media.edit', compact('socialMedia'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'channels' => 'required|string',
            'dark_posts_per_month' => 'required|boolean',
            'static_posts_count' => 'nullable|integer',
            'video_reels_count' => 'nullable|integer',
            'video_shoot_per_month' => 'nullable|integer',
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

        $socialMedia = SocialMediaProject::findOrFail($id);
        $socialMedia->update($validated);

        return redirect()->route('projects.show', $socialMedia->project_id);
    }

    public function destroy($id)
    {
        $socialMedia = SocialMediaProject::findOrFail($id);
        $socialMedia->delete();

        return redirect()->route('projects.index');
    }
}
