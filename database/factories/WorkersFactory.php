<?php

namespace Database\Factories;

use App\Models\Workers;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model =Workers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'price_hour'=>$this->faker->numberBetween(100,200),
            'address'=>$this->faker->address(),
            'notes'=>$this->faker->sentence($this->faker->numberBetween(10,100)),
            'status'=>$this->faker->numberBetween(0,1),
            'date_of_billings'=>$this->faker->dateTimeBetween('-2 years', 'now'),
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
