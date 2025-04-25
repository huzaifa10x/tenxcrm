<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientLead;
use Illuminate\Http\Request;

class ClientLeadController extends Controller
{
    public function index()
    {
        $websites = ClientLead::select('website_name')->distinct()->pluck('website_name');
        return view('leads.index', compact('websites'));
    }

    public function getLeadsByWebsite($website_name)
    {
        $leads = ClientLead::where('website_name', $website_name)->get();

        $formatted = $leads->map(function ($lead) {
            return [
                'id' => $lead->id,
                'form_data' => $lead->form_data,
                'created_at' => $lead->created_at->toDateTimeString(),
            ];
        });

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        $data = $request->except(['website_name', 'website_domain']);

        $lead = ClientLead::create([
            'website_name' => $request->input('website_name', 'Unknown Site'),
            'website_domain' => $request->input('website_domain', $request->getHost()),
            'form_data' => $data,
        ]);

        return response()->json(['success' => true, 'lead_id' => $lead->id], 201);
    }
    public function show(Request $request)
    {
        return response()->json(['message' => 'sfdf']);
    }
}
