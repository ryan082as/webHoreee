@extends('layout')

@section('title', 'Keranjang & Pemesanan')

@section('header')
<header class="topbar">
    <nav aria-label="Navigasi Halaman">
        <a href="{{ route('home') }}">&larr; Kembali ke Beranda</a>
    </nav>
</header>
@endsection

@section('content')
<section aria-labelledby="cart-heading">
    <h2 id="cart-heading">Keranjang &amp; Pemesanan</h2>

    @php
        $grouped = [];
        foreach ($cart as $item) {
            $grouped[$item['store_id']]['store_name'] = $item['store_name'];
            $grouped[$item['store_id']]['items'][] = $item;
        }
    @endphp

    @forelse ($grouped as $storeId => $group)
        @php $storeTotal = 0; @endphp
        <section class="box" aria-label="Keranjang {{ $group['store_name'] }}">
            <h3>{{ $group['store_name'] }}</h3>

            @foreach ($group['items'] as $item)
                @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $storeTotal += $subtotal;
                @endphp
                <article style="border-top: 1px solid #ddd; padding: 8px 0;">
                    <div class="row">
                        <strong>{{ $item['name'] }}</strong>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <small>Harga satuan: Rp {{ number_format($item['price'], 0, ',', '.') }}</small>

                    <div style="margin-top: 6px;">
                        <form action="{{ route('cart.update') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" style="width: 50px;">
                            <button type="submit">Ubah</button>
                        </form>
                        <form action="{{ route('cart.remove') }}" method="POST" style="display: inline;" data-confirm-remove>
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <button type="submit">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach

            <div class="row" style="border-top: 1px solid #ddd; padding-top: 10px; margin-top: 6px;">
                <strong>Subtotal Toko: Rp {{ number_format($storeTotal, 0, ',', '.') }}</strong>
                <form action="{{ route('cart.checkout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $storeId }}">
                    <button type="submit">Bayar</button>
                </form>
            </div>
        </section>
    @empty
        <section class="box">Keranjang masih kosong.</section>
    @endforelse
</section>
@endsection
