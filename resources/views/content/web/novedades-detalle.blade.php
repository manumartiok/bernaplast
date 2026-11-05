@php
    $novedade = App\Models\Novedade::with('categoria')->where('active', true)->findOrFail($id);
@endphp

@extends('layouts.web')

@section('title', $novedade->t('titulo'))

@section('content')

{{-- banner --}}
<x-banner-seccion seccion="novedades" :migas="['Novedades' => route('novedades'), $novedade->t('titulo') => null]" />

{{-- contenido --}}
<section class="contenedor pt-[80px] pb-[110px]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-[48px]">

        {{-- imagen --}}
        <div class="w-full">
            @if ($novedade->foto)
                <img src="{{ $novedade->foto }}"
                     class="w-full h-[300px] md:h-[420px] object-cover border border-linea rounded-[12px]"
                     alt="{{ $novedade->t('titulo') }}">
            @else
                <div class="w-full h-[300px] md:h-[420px] border border-linea rounded-[12px] bg-fondo"></div>
            @endif
        </div>

        {{-- texto --}}
        <div class="flex flex-col justify-center">
            @if ($novedade->categoria)
                <h4 class="text-[14px] font-bold leading-[1.5] text-celeste uppercase mb-[8px]">
                    {{ \App\Support\Idiomas::texto($novedade->categoria->nombre) }}
                </h4>
            @endif

            <h2 class="font-sora font-bold text-[32px] leading-[1.2] mb-[16px]">
                {{ $novedade->t('titulo') }}
            </h2>

            <div class="text-[16px] leading-[1.5] text-[#303030] [&_p+p]:mt-[16px]">
                {!! $novedade->t('texto') !!}
            </div>
        </div>

    </div>
</section>

@endsection
