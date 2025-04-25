<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientLead;
use Illuminate\Http\Request;

class ClientLeadController extends Controller
{
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
