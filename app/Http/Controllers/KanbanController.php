<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function index()
    {
        $statuses = ['novo', 'em_contato', 'convertido', 'perdido'];

        $leadsByStatus = [];
        foreach ($statuses as $status) {
            $leadsByStatus[$status] = Lead::with(['tags', 'assignedTo'])
                ->where('status', $status)
                ->orderByDesc('unread_count')
                ->orderByDesc('last_message_at')
                ->get();
        }

        return view('kanban', compact('leadsByStatus'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:novo,em_contato,convertido,perdido',
        ]);

        $lead->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
    }
}
