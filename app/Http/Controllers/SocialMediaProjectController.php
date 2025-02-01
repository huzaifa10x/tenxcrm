<?php

// In app/Http/Controllers/SocialMediaProjectController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Industry;
use App\Models\SocialMediaProject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocialMediaProjectController extends Controller
{
    public function create()
    {
        $users = User::all();
        $industries = Industry::all();
        $customers = Customer::all();
        return view('social-media-projects.create', compact('users', 'industries', 'customers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channels' => 'required|string',
            'dark_posts_per_month' => 'required|boolean',
            'static_posts_count' => 'nullable|integer',
            'video_reels_count' => 'nullable|integer',
            'video_shoot_per_month' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg,gif,webp',
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
        if ($validator->fails()) {
            return redirect()->route('social-media-projects.create')->with('error', $validator->errors()->all());
        }
        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }
        $socialMediaProject = SocialMediaProject::create([
            ...$validator->validated(),
            'contract_copy' => $uploadedFiles,
        ]);
        return redirect()->route('social-media-projects.index')->with('success', 'Social Media Project created successfully.');
    }

    public function show($id)
    {
        $SocialMediaProject = SocialMediaProject::findOrFail($id);
        return view('social-media-projects.show', compact('SocialMediaProject'));
    }

    public function edit($id)
    {
        $socialMediaProject = SocialMediaProject::findOrFail($id);
        $users = User::all();
        $customers = Customer::all();
        $industries = Industry::all();
        return view('social-media-projects.edit', compact('socialMediaProject', 'users', 'customers', 'industries'));
    }

    public function update(Request $request, $id)
    {
        $SocialMediaProject = SocialMediaProject::findOrFail($id);
        $validated = $request->validate([
            'channels' => 'required|string',
            'dark_posts_per_month' => 'required|boolean',
            'static_posts_count' => 'nullable|integer',
            'video_reels_count' => 'nullable|integer',
            'video_shoot_per_month' => 'nullable|integer',
            'contract_copy' => 'nullable|array',
            'contract_copy.*' => 'file|mimes:pdf,xls,xlsx,doc,docx,jpeg,png,jpg,gif,webp',
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
        $uploadedFiles = [];
        if ($request->hasFile('contract_copy')) {
            foreach ($request->file('contract_copy') as $file) {
                $filePath = $file->store('contracts', 'public');
                $uploadedFiles[] = $filePath;
            }
        }
        if ($request->hasFile('contract_copy')) {
            foreach ($SocialMediaProject->contract_copy as $oldFile) {
                Storage::delete($oldFile);
            }
            $validated['contract_copy'] = $uploadedFiles;
        }
        $SocialMediaProject->update($validated);
        return redirect()->route('social-media-projects.index')->with('success', 'Social Media Project updated successfully.');
    }

    public function destroy($id)
    {
        $SocialMediaProject = SocialMediaProject::findOrFail($id);
        $SocialMediaProject->delete();
        return redirect()->route('social-media-projects.index')->with('success', 'Social Media Project deleted successfully.');
    }
}
