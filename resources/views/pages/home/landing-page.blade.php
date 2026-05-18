@extends('layouts.home')

@section('title', 'SMK Negeri 1 Rejang Lebong')

@section('content')

<!-- PARTICLES -->
<div id="particles" class="fixed inset-0 pointer-events-none z-0 overflow-hidden"></div>

<!-- NAVBAR -->
@include ('pages.home.partials.navbar')

<!-- HERO -->
@include ('pages.home.partials.hero')

<!-- STATISTIK -->
@include ('pages.home.partials.statistik')

<!-- TENTANG SEKOLAH -->
@include ('pages.home.partials.profil')

<!-- JURUSAN UNGGULAN -->
@include ('pages.home.partials.jurusan')

<!-- LANGKAH PENDAFTARAN -->
@include ('pages.home.partials.langkah')

<!-- JADWAL PENDAFTARAN -->
@include ('pages.home.partials.jadwal')

<!-- FASILITAS -->
@include ('pages.home.partials.fasilitas')

<!-- FAQ -->
@include ('pages.home.partials.faq')

@endsection