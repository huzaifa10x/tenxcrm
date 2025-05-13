<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Service;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $LeadStatus = LeadStatus::all();
        $statuses = LeadStatus::with('leads')->get();
        $industries = Industry::all();
        $sources = LeadSource::all();
        return view('sales-leads.index', compact('services', 'LeadStatus', 'statuses', 'industries', 'sources'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'lead_status_id' => 'required|exists:lead_statuses,id'
        ]);

        Lead::where('id', $request->lead_id)->update(['lead_status_id' => $request->lead_status_id]);

        return response()->json(['message' => 'Lead status updated']);
    }

    public function list()
    {
        $leads = Lead::with('services', 'status')->latest()->get();

        return response()->json(['data' => $leads]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'website' => 'nullable|string',
            'lead_date' => 'nullable|date',
            'comments' => 'nullable|string',
            'lead_status_id' => 'required|exists:lead_statuses,id',
            'services' => 'nullable|array',
            'source_id' => 'nullable|exists:lead_sources,id',
            'priority' => 'nullable|in:low,medium,high',
            'location' => 'nullable|string',
            'is_qualified' => 'boolean',
            'industry_id' => 'nullable|exists:industries,id',
        ]);

        $validated['created_by'] = auth()->id();

        $lead = Lead::create($validated);
        $lead->services()->sync($request->services ?? []);

        return response()->json(['success' => true, 'message' => 'Lead created successfully.']);
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'website' => 'nullable|string',
            'lead_date' => 'nullable|date',
            'comments' => 'nullable|string',
            'lead_status_id' => 'required|exists:lead_statuses,id',
            'services' => 'nullable|array',
            'source_id' => 'nullable|exists:lead_sources,id',
            'priority' => 'nullable|in:low,medium,high',
            'location' => 'nullable|string',
            'is_qualified' => 'boolean',
            'industry_id' => 'nullable|exists:industries,id',
        ]);

        $lead->update($validated);
        $lead->services()->sync($request->services ?? []);

        return response()->json(['success' => true, 'message' => 'Lead updated successfully.']);
    }

    public function show(Lead $lead)
    {
        $lead->load('services', 'status', 'industry', 'source', 'creator');
        return response()->json($lead);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return response()->json(['success' => true, 'message' => 'Lead deleted successfully.']);
    }

    public function getComments(Lead $lead)
    {
        $comments = $lead->comments()->with('creator')->get();
        return response()->json($comments);
    }

    public function storeComment(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $validated['lead_id'] = $lead->id;
        $validated['user_id'] = auth()->id();

        $comment = LeadComment::create($validated);

        return response()->json(['success' => true, 'message' => 'Comment added successfully!', 'comment' => $comment]);
    }

    public function updateComment(Request $request, Lead $lead, LeadComment $comment)
    {
        if ($comment->lead_id !== $lead->id) {
            return response()->json(['error' => 'Comment does not belong to this lead.'], 404);
        }
        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);
        $comment->update($validated);
        return response()->json(['success' => true, 'message' => 'Comment updated successfully!', 'comment' => $comment]);
    }

    public function deleteComment(Lead $lead, LeadComment $comment)
    {

        if ($comment->lead_id !== $lead->id) {
            return response()->json(['error' => 'Comment not found for this lead.'], 404);
        }

        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully!']);
    }
}
