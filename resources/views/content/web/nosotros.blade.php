@php
    $nosotros = App\Models\Nosotro::first() ?? new App\Models\Nosotro();
    $valores = $nosotros->valores ?? [];
@endphp

@extends('layouts.web')

@section('title', 'Nosotros')

@section('content')

<x-banner-seccion seccion="nosotros" :migas="['Nosotros' => null]" />

{{-- quiénes somos --}}
<section class="contenedor pt-[80px] pb-[80px] grid grid-cols-1 lg:grid-cols-[599px_1fr] gap-[24px] items-start">
    <div class="w-full max-w-[599px] h-[300px] md:h-[400px] rounded-[12px] overflow-hidden bg-fondo">
        @if ($nosotros->foto)
            <img src="{{ $nosotros->foto }}" alt="{{ $nosotros->t('titulo') }}" class="w-full h-full object-cover">
        @endif
    </div>
    <div class="max-w-[602px]">
        <h2 class="font-sora font-bold text-[32px] leading-[1.2]">{{ $nosotros->t('titulo') }}</h2>
        <div class="mt-[17px] text-[15px] leading-[1.5] [&_p+p]:mt-[22px]">{!! $nosotros->t('texto') !!}</div>
    </div>
</section>

{{-- valores --}}
@if ($valores)
<section class="bg-[#ECF1F5]">
    <div class="contenedor pt-[40px] pb-[64px]">
        <h2 class="font-sora font-bold text-[32px] leading-[1.2]">{{ $nosotros->t('titulo_valores') }}</h2>

        <div class="mt-[24px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px]">
            @foreach ($valores as $valor)
                <div class="min-h-[160px] bg-white rounded-[6px] px-[24px] pt-[27px] pb-[24px]">
                    <h3 class="font-sora font-bold text-[20px] leading-[1.2]">{{ \App\Support\Idiomas::texto($valor['titulo'] ?? '') }}</h3>
                    <p class="mt-[18px] text-[16px] leading-[1.5] text-[#303030] max-w-[238px]">{{ \App\Support\Idiomas::texto($valor['texto'] ?? '') }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
