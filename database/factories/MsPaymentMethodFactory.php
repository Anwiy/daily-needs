<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MsPaymentMethod>
 */
class MsPaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $usedPaymentMethods = [];

            $allPaymentMethods = [
                'Application Balance', 'Bank Transfer', 'Cash on Delivery'
            ];

            $availablePaymentMethods = array_diff($allPaymentMethods, $usedPaymentMethods);

            if (empty($availablePaymentMethods)) {
                throw new \Exception("All payment method have been used");
            }

            $paymentMethodName = $this->faker->randomElement($availablePaymentMethods);

            $usedPaymentMethods[] = $paymentMethodName;

            return [
                'payment_method_name' => $paymentMethodName,
                'payment_method_slug' => Str::slug($paymentMethodName),
            ];
    }
}
