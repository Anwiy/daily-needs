<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MsAdmin;
use App\Models\MsCustomer;
use App\Models\MsCustomerAddress;
use App\Models\MsPaymentMethod;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionHeader>
 */
class TransactionHeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_date' => $this->faker->dateTimeThisYear(),
            'transaction_status' => $this->faker->randomElement(['Pending', 'Processing', 'Out for Delivery', 'Shipped', 'Completed', 'Cancelled']),

            'admin_id' => MsAdmin::inRandomOrder()->first()->admin_id ?? MsAdmin::factory(),
            'customer_id' => MsCustomer::inRandomOrder()->first()->customer_id ?? MsCustomer::factory(),
            'customer_address_id' => MsCustomerAddress::inRandomOrder()->first()->customer_address_id ?? MsCustomerAddress::factory(),
            'payment_method_id' => MsPaymentMethod::inRandomOrder()->first()->payment_method_id ?? MsPaymentMethod::factory(),
        ];
    }
}
