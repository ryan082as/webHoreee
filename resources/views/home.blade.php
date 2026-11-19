@extends('layout')

@section('title', config('app.name'))
@section('floating_cart', '1')

@section('header')
<header class="topbar">
    <div><strong>[Logo] {{ config('app.name') }}</strong></div>

    {{-- Tombol Langganan & Login hanya ada di Halaman Utama --}}
    <nav aria-label="Navigasi Utama">
        <a href="{{ route('subscription.index') }}"><button type="button">Langganan</button></a>
        <a href="{{ route('login') }}"><button type="button">Login</button></a>
    </nav>
</header>
@endsection

@section('content')
<section aria-labelledby="stores-heading">
    <h1 id="stores-heading">Katalog Nama Toko</h1>

    @foreach ($stores as $store)
        <article class="box">
            <a href="{{ route('store.show', $store->getSlug()) }}" style="text-decoration: none; color: inherit; display: block;">
                <strong>{{ $store->getName() }}</strong><br>
                <small>{{ $store->getCategory() }} - {{ $store->getDescription() }}</small>
            </a>
        </article>
    @endforeach
</section>
@endsection
