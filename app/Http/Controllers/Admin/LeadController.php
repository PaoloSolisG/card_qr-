<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.leads.index', [
            'leads' => $this->filteredQuery($request)->paginate(20)->withQueryString(),
            'agents' => Agent::query()->orderBy('name')->get(['id', 'name']),
            'agentId' => $request->integer('agent') ?: null,
            'search' => trim((string) $request->query('q')),
        ]);
    }

    /**
     * Export the filtered leads as a CSV that opens cleanly in Excel.
     */
    public function export(Request $request): StreamedResponse
    {
        $leads = $this->filteredQuery($request)->get();

        return response()->streamDownload(function () use ($leads): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Fecha', 'Nombre', 'Teléfono', 'Agente'], ';');

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->created_at->format('d/m/Y H:i'),
                    $lead->name,
                    $lead->phone,
                    $lead->agent?->name,
                ], ';');
            }

            fclose($handle);
        }, 'contactos-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('status', 'Contacto eliminado.');
    }

    /**
     * @return Builder<Lead>
     */
    private function filteredQuery(Request $request): Builder
    {
        $search = trim((string) $request->query('q'));

        return Lead::query()
            ->with('agent')
            ->when($request->integer('agent'), fn (Builder $query, int $agentId) => $query->where('agent_id', $agentId))
            ->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
            }))
            ->latest();
    }
}
