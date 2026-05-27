<?php

use App\Models\Product;
use App\Models\Type;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('lista produtos sem autenticacao', function () {
    $type = Type::create(['name' => 'Teste']);
    Product::create([
        'name' => 'Produto API',
        'description' => 'Descricao',
        'quantity' => 10,
        'price' => 99.90,
        'type_id' => $type->id,
    ]);

    $response = $this->getJson('/api/products');

    $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'Produto API']);
});

test('login retorna token com credenciais validas', function () {
    $user = User::factory()->create([
        'email' => 'api@example.com',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'api@example.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
});

test('login retorna 401 com credenciais invalidas', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'inexistente@example.com',
        'password' => 'errada',
    ]);

    $response->assertUnauthorized();
});

test('cria produto com token sanctum', function () {
    $user = User::factory()->create();
    $type = Type::create(['name' => 'Categoria']);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/products', [
        'name' => 'Novo Produto',
        'description' => 'Descricao do produto',
        'quantity' => 5,
        'price' => 49.99,
        'type_id' => $type->id,
    ]);

    $response->assertCreated()
        ->assertJsonFragment(['name' => 'Novo Produto']);

    $this->assertDatabaseHas('products', ['name' => 'Novo Produto']);
});

test('criar produto sem token retorna 401', function () {
    $type = Type::create(['name' => 'Categoria']);

    $response = $this->postJson('/api/products', [
        'name' => 'Sem Auth',
        'quantity' => 1,
        'price' => 10,
        'type_id' => $type->id,
    ]);

    $response->assertUnauthorized();
});

test('criar produto com dados invalidos retorna 422', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->postJson('/api/products', [
        'name' => '',
        'quantity' => -1,
        'price' => -5,
        'type_id' => 99999,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'quantity', 'price', 'type_id']);
});

test('atualiza produto com token sanctum', function () {
    $user = User::factory()->create();
    $type = Type::create(['name' => 'Categoria']);
    $product = Product::create([
        'name' => 'Antigo',
        'description' => null,
        'quantity' => 1,
        'price' => 10,
        'type_id' => $type->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/products/{$product->id}", [
        'name' => 'Atualizado',
        'description' => 'Nova descricao',
        'quantity' => 20,
        'price' => 150.50,
        'type_id' => $type->id,
    ]);

    $response->assertOk()
        ->assertJsonFragment(['name' => 'Atualizado']);

    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Atualizado']);
});

test('exclui produto com token sanctum', function () {
    $user = User::factory()->create();
    $type = Type::create(['name' => 'Categoria']);
    $product = Product::create([
        'name' => 'Para Excluir',
        'description' => null,
        'quantity' => 1,
        'price' => 10,
        'type_id' => $type->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson("/api/products/{$product->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Produto excluído com sucesso.']);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('produto inexistente retorna 404', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->putJson('/api/products/99999', [
        'name' => 'Teste',
        'quantity' => 1,
        'price' => 10,
        'type_id' => 1,
    ]);

    $response->assertNotFound();
});
