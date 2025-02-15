<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
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
        $types = ['COMPANY', 'PERSON'];
        $type  = $types[mt_rand(0, count($types) - 1)];

        return [
            'type'    => $type,
            'name'    => $type === 'COMPANY' ? $this->faker->company() : $this->faker->name(),
            'email'   => $this->faker->safeEmail(),
            'phone'   => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
        ];
    }
}
