<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{

    public function test_authenticated_employee_can_get_all_orders()
    {
        $user = User::factory()->create(['type' => 0]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'orders' => [
                    '*' => [
                        'id',
                        'external_id',
                        'confirmed',
                        'shipping_method',
                        'total_products',
                        'products'
                    ]
                ]
            ]);
    }
    public function test_authenticated_admin_can_get_all_orders()
    {
        $user = User::factory()->create(['type' => 1]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'orders' => [
                    '*' => [
                        'id',
                        'external_id',
                        'confirmed',
                        'shipping_method',
                        'total_products',
                        'products',
                        'currency',
                        'order_sum',
                        'paid',
                        'username'
                    ]
                ]
            ]);
    }
}
