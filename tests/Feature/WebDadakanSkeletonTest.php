<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebDadakanSkeletonTest extends TestCase
{
    public function test_home_page_has_langganan_login_and_stores(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<main>', false);
        $response->assertSee('<nav aria-label="Navigasi Utama">', false);
        $response->assertSee('<article class="box">', false);
        $response->assertSee('<footer class="footer">', false);
        $response->assertSee('Katalog Multi-Toko');
        $response->assertSee('Langganan');
        $response->assertSee('Login');
        $response->assertSee('Toko A');
        $response->assertSee('Toko B');
        $response->assertSee('floating-cart', false);
    }

    public function test_store_and_product_pages_do_not_show_login_or_langganan_buttons(): void
    {
        $storeResponse = $this->get('/toko/toko-a');
        $storeResponse->assertStatus(200);
        $storeResponse->assertSee('Barang Contoh 1');
        $storeResponse->assertSee('+62 857-0424-4288');
        $storeResponse->assertSee('Rp 50.000');
        $storeResponse->assertSee('Tambah ke Keranjang');
        $storeResponse->assertSee('Detail');
        $storeResponse->assertSee('floating-cart', false);
        $storeResponse->assertDontSee('>Langganan<', false);
        $storeResponse->assertDontSee('>Login<', false);

        $this->get('/toko/toko-b')
            ->assertStatus(200)
            ->assertSee('+62 895-8107-11221');

        $productResponse = $this->get('/toko/toko-a/barang/barang-contoh-1');
        $productResponse->assertStatus(200);
        $productResponse->assertSee('List Detail Barang');
        $productResponse->assertSee('data-toggle-specs="product-specs-body"', false);
        $productResponse->assertSee('Katun');
        $productResponse->assertDontSee('Tambah ke Keranjang');
        $productResponse->assertSee('floating-cart', false);
        $productResponse->assertDontSee('>Langganan<', false);
        $productResponse->assertDontSee('>Login<', false);
    }

    public function test_cart_flow_and_whatsapp_checkout_redirect(): void
    {
        $this->post('/keranjang/tambah', [
            'product_id' => 101,
            'quantity' => 2,
        ])->assertRedirect();

        $cartResponse = $this->get('/keranjang');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Barang Contoh 1');
        $cartResponse->assertSee('Rp 100.000');
        $cartResponse->assertSee('Bayar');
        $cartResponse->assertSee('data-confirm-remove', false);
        $cartResponse->assertDontSee('Total Keseluruhan');
        $cartResponse->assertDontSee('Nomor WhatsApp:');
        $cartResponse->assertDontSee('Kirim Pesanan ke WhatsApp');
        $cartResponse->assertDontSee('>Langganan<', false);
        $cartResponse->assertDontSee('>Login<', false);

        $this->post('/keranjang/update', [
            'product_id' => 101,
            'quantity' => 3,
        ])->assertRedirect('/keranjang');

        $this->get('/keranjang')->assertSee('Rp 150.000');

        $checkoutResponse = $this->post('/keranjang/checkout', [
            'store_id' => 1,
        ]);
        $checkoutResponse->assertRedirect();
        $this->assertStringStartsWith(
            'https://wa.me/6285704244288?text=',
            (string) $checkoutResponse->headers->get('Location'),
        );

        $this->post('/keranjang/tambah', [
            'product_id' => 201,
            'quantity' => 1,
        ])->assertRedirect();

        $checkoutB = $this->post('/keranjang/checkout', [
            'store_id' => 2,
        ]);
        $checkoutB->assertRedirect();
        $this->assertStringStartsWith(
            'https://wa.me/62895810711221?text=',
            (string) $checkoutB->headers->get('Location'),
        );

        $this->post('/keranjang/hapus', [
            'product_id' => 201,
        ])->assertRedirect('/keranjang');

        $this->post('/keranjang/hapus', [
            'product_id' => 101,
        ])->assertRedirect('/keranjang');

        $this->get('/keranjang')->assertSee('Keranjang masih kosong.');
    }

    public function test_login_and_langganan_skeleton_pages(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Masih dalam pengembangan')
            ->assertDontSee('Email:')
            ->assertDontSee('Password:');

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'secret',
        ])->assertRedirect()->assertSessionHas('notice', 'Masih dalam pengembangan');

        $this->get('/langganan')
            ->assertStatus(200)
            ->assertSee('Masih dalam pengembangan')
            ->assertDontSee('Kerangka Paket Langganan')
            ->assertDontSee('Pilih Paket');

        $this->post('/langganan')
            ->assertRedirect()
            ->assertSessionHas('notice', 'Masih dalam pengembangan');
    }

    public function test_api_in_code_endpoints(): void
    {
        $this->getJson('/api/stores')
            ->assertStatus(200)
            ->assertJsonFragment(['slug' => 'toko-a']);

        $this->getJson('/api/stores/toko-a')
            ->assertStatus(200)
            ->assertJsonFragment(['slug' => 'barang-contoh-1']);

        $this->getJson('/api/stores/toko-a/barang/barang-contoh-1')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Barang Contoh 1', 'price' => 50000]);

        $this->getJson('/api/stores/tidak-ada')->assertStatus(404);
    }
}
