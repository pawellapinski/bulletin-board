<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_products_list()
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'detail' => 'Test Description',
            'price' => 199.99,
        ];

        $response = $this->post(route('products.store'), $productData);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', $productData);
    }

    public function test_can_create_product_with_image()
    {
        Storage::fake('public');

        $productData = [
            'name' => 'Test Product',
            'detail' => 'Test Description',
            'price' => 199.99,
            'images' => [
                UploadedFile::fake()->image('product.jpg')
            ]
        ];

        $response = $this->post(route('products.store'), $productData);
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'detail' => 'Test Description',
            'price' => 199.99,
        ]);

        Storage::disk('public')->assertExists('images/' . $productData['images'][0]->hashName());
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product',
            'detail' => 'Updated Description',
            'price' => 299.99,
        ];

        $response = $this->put(route('products.update', $product), $updatedData);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', $updatedData);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
