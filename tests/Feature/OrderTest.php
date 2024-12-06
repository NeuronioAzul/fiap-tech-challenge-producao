<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechChallenge\Infra\DB\Eloquent\Order\Model as Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste para realizar o checkout de um pedido.
     */
    public function test_checkout_order()
    {
        // Arrange: Cria um pedido no banco de dados
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        // Act: Faz a requisição POST para o endpoint /order/checkout/{id}
        $response = $this->postJson('/order/checkout/' . $order->id);

        // Assert: Verifica o status da resposta
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Checkout completed successfully.',
                     'order' => [
                         'id' => $order->id,
                         'status' => 'completed',
                     ],
                 ]);
    }

    /**
     * Teste para alterar o status de um pedido.
     */
    public function test_change_order_status()
    {
        // Arrange: Cria um pedido no banco de dados
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        // Act: Faz a requisição POST para o endpoint /order/status/{id}
        $response = $this->postJson('/order/status/' . $order->id, [
            'status' => 'shipped',
        ]);

        // Assert: Verifica o status da resposta
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Order status updated successfully.',
                     'order' => [
                         'id' => $order->id,
                         'status' => 'shipped',
                     ],
                 ]);
    }

    /**
     * Teste para o endpoint de webhook.
     */
    public function test_webhook_endpoint()
    {
        // Arrange: Payload enviado pelo webhook
        $payload = [
            'event' => 'order_updated',
            'order_id' => 1,
            'status' => 'shipped',
        ];

        // Act: Faz a requisição POST para o endpoint /order/webhook
        $response = $this->postJson('/order/webhook', $payload);

        // Assert: Verifica o status da resposta
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Webhook processed successfully.',
                 ]);
    }
}
