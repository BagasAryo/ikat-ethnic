<?php

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->cat1 = Category::create([
        'name' => 'Batik Silk',
        'slug' => 'batik-silk',
    ]);

    $this->cat2 = Category::create([
        'name' => 'Songket',
        'slug' => 'songket',
    ]);

    // Product 1
    $this->prod1 = Product::create([
        'name' => 'Premium Silk Batik Piece',
        'slug' => 'premium-silk-batik-piece',
        'description' => 'Beautiful hand-drawn silk batik from Java.',
        'price' => 500000,
        'category_id' => $this->cat1->id,
    ]);

    // Product 2
    $this->prod2 = Product::create([
        'name' => 'Traditional Songket Weave',
        'slug' => 'traditional-songket-weave',
        'description' => 'Authentic gold-threaded songket cloth.',
        'price' => 1200000,
        'category_id' => $this->cat2->id,
    ]);
});

it('displays the products index page with filters', function () {
    $response = $this->get(route('products'));

    $response->assertStatus(200);
    $response->assertSee('Batik Silk');
    $response->assertSee('Songket');
    $response->assertSee('Premium Silk Batik Piece');
    $response->assertSee('Traditional Songket Weave');
});

it('filters products by search keyword', function () {
    $response = $this->get(route('products', ['search' => 'Batik']));

    $response->assertStatus(200);
    $response->assertSee('Premium Silk Batik Piece');
    $response->assertDontSee('Traditional Songket Weave');
});

it('filters products by category selection', function () {
    $response = $this->get(route('products', ['categories' => [$this->cat2->id]]));

    $response->assertStatus(200);
    $response->assertSee('Traditional Songket Weave');
    $response->assertDontSee('Premium Silk Batik Piece');
});

it('filters products by price range', function () {
    // Under 600,000 IDR
    $response = $this->get(route('products', ['max_price' => 600000]));

    $response->assertStatus(200);
    $response->assertSee('Premium Silk Batik Piece');
    $response->assertDontSee('Traditional Songket Weave');

    // Over 600,000 IDR
    $response = $this->get(route('products', ['min_price' => 600000]));

    $response->assertStatus(200);
    $response->assertSee('Traditional Songket Weave');
    $response->assertDontSee('Premium Silk Batik Piece');
});

it('sorts products by price correctly', function () {
    // Sort low to high
    $response = $this->get(route('products', ['sort' => 'price_asc']));
    $response->assertStatus(200);
    
    // Check if prod1 appears before prod2 in HTML
    $html = $response->getContent();
    $pos1 = strpos($html, 'Premium Silk Batik Piece');
    $pos2 = strpos($html, 'Traditional Songket Weave');
    expect($pos1)->toBeLessThan($pos2);

    // Sort high to low
    $response = $this->get(route('products', ['sort' => 'price_desc']));
    $response->assertStatus(200);
    
    $html = $response->getContent();
    $pos1 = strpos($html, 'Premium Silk Batik Piece');
    $pos2 = strpos($html, 'Traditional Songket Weave');
    expect($pos2)->toBeLessThan($pos1);
});
