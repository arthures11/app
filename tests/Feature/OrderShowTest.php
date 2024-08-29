<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OrderShowTest extends TestCase
{

    public function test_authenticated_employee_can_get_order_by_id()
    {
        $user = User::factory()->create(['type' => 0]);
        $orderId = 77;

        $response = $this->actingAs($user)
            ->getJson("/api/v1/orders/{$orderId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'order' => [
                    'id',
                    'external_id',
                    'confirmed',
                    'shipping_method',
                    'total_products',
                    'shipping_first_name',
                    'shipping_last_name',
                    'shipping_company',
                    'shipping_street',
                    'shipping_street_number_1',
                    'shipping_street_number_2',
                    'shipping_post_code',
                    'shipping_city',
                    'shipping_state_code',
                    'shipping_state',
                    'shipping_country_code',
                    'shipping_country',
                    'products'
                ]
            ])
            ->assertJson([
                'order' => [
                    'id' => (string) $orderId
                ]
            ]);
    }

    public function test_authenticated_admin_can_get_order_by_id()
    {
        $user = User::factory()->create(['type' => 1]);
        $orderId = 123;

        $response = $this->actingAs($user)
            ->getJson("/api/v1/orders/{$orderId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'order' => [
                    'id',
                    'external_id',
                    'confirmed',
                    'shipping_method',
                    'total_products',
                    'shipping_first_name',
                    'shipping_last_name',
                    'shipping_company',
                    'shipping_street',
                    'shipping_street_number_1',
                    'shipping_street_number_2',
                    'shipping_post_code',
                    'shipping_city',
                    'shipping_state_code',
                    'shipping_state',
                    'shipping_country_code',
                    'shipping_country',
                    'products',
                    'currency',
                    'order_sum',
                    'paid',
                    'username'
                ]
            ])
            ->assertJson([
                'order' => [
                    'id' => (string) $orderId
                ]
            ]);
    }
}
