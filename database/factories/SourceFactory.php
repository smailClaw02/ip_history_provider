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
        // Create 3 sets of duplicate values that will be shared across records
        static $duplicateSets = null;

        if ($duplicateSets === null) {
            $duplicateSets = [
                [
                    'ip' => $this->faker->ipv4(),
                    'provider_ip' => $this->faker->text(10),
                    'vmta' => 'vmta' . $this->faker->randomNumber(3),
                    'from' => $this->faker->safeEmail(),
                    'return_path' => $this->faker->safeEmail(),
                ],
                [
                    'ip' => $this->faker->ipv4(),
                    'provider_ip' => $this->faker->text(10),
                    'vmta' => 'vmta' . $this->faker->randomNumber(3),
                    'from' => $this->faker->safeEmail(),
                    'return_path' => $this->faker->safeEmail(),
                ],
                [
                    'ip' => $this->faker->ipv4(),
                    'provider_ip' => $this->faker->text(10),
                    'vmta' => 'vmta' . $this->faker->randomNumber(3),
                    'from' => $this->faker->safeEmail(),
                    'return_path' => $this->faker->safeEmail(),
                ]
            ];
        }

        // 60% chance to use one of the duplicate sets
        if ($this->faker->boolean(60)) {
            $duplicateSet = $this->faker->randomElement($duplicateSets);
            return array_merge($duplicateSet, [
                'spf' => $this->faker->randomElement(['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror']),
                'dkim' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror', 'policy']),
                'dmark' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror']),
                'email' => $this->faker->safeEmail(),
                'message_path' => $this->faker->randomElement(['spam', 'inbox']),
                'colonne' => 'column' . $this->faker->randomNumber(2),
                'redirect_link' => $this->faker->url(),
                'header' => $this->faker->text(200),
                'body' => $this->faker->text(500),
                'domains' => json_encode([
                    $this->faker->domainName(),
                    $this->faker->domainName(),
                    $this->faker->domainName()
                ]),
                'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        // 40% chance to generate completely unique values
        return [
            'ip' => $this->faker->ipv4(),
            'provider_ip' => $this->faker->text(10),
            'vmta' => 'vmta' . $this->faker->randomNumber(3),
            'from' => $this->faker->safeEmail(),
            'return_path' => $this->faker->safeEmail(),
            'spf' => $this->faker->randomElement(['pass', 'fail', 'softfail', 'neutral', 'none', 'permerror', 'temperror']),
            'dkim' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror', 'policy']),
            'dmark' => $this->faker->randomElement(['pass', 'fail', 'none', 'permerror', 'temperror']),
            'email' => $this->faker->safeEmail(),
            'message_path' => $this->faker->randomElement(['spam', 'inbox']),
            'colonne' => 'column' . $this->faker->randomNumber(2),
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
