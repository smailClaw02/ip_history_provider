<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip' => $this->faker->unique()->ipv4(),
            'provider_ip' => $this->faker->text(10),
            'vmta' => 'vmta'.$this->faker->randomNumber(3),
            'from' => $this->faker->safeEmail(),
            'return_path' => $this->faker->safeEmail(),
            'spf' => $this->faker->randomElement(['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror']),
            'dkim' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror', 'policy']),
            'dmark' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror']),
            'email' => $this->faker->safeEmail(),
            'message_path' => $this->faker->randomElement(['spam', 'inbox']),
            'colonne' => 'column'.$this->faker->randomNumber(2),
            'redirect_link' => $this->faker->url(),
            'header' => $this->faker->text(200),
            'body' => $this->faker->text(500),
            'domains' => json_encode([
                $this->faker->domainName(),
                $this->faker->domainName(),
                $this->faker->domainName()
            ]),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}