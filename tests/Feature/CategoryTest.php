<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TechChallenge\Infra\DB\Eloquent\Category\Model as Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste para listar todas as categorias.
     */
    public function test_list_all_categories()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->getJson('/category');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonFragment([
                     'id' => $categories[0]->id,
                     'name' => $categories[0]->name,
                 ]);
    }

    /**
     * Teste para exibir detalhes de uma categoria específica.
     */
    public function test_show_category_by_id()
    {
        $category = Category::factory()->create([
            'id' => 1,
            'name' => 'Category 1',
        ]);

        $response = $this->getJson('/category/' . $category->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $category->id,
                     'name' => 'Category 1',
                 ]);
    }

    /**
     * Teste para exibir uma categoria inexistente.
     */
    public function test_show_category_not_found()
    {
        $response = $this->getJson('/category/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Category not found.',
                 ]);
    }

    /**
     * Teste para criar uma nova categoria.
     */
    public function test_create_new_category()
    {
        $categoryData = [
            'name' => 'New Category',
        ];

        $response = $this->postJson('/category', $categoryData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'New Category',
                 ]);

        $this->assertDatabaseHas('categories', $categoryData);
    }

    /**
     * Teste para atualizar uma categoria existente.
     */
    public function test_update_existing_category()
    {
        $category = Category::factory()->create([
            'name' => 'Old Category',
        ]);

        $updateData = [
            'name' => 'Updated Category',
        ];

        $response = $this->putJson('/category/' . $category->id, $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Updated Category',
                 ]);

        $this->assertDatabaseHas('categories', $updateData);
    }

    /**
     * Teste para tentar atualizar uma categoria inexistente.
     */
    public function test_update_nonexistent_category()
    {
        $updateData = [
            'name' => 'Updated Category',
        ];

        $response = $this->putJson('/category/999', $updateData);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Category not found.',
                 ]);
    }

    /**
     * Teste para deletar uma categoria existente.
     */
    public function test_delete_existing_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('/category/' . $category->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Teste para tentar deletar uma categoria inexistente.
     */
    public function test_delete_nonexistent_category()
    {
        $response = $this->deleteJson('/category/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Category not found.',
                 ]);
    }
}
