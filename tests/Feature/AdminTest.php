<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.agents.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_log_in_and_out(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_pages_render(): void
    {
        $agent = Agent::factory()->create();
        Lead::factory()->for($agent)->create();

        $this->actingAs(User::factory()->create());

        $this->get(route('admin.dashboard'))->assertOk()->assertSee($agent->name);
        $this->get(route('admin.agents.index'))->assertOk()->assertSee($agent->name);
        $this->get(route('admin.agents.create'))->assertOk();
        $this->get(route('admin.agents.edit', $agent))->assertOk();
        $this->get(route('admin.leads.index'))->assertOk();
    }

    public function test_admin_can_create_agent_and_lands_on_qr(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.agents.store'), [
            'name' => 'Cristina Godoy Inciso',
            'position' => 'Controller',
            'whatsapp' => '51987654321',
            'is_active' => '1',
        ]);

        $agent = Agent::query()->firstOrFail();

        $response->assertRedirect(route('admin.agents.qr', $agent));
        $this->assertSame('cristina-godoy-inciso', $agent->slug);
        $this->assertTrue($agent->is_active);

        $this->get(route('admin.agents.qr', $agent))->assertOk()->assertSee('<svg', false);
    }

    public function test_admin_can_update_and_delete_agent(): void
    {
        $agent = Agent::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->put(route('admin.agents.update', $agent), ['name' => 'Nuevo Nombre', 'slug' => $agent->slug])
            ->assertRedirect(route('admin.agents.index'));

        $agent->refresh();
        $this->assertSame('Nuevo Nombre', $agent->name);
        $this->assertFalse($agent->is_active, 'Unchecked checkbox should deactivate the card.');

        $this->delete(route('admin.agents.destroy', $agent))->assertRedirect(route('admin.agents.index'));
        $this->assertModelMissing($agent);
    }

    public function test_qr_downloads_as_png_and_svg(): void
    {
        $agent = Agent::factory()->create();
        $this->actingAs(User::factory()->create());

        $png = $this->get(route('admin.agents.qr.download', [$agent, 'png']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
        $this->assertStringStartsWith("\x89PNG", $png->getContent());

        $this->get(route('admin.agents.qr.download', [$agent, 'svg']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');
    }

    public function test_leads_can_be_filtered_and_exported(): void
    {
        $cristina = Agent::factory()->create();
        $other = Agent::factory()->create();
        Lead::factory()->for($cristina)->create(['name' => 'Lead de Cristina']);
        Lead::factory()->for($other)->create(['name' => 'Lead de Otro']);

        $this->actingAs(User::factory()->create());

        $this->get(route('admin.leads.index', ['agent' => $cristina->id]))
            ->assertOk()
            ->assertSee('Lead de Cristina')
            ->assertDontSee('Lead de Otro');

        $csv = $this->get(route('admin.leads.export', ['agent' => $cristina->id]))->assertOk()->streamedContent();
        $this->assertStringContainsString('Lead de Cristina', $csv);
        $this->assertStringNotContainsString('Lead de Otro', $csv);
    }
}
