@extends('layout')

@section('title', $store->getName())
@section('floating_cart', '1')

@section('header')
<header class="topbar">
    <nav aria-label="Navigasi Halaman">
        <a href="{{ route('home') }}">&larr; Kembali ke Beranda</a>
    </nav>
</header>
@endsection

@section('content')
@if (session('notice'))
    <section class="box row" data-notice-box aria-live="polite">
        <span>{{ session('notice') }}</span>
        <button type="button" data-dismiss-notice>Tutup</button>
    </section>
@endif

<section class="box" aria-label="Informasi Toko">
    <h2>{{ $store->getName() }}</h2>
    <p>{{ $store->getDescription() }}</p>
    <small>{{ $store->getAddress() }} | {{ $store->getWhatsappNumber() }} | {{ $store->getOperationalHours() }}</small>
</section>

<section aria-labelledby="catalog-heading">
    <h3 id="catalog-heading">Katalog Barang</h3>

    @foreach ($store->getProducts() as $product)
        <article class="box">
            @if (!empty($product->getThumbnailUrl()))
                <img src="{{ $product->getThumbnailUrl() }}" alt="{{ $product->getName() }}" style="width: 100%; height: 140px; object-fit: cover;">
            @else
                <div style="height: 100px; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                    [Thumbnail Produk]
                </div>
            @endif

            <p><strong>{{ $product->getName() }}</strong></p>

            <div class="row" style="flex-wrap: wrap;">
                <span>{{ $product->getFormattedPrice() }}</span>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <form action="{{ route('cart.add') }}" method="POST" style="display: flex; align-items: center; gap: 4px; margin: 0;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->getId() }}">
                        <input type="number" name="quantity" value="1" min="1" style="width: 48px;">
                        <button type="submit">Tambah ke Keranjang</button>
                    </form>
                    <a href="{{ route('product.show', [$store->getSlug(), $product->getSlug()]) }}">
                        <button type="button">Detail</button>
                    </a>
                </div>
            </div>
        </article>
    @endforeach
</section>
@endsection
