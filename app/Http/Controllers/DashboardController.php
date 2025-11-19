<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas
        $totalLeads = Lead::count();
        $leadsByStatus = Lead::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $unreadLeads = Lead::where('unread_count', '>', 0)->count();
        $leadsToday = Lead::whereDate('created_at', today())->count();

        // Leads que precisam de atenção (sem mensagem há mais de 24h)
        $leadsNeedingAttention = Lead::where('last_message_at', '<', now()->subHours(24))
            ->orWhereNull('last_message_at')
            ->count();

        // Últimos leads
        $recentLeads = Lead::with(['tags', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalLeads',
            'leadsByStatus',
            'unreadLeads',
            'leadsToday',
            'leadsNeedingAttention',
            'recentLeads'
        ));
    }
}
