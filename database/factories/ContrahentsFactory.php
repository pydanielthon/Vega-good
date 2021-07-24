<?php

namespace Database\Factories;

use App\Models\Contrahents;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContrahentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model =Contrahents::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'salary_cash' => $this->faker->numberBetween(100,1000),
            'salary_invoice'=>$this->faker->numberBetween(200,1200),
            'status'=>$this->faker->numberBetween(0,1),
            'notes'=>$this->faker->sentence($this->faker->numberBetween(10,100)),
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
