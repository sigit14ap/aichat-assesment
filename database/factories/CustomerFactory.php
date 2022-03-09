<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $genderArray = ['male', 'female'];
        $gender = $genderArray[array_rand($genderArray, 1)];

        $start = strtotime('1990-10-01');
        $end = time();
        $timestamp = mt_rand($start, $end);

        return [
            'first_name'        =>  $this->faker->firstName($gender),
            'last_name'         =>  $this->faker->lastName($gender),
            'gender'            =>  $gender,
            'date_of_birth'     =>  date('Y-m-d', $timestamp),
            'contact_number'    =>  $this->faker->phoneNumber(),
            'email'             =>  $this->faker->unique()->safeEmail()
        ];
    }
}
