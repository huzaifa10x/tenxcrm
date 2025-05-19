<?php

namespace App\Http\Controllers;

use App\Models\PartnerLink;
use Illuminate\Http\Request;

class PartnerLinkController extends Controller
{
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'task_id' => 'required|integer',
            'links' => 'required|array',
            'links.*.keyword' => 'required|string',
            'links.*.url' => 'required|url',
            'links.*.backlink' => 'required|url',
            'links.*.amount' => 'nullable|numeric',
        ]);

        $links = collect($request->input('links'))->map(function ($item) use ($request) {
            return [
                'project_id' => $request->project_id,
                'task_id' => $request->task_id,
                'keyword' => $item['keyword'],
                'url' => $item['url'],
                'backlink' => $item['backlink'],
                'amount' => $item['amount'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        PartnerLink::insert($links->toArray());

        return response()->json(['success' => true]);
    }
}
