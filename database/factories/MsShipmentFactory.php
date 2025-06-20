<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransactionHeader;
use App\Models\MsCourier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MsShipment>
 */
class MsShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed> 
     */
    public function definition(): array
    {
        return [
            'shipment_date_start' => $this->faker->dateTimeThisYear(),
            'shipment_date_end' => $this->faker->dateTimeThisYear(),
            'shipment_recipient_name' => $this->faker->name,
            'shipment_status' => $this->faker->randomElement(['In Progress', 'Delivered', 'Cancelled']),

            'transaction_id' => TransactionHeader::inRandomOrder()->first()->transaction_id ?? TransactionHeader::factory(),
            'courier_id' => MsCourier::inRandomOrder()->first()->courier_id ?? MsCourier::factory(),
        ];
    }
}
