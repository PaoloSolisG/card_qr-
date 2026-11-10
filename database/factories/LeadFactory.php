<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agent_id' => Agent::factory(),
            'name' => fake()->name(),
            'phone' => '9'.fake()->numerify('########'),
        ];
    }
}
