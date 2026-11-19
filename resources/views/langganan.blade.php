@extends('layout')

@section('title', 'Langganan')

@section('header')
<header class="topbar">
    <nav aria-label="Navigasi Halaman">
        <a href="{{ route('home') }}">&larr; Kembali ke Beranda</a>
    </nav>
</header>
@endsection

@section('content')
<section aria-labelledby="langganan-heading">
    <h2 id="langganan-heading">Langganan</h2>

    <div class="box">
        {{ session('notice', 'Masih dalam pengembangan') }}
    </div>
</section>
@endsection
