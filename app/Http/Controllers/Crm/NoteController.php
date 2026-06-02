<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Crm\Deal;
use App\Models\Crm\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request, Deal $deal)
    {
        // $this->authorize('view', $deal);

        $data = $request->validate(['content' => 'required|string']);

        $deal->notes()->create([
            'content'         => $data['content'],
            'person_id'       => $deal->person_id,
            'organization_id' => $deal->organization_id,
            'user_id'         => $request->user()->id,
        ]);

        $deal->touchActivity();

        return back()->with('success', 'Note added.');
    }

    public function update(Request $request, Note $note)
    {
        // $this->authorize('update', $note->deal);
        $note->update($request->validate(['content' => 'required|string']));

        return back()->with('success', 'Note updated.');
    }

    public function pin(Note $note)
    {
        // $this->authorize('view', $note->deal);
        $note->update(['pinned' => ! $note->pinned]);

        return back();
    }

    public function destroy(Note $note)
    {
        // $this->authorize('update', $note->deal);
        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}
