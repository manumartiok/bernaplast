@php
    $calidad = App\Models\Calidade::first() ?? new App\Models\Calidade();
    $pilares = $calidad->pilares ?? [];
    $certificados = App\Models\Certificado::where('active', true)->orderBy('orden')->get();

    // Tipo y peso del archivo de un certificado (guardado como /storage/...)
    $datosArchivo = function (?string $url) {
        if (!$url) {
            return [null, null];
        }
        $ruta = \Illuminate\Support\Str::after($url, '/storage/');
        $disco = \Illuminate\Support\Facades\Storage::disk('public');
        $peso = $disco->exists($ruta) ? round($disco->size($ruta) / 1024) . 'kb' : null;

        return [strtoupper(pathinfo($ruta, PATHINFO_EXTENSION)), $peso];
    };
@endphp

@extends('layouts.web')

@section('title', 'Calidad')

@section('content')

<x-banner-seccion seccion="calidad" :migas="['Calidad' => null]" />

<section class="contenedor pt-[80px] grid grid-cols-1 lg:grid-cols-[600px_1fr] gap-x-[27px] gap-y-[40px] items-start">
    <div class="lg:pt-[9px] max-w-[600px] text-black">
        <div class="text-[16px] leading-[1.5] [&_p+p]:mt-[18px]">{!! $calidad->t('texto') !!}</div>

        @if ($pilares)
            <ul class="mt-[10px] flex flex-col gap-[10px] text-[14px] leading-[1.5]">
                @foreach ($pilares as $pilar)
                    <li class="flex gap-[10px]">
                        <img src="{{ asset('assets/img/icons/circle-check.svg') }}" alt="" width="18" height="18" class="w-[18px] h-[18px] mt-[2px] shrink-0 self-start">
                        <p class="max-w-[571px]"><strong>{{ \App\Support\Idiomas::texto($pilar['titulo'] ?? '') }}:</strong> {{ \App\Support\Idiomas::texto($pilar['texto'] ?? '') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($calidad->archivo)
            <a href="{{ $calidad->archivo }}" target="_blank" class="btn-ppal w-[288px] mt-[39px]">Descargar política de calidad</a>
        @endif
    </div>

    <div class="w-full max-w-[599px] h-[300px] md:h-[500px] rounded-[4px] overflow-hidden bg-fondo lg:justify-self-end">
        @if ($calidad->foto)
            <img src="{{ $calidad->foto }}" alt="Calidad" class="w-full h-full object-cover">
        @endif
    </div>
</section>

{{-- certificados --}}
@if ($certificados->isNotEmpty())
<section class="contenedor pt-[80px] pb-[110px]">
    <h2 class="font-sora font-bold text-[32px] leading-[1.2]">{{ $calidad->t('titulo_certificados') }}</h2>

    <div class="mt-[36px] max-w-[600px] border-t border-linea">
        @foreach ($certificados as $certificado)
            @php [$tipo, $peso] = $datosArchivo($certificado->archivo); @endphp
            <a @if ($certificado->archivo) href="{{ $certificado->archivo }}" target="_blank" download @endif
               class="grid grid-cols-[80px_1fr_auto_24px] sm:grid-cols-[80px_184px_106px_84px_24px] gap-x-[24px] items-center py-[8px] border-b border-linea">
                <span class="size-[80px] rounded-[6px] bg-black/[0.03] flex items-center justify-center overflow-hidden">
                    @if ($certificado->foto)
                        <img src="{{ $certificado->foto }}" alt="" class="max-w-[76px] max-h-[76px] object-contain">
                    @endif
                </span>
                <span class="font-sora font-bold text-[16px] leading-[1.2]">{{ $certificado->t('nombre') }}</span>
                <span class="hidden sm:block text-[12px] leading-[1.5] text-[#303030]">{{ $tipo }}</span>
                <span class="text-[12px] leading-[1.5] text-[#303030]">{{ $peso }}</span>
                @if ($certificado->archivo)
                    <img src="{{ asset('assets/img/icons/download.svg') }}" alt="Descargar" width="24" height="24">
                @endif
            </a>
        @endforeach
    </div>
</section>
@else
<div class="pb-[110px]"></div>
@endif

@endsection
