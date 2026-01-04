<?php

namespace Database\Factories;

use App\Models\Shop\Shop;
use App\Models\Shop\ShopTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'rajaongkir_province_id' => $this->faker->numberBetween(1, 34),
            'rajaongkir_city_id' => $this->faker->numberBetween(1, 500),
            'rajaongkir_district_id' => $this->faker->numberBetween(1, 6000),
            'status' => true,
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'total_reviews' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Shop $shop) {
            foreach (getLocales() as $locale) {
                ShopTranslation::create([
                    'shop_id' => $shop->id,
                    'locale' => $locale->code,
                    'name' => $shop->name,
                    'description' => $this->faker->paragraph(),
                ]);
            }
        });
    }
}
