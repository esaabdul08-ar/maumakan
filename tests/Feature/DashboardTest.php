<?php

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

test('dashboard page displays incoming orders', function () {
    $user = User::factory()->create();

    $food = Food::create([
        'name' => 'Nasi Goreng Spesial',
        'category' => 'Makanan',
        'price' => 25000,
        'description' => 'Nasi goreng dengan telur dan kerupuk',
    ]);

    $order = Order::create([
        'customer_name' => 'Budi Santoso',
        'table_number' => '7',
        'total_price' => 50000,
        'status' => 'pending',
    ]);

    OrderDetail::create([
        'order_id' => $order->id,
        'food_id' => $food->id,
        'quantity' => 2,
        'subtotal' => 50000,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('Daftar Pesanan Masuk')
        ->assertSee('Budi Santoso')
        ->assertSee('Nasi Goreng Spesial')
        ->assertSee('50,000');
});

test('dashboard requires authentication', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('admin can update an order status', function () {
    $user = User::factory()->create();

    $order = Order::create([
        'customer_name' => 'Siti Aminah',
        'table_number' => '3',
        'total_price' => 15000,
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.orders.updateStatus', $order->id), [
            'status' => 'completed',
        ]);

    $response->assertSessionHas('success');

    expect($order->refresh()->status)->toBe('completed');
});
