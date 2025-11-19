<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();

        return view('templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        MessageTemplate::create([
            'name' => $validated['name'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Template criado com sucesso!');
    }

    public function destroy(MessageTemplate $template)
    {
        if ($template->user_id !== auth()->id()) {
            return back()->with('error', 'Você não pode excluir este template!');
        }

        $template->delete();
        return back()->with('success', 'Template excluído com sucesso!');
    }
}
