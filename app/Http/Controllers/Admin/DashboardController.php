<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Lead;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'agentsCount' => Agent::query()->count(),
            'activeAgentsCount' => Agent::query()->active()->count(),
            'leadsCount' => Lead::query()->count(),
            'leadsThisWeek' => Lead::query()->where('created_at', '>=', now()->subDays(7))->count(),
            'latestLeads' => Lead::query()->with('agent')->latest()->limit(6)->get(),
        ]);
    }
}
