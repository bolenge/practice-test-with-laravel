<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function store_an_order(array $inputs = []) : object
    {
        $inputs = $inputs ?? [
            'label' => 'Une nouvelle commande',
            'sends_at' => Carbon::tomorrow()
        ];

        return $this->post('orders', $inputs);
    }

    public function test_an_order_can_be_stored_to_the_database()
    {
        $response = $this->store_an_order();

        $response->assertStatus(201);
        $this->assertCount(1, Order::all());
    }

    public function test_an_order_can_be_updated()
    {
        // $this->withoutExceptionHandling();
        $this->store_an_order();

        $order = Order::first();

        $this->put('orders/' . $order->id, [
            'label' => 'Commande updated',
            'sends_at' => Carbon::now()->addDay(2)
        ]);

        $this->assertEquals(Order::first()->label, 'Commande updated');
    }
}
