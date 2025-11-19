<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        Note::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Nota adicionada com sucesso!');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('success', 'Nota excluída com sucesso!');
    }
}
