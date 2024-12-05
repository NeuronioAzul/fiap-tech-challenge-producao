<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechChallenge\Infra\DB\Eloquent\Product\Model as Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste para listar todos os produtos.
     */
    public function test_list_all_products()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->getJson('/product');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonFragment([
                     'id' => $products[0]->id,
                     'name' => $products[0]->name,
                     'price' => $products[0]->price,
                 ]);
    }

    /**
     * Teste para exibir detalhes de um produto específico.
     */
    public function test_show_product_by_id()
    {
        $product = Product::factory()->create([
            'id' => 1,
            'name' => 'Product 1',
            'price' => 100.50,
        ]);

        $response = $this->getJson('/product/' . $product->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => 'Product 1',
                     'price' => 100.50,
                 ]);
    }

    /**
     * Teste para exibir um produto inexistente.
     */
    public function test_show_product_not_found()
    {
        $response = $this->getJson('/product/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Product not found.',
                 ]);
    }

    /**
     * Teste para criar um novo produto.
     */
    public function test_create_new_product()
    {
        $productData = [
            'name' => 'New Product',
            'description' => 'A sample product',
            'price' => 99.99,
        ];

        $response = $this->postJson('/product', $productData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'New Product',
                     'description' => 'A sample product',
                     'price' => 99.99,
                 ]);

        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Teste para atualizar um produto existente.
     */
    public function test_update_existing_product()
    {
        $product = Product::factory()->create([
            'name' => 'Old Product',
            'price' => 50.00,
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'price' => 75.00,
        ];

        $response = $this->putJson('/product/' . $product->id, $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Updated Product',
                     'price' => 75.00,
                 ]);

        $this->assertDatabaseHas('products', $updateData);
    }

    /**
     * Teste para tentar atualizar um produto inexistente.
     */
    public function test_update_nonexistent_product()
    {
        $updateData = [
            'name' => 'Updated Product',
            'price' => 75.00,
        ];

        $response = $this->putJson('/product/999', $updateData);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Product not found.',
                 ]);
    }

    /**
     * Teste para deletar um produto existente.
     */
    public function test_delete_existing_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson('/product/' . $product->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Teste para tentar deletar um produto inexistente.
     */
    public function test_delete_nonexistent_product()
    {
        $response = $this->deleteJson('/product/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Product not found.',
                 ]);
    }
}
