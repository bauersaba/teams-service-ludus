<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'coach_id' => fake()->randomDigitNotZero(),
            'popular_name' => fake()->firstNameMale,
            'nickname_club' => fake()->firstNameMale,
            'name_club' => fake()->firstNameMale,
            'acronym_club' => fake()->firstNameMale,
            'shield_club' => fake()->firstNameMale,
        ];
    }
}
