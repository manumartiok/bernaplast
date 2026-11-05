@php
    $buscar = trim((string) request('buscar'));
    $categoria = request('categoria');
    $mes = request('mes');

    $novedades = App\Models\Novedade::with('categoria')
        ->where('active', true)
        ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s->where('titulo', 'like', "%{$buscar}%")->orWhere('texto', 'like', "%{$buscar}%")))
        ->when($categoria, fn ($q) => $q->where('categoria_id', $categoria))
        ->when($mes && preg_match('/^\d{4}-\d{2}$/', $mes), fn ($q) => $q->whereYear('fecha', substr($mes, 0, 4))->whereMonth('fecha', substr($mes, 5, 2)))
        ->orderBy('orden')
        ->get();

    $filtrando = $buscar !== '' || $categoria || $mes;
@endphp

@extends('layouts.web')

@section('title', 'Novedades')

@section('content')

<x-banner-seccion seccion="novedades" :migas="['Novedades' => null]" />

<section class="contenedor pt-[80px] pb-[94px]">
    @if ($filtrando)
        <div class="mb-[32px] flex flex-wrap items-center gap-3 text-[16px]">
            <span>{{ $novedades->count() }} {{ $novedades->count() === 1 ? 'novedad encontrada' : 'novedades encontradas' }}</span>
            <a href="{{ route('novedades') }}" class="text-celeste font-bold text-[14px]">Ver todas</a>
        </div>
    @endif

    {{-- 3 columnas: el filtro es el primer item de la grilla, igual que una tarjeta más --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-[24px] gap-y-[24px] justify-items-center lg:justify-items-start">
        <div class="w-full">
            <x-novedades-sidebar />
        </div>

        @forelse ($novedades as $novedad)
            <x-novedad-card :novedad="$novedad" />
        @empty
            <p class="text-[16px] lg:col-span-2 justify-self-start self-center">No encontramos novedades.</p>
        @endforelse
    </div>
</section>

@endsection
