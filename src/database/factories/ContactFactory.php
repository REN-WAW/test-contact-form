<?php

namespace Database\Factories;

use App\Models\contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * @var string
     */

    protected $model = Contact::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $categoryIds = Category::pluck('id')->all();
        if (empty($categoryIds)) {
            $categoryIds = [1];
    }

    return [
        'category_id' => $this->faker->randomElement($categoryIds),
        'first_name' => $this->faker->firstName,
        'last_name' => $this->faker->lastName,
        'gender' => $this->faker->numberBetween(1, 3),
        'email' => $this->faker->unique()->safeEmail,
        'tel' => $this->faker->phoneNumber,
        'address' => $this->faker->address,
        'building' => $this->faker->optional(0.5)->secondaryAddress,
        'detail' => $this->faker->realText(200), 
    ];
}
}
