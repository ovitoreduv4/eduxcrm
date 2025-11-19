<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::with(['tags', 'assignedTo'])
            ->withCount('messages');

        // Busca
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtros
        if ($request->filled('status')) {
            $query->filterByStatus($request->status);
        }

        if ($request->filled('tag')) {
            $query->filterByTag($request->tag);
        }

        if ($request->filled('assigned')) {
            $query->filterByAssigned($request->assigned);
        }

        // Ordenação
        $query->orderByDesc('unread_count')
              ->orderByDesc('last_message_at');

        $leads = $query->paginate(15);
        $tags = Tag::all();
        $users = User::all();

        return view('leads.index', compact('leads', 'tags', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        $users = User::all();
        return view('leads.create', compact('tags', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $lead = Lead::create([
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'],
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        if (isset($validated['tags'])) {
            $lead->tags()->sync($validated['tags']);
        }

        return redirect()->route('leads.index')->with('success', 'Lead criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load(['tags', 'notes.user', 'assignedTo']);
        $messages = $lead->messages()->orderBy('created_at', 'asc')->get();
        $tags = Tag::all();
        $users = User::all();

        // Marcar mensagens como lidas
        $lead->update(['unread_count' => 0]);

        return view('leads.show', compact('lead', 'messages', 'tags', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $lead->load('tags');
        $tags = Tag::all();
        $users = User::all();
        return view('leads.edit', compact('lead', 'tags', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $lead->update([
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'],
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        if (isset($validated['tags'])) {
            $lead->tags()->sync($validated['tags']);
        } else {
            $lead->tags()->detach();
        }

        return redirect()->route('leads.index')->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead excluído com sucesso!');
    }
}
