<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MsCategory>
 */
class MsCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array
        {

            static $usedCategories = [];

            $allCategories = [
                'Foods', 'Beverages', 'Household Supplies', 
                'Health Supplies', 'Kitchen Supplies', 'Fresh Products'
            ];

            $availableCategories = array_diff($allCategories, $usedCategories);

            if (empty($availableCategories)) {
                throw new \Exception("All category have been used");
            }

            $categoryName = $this->faker->randomElement($availableCategories);

            $usedCategories[] = $categoryName;

            return [
                'category_name' => $categoryName,
                'category_slug' => Str::slug($categoryName),
            ];
        }
}