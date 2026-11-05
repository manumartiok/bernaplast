@php
    $desarrollo = App\Models\Desarrollo::first() ?? new App\Models\Desarrollo();
    $pasos = $desarrollo->pasos ?? [];
    $matricerias = App\Models\Matriceria::where('active', true)->orderBy('orden')->get();
@endphp

@extends('layouts.web')

@section('title', 'Desarrollo de producto')

@section('content')

<x-banner-seccion seccion="desarrollo" :migas="['Desarrollo de producto' => null]" />

<section class="contenedor pt-[80px] grid grid-cols-1 lg:grid-cols-[599px_1fr] gap-[24px] items-stretch">
    <div class="w-full max-w-[599px] h-[300px] md:h-[500px] rounded-[4px] overflow-hidden bg-fondo">
        @if ($desarrollo->foto)
            <img src="{{ $desarrollo->foto }}" alt="Desarrollo de producto" class="w-full h-full object-cover">
        @endif
    </div>

    <div class="flex flex-col max-w-[600px] lg:pt-[8px] text-[16px] leading-[1.5] text-black">
        <div class="[&_p+p]:mt-[24px]">{!! $desarrollo->t('texto') !!}</div>

        @if ($pasos)
            <ul class="mt-[4px]">
                @foreach ($pasos as $paso)
                    <li class="flex gap-[10px] py-[4px]">
                        <img src="{{ asset('assets/img/icons/circle-check.svg') }}" alt="" width="18" height="18" class="w-[18px] h-[18px] mt-[3px] shrink-0 self-start">
                        <span>{{ \App\Support\Idiomas::texto($paso['texto'] ?? '') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-[11px]">{!! $desarrollo->t('texto2') !!}</div>

        <a href="{{ route('contacto') }}" class="btn-ppal w-[288px] mt-[28px] lg:mt-auto">Consultar</a>
    </div>
</section>

{{-- matricerías --}}
@if ($matricerias->isNotEmpty())
<section class="contenedor pt-[80px] pb-[119px]">
    <h2 class="font-sora font-bold text-[32px] leading-[1.2]">{{ $desarrollo->t('titulo_matricerias') }}</h2>

    <div class="mt-[24px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[23px] justify-items-center lg:pl-[3px]">
        @foreach ($matricerias as $matriceria)
            <div class="w-full max-w-[288px] h-[378px] bg-white border border-linea rounded-[20px] overflow-hidden">
                <div class="relative h-[288px] bg-fondo">
                    @if ($matriceria->foto)
                        <img src="{{ $matriceria->foto }}" alt="{{ $matriceria->t('nombre') }}" class="absolute inset-0 w-full h-full object-contain">
                    @endif
                    <span class="absolute inset-0 bg-black/[0.03]"></span>
                </div>
                <h3 class="px-[24px] pt-[29px] font-sora font-bold text-[20px] leading-[1.2] text-black">{{ $matriceria->t('nombre') }}</h3>
            </div>
        @endforeach
    </div>
</section>
@else
<div class="pb-[119px]"></div>
@endif

@endsection
