<?php

namespace Database\Factories;

use App\Models\Lead as Lead;
use App\Models\Organisation as Organisation;
use App\Models\Account as Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Carbon\Carbon;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userable = [
            Lead::class,
            Account::class,
            Organisation::class,
        ];

        $userableType = $this->faker->randomElement($userable);
        if ($userableType === Lead::class) {
            $userableId = Lead::all()->random()->id;
        } elseif ($userableType === Account::class) {
            $userableId = Account::all()->random()->id;
        } else {
            $userableId = Organisation::all()->random()->id;
        }

        return [
            'userable_type' => $userableType,
            'userable_id' => $userableId,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
