<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Models\Agent;
use App\Services\VCardBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CardController extends Controller
{
    /**
     * Public mobile card shown after scanning the QR.
     */
    public function show(Agent $agent): View
    {
        abort_unless($agent->is_active, 404);

        return view('card.show', ['agent' => $agent]);
    }

    /**
     * Download the agent as a phone contact.
     */
    public function vcard(Agent $agent, VCardBuilder $builder): Response
    {
        abort_unless($agent->is_active, 404);

        return response($builder->build($agent), 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$agent->slug.'.vcf"',
        ]);
    }

    /**
     * Store the name and phone left by a visitor.
     */
    public function storeLead(StoreLeadRequest $request, Agent $agent): RedirectResponse
    {
        abort_unless($agent->is_active, 404);

        $agent->leads()->create($request->validated());

        return to_route('card.show', $agent)->with('lead_saved', true);
    }
}
