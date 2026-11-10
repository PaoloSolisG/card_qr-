<?php

namespace Tests\Feature;

use App\Models\Agent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_card_shows_agent_details_and_actions(): void
    {
        $agent = Agent::factory()->create([
            'name' => 'Cristina Godoy Inciso',
            'position' => 'Controller',
            'whatsapp' => '51987654321',
        ]);

        $this->get(route('card.show', $agent))
            ->assertOk()
            ->assertSee('Cristina Godoy Inciso')
            ->assertSee('Controller')
            ->assertSee('Guardar contacto')
            ->assertSee('Déjame tus datos')
            ->assertSee('https://wa.me/51987654321', false);
    }

    public function test_inactive_card_is_not_found(): void
    {
        $agent = Agent::factory()->inactive()->create();

        $this->get(route('card.show', $agent))->assertNotFound();
        $this->get(route('card.vcard', $agent))->assertNotFound();
    }

    public function test_vcard_downloads_contact(): void
    {
        $agent = Agent::factory()->create(['name' => 'Cristina Godoy Inciso', 'position' => 'Controller']);

        $response = $this->get(route('card.vcard', $agent))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/vcard; charset=utf-8');

        $this->assertStringContainsString('FN:Cristina Godoy Inciso', $response->getContent());
        $this->assertStringContainsString('N:Godoy Inciso;Cristina;;;', $response->getContent());
        $this->assertStringContainsString('TITLE:Controller', $response->getContent());
    }

    public function test_visitor_can_leave_name_and_phone(): void
    {
        $agent = Agent::factory()->create();

        $this->post(route('card.leads.store', $agent), ['name' => 'Juan Pérez', 'phone' => '987 654 321'])
            ->assertRedirect(route('card.show', $agent))
            ->assertSessionHas('lead_saved');

        $this->assertDatabaseHas('leads', [
            'agent_id' => $agent->id,
            'name' => 'Juan Pérez',
            'phone' => '987 654 321',
        ]);
    }

    public function test_lead_requires_valid_name_and_phone(): void
    {
        $agent = Agent::factory()->create();

        $this->post(route('card.leads.store', $agent), ['name' => '', 'phone' => 'abc'])
            ->assertSessionHasErrors(['name', 'phone']);

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_slug_is_generated_and_kept_unique(): void
    {
        $first = Agent::factory()->create(['name' => 'Cristina Godoy']);
        $second = Agent::factory()->create(['name' => 'Cristina Godoy']);

        $this->assertSame('cristina-godoy', $first->slug);
        $this->assertSame('cristina-godoy-2', $second->slug);
    }

    public function test_card_url_uses_app_url(): void
    {
        config(['app.url' => 'https://tarjetas.psgroup.com']);

        $agent = Agent::factory()->create(['name' => 'Cristina Godoy']);

        $this->assertSame('https://tarjetas.psgroup.com/c/cristina-godoy', $agent->cardUrl());
    }
}
