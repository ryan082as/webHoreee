@extends('layout')

@section('title', $product->getName() . ' - ' . $store->getName())
@section('floating_cart', '1')

@section('header')
<header class="topbar">
    <nav aria-label="Navigasi Halaman">
        <a href="{{ route('store.show', $store->getSlug()) }}">&larr; Kembali ke {{ $store->getName() }}</a>
    </nav>
</header>
@endsection

@section('content')
<article class="box">
    @if (!empty($product->getImageUrl()))
        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->getName() }}" style="width: 100%; height: 220px; object-fit: cover;">
    @else
        <div style="height: 180px; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
            [Foto Produk Besar]
        </div>
    @endif

    <h2>{{ $product->getName() }}</h2>
    <p><strong>{{ $product->getFormattedPrice() }}</strong></p>
</article>

<section class="box" aria-labelledby="specs-heading">
    <div class="row">
        <h3 id="specs-heading" style="margin: 0;">List Detail Barang</h3>
        <button type="button" data-toggle-specs="product-specs-body">Sembunyikan Detail</button>
    </div>

    <div id="product-specs-body">
        <p>{{ $product->getDescription() }}</p>
        <ul>
            @foreach ($product->getSpecs() as $label => $value)
                <li>{{ $label }}: {{ $value }}</li>
            @endforeach
        </ul>
    </div>
</section>
@endsection
