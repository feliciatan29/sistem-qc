@extends('produksi.layout')

@section('title', 'Dashboard Produksi')

@section('header', 'Dashboard Produksi')

@section('content')

<div class="row g-3">

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h5>Total Produksi</h5>
                <h2>{{ $totalProduksi ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h5>Hasil Produksi</h5>
                <h2>{{ $hasilProduksi ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h5>Pengaturan Mesin</h5>
                <h2>{{ $totalPengaturan ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h5>Data Kerusakan</h5>
                <h2>{{ $totalKerusakan ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>

@endsection
