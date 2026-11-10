<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgentRequest;
use App\Models\Agent;
use App\Services\QrCodeGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $agents = Agent::query()
            ->withCount('leads')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.agents.index', ['agents' => $agents, 'search' => $search]);
    }

    public function create(): View
    {
        return view('admin.agents.create', ['agent' => new Agent(['is_active' => true])]);
    }

    public function store(AgentRequest $request): RedirectResponse
    {
        $agent = Agent::create($request->validated());

        return to_route('admin.agents.qr', $agent)->with('status', 'Agente creado. Este es su código QR.');
    }

    public function edit(Agent $agent): View
    {
        return view('admin.agents.edit', ['agent' => $agent]);
    }

    public function update(AgentRequest $request, Agent $agent): RedirectResponse
    {
        $agent->update($request->validated());

        return to_route('admin.agents.index')->with('status', 'Agente actualizado.');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        $agent->delete();

        return to_route('admin.agents.index')->with('status', 'Agente eliminado.');
    }

    /**
     * Page with the agent's QR, its link and download buttons.
     */
    public function qr(Agent $agent, QrCodeGenerator $generator): View
    {
        return view('admin.agents.qr', [
            'agent' => $agent,
            'svg' => $generator->svg($agent->cardUrl(), standalone: false),
        ]);
    }

    /**
     * Download the QR as PNG (print) or SVG (design tools).
     */
    public function downloadQr(Agent $agent, string $format, QrCodeGenerator $generator): Response
    {
        [$content, $mime] = match ($format) {
            'svg' => [$generator->svg($agent->cardUrl()), 'image/svg+xml'],
            default => [$generator->png($agent->cardUrl()), 'image/png'],
        };

        return response($content, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="qr-'.$agent->slug.'.'.$format.'"',
        ]);
    }
}
