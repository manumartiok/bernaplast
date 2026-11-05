@php
    $buscar = trim((string) request('buscar'));
    $familiaId = request('familia');

    $familias = App\Models\Familia::where('active', true)->orderBy('orden')
        ->with(['productos' => fn ($q) => $q->where('active', true)->orderBy('orden')])
        ->get();
    $familiaActual = $familias->firstWhere('id', $familiaId);

    $productos = App\Models\Producto::where('active', true)
        ->when($buscar !== '', fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
        ->when($familiaId, fn ($q) => $q->where('familia_id', $familiaId))
        ->orderBy('orden')
        ->get();

    $migas = $familiaActual
        ? ['Productos' => route('productos'), $familiaActual->t('nombre') => null]
        : ['Productos' => null];

    $item = 'flex items-center justify-between px-[12px] py-[10px] rounded-[4px] font-sora font-semibold text-[14px] leading-none';
    $separador = '<span class="block h-px w-full bg-[#E2E6EC]"></span>';
@endphp

@extends('layouts.web')

@section('title', $familiaActual ? $familiaActual->t('nombre') : 'Productos')

@section('content')

<x-banner-seccion seccion="productos" :migas="$migas" />

<section class="contenedor pt-[80px] pb-[126px] grid grid-cols-1 lg:grid-cols-[288px_1fr] gap-x-[24px] gap-y-[40px] items-start">

    {{-- líneas de producto (familias) --}}
    <details open class="group/panel w-full lg:w-[282px] lg:ml-[6px] bg-white border border-[#E2E6EC] rounded-[19px] p-[24px]">
        <summary class="flex items-center justify-between cursor-pointer list-none [&::-webkit-details-marker]:hidden">
            <span class="font-sora font-semibold text-[18px] leading-[1.3] text-[#14202E]">Líneas de producto</span>
            <img src="{{ asset('assets/img/icons/flecha-familia.svg') }}" alt="" width="24" height="24" class="-rotate-90 group-open/panel:rotate-90 transition-transform">
        </summary>

        <div class="mt-[8px] flex flex-col gap-[8px]">
            <a href="{{ route('productos') }}" class="{{ $item }} {{ $familiaActual ? 'text-[#14202E]' : 'text-black' }}">Todos los productos</a>
            {!! $separador !!}

            @foreach ($familias as $familia)
                @php $abierta = $familiaActual?->id === $familia->id; @endphp
                <details @if ($abierta) open @endif class="group/familia">
                    <summary class="{{ $item }} cursor-pointer list-none [&::-webkit-details-marker]:hidden {{ $abierta ? 'text-celeste' : 'text-[#14202E]' }}">
                        <a href="{{ route('productos', ['familia' => $familia->id]) }}" onclick="event.stopPropagation()">{{ $familia->t('nombre') }}</a>
                        <img src="{{ asset('assets/img/icons/flecha-familia.svg') }}" alt="" width="16" height="16" class="-rotate-90 group-open/familia:rotate-90 transition-transform">
                    </summary>
                    @if ($familia->productos->isNotEmpty())
                        <ul class="mt-[8px]">
                            @foreach ($familia->productos as $producto)
                                <li>
                                    <a href="{{ route('productos.detalle', $producto->id) }}" class="block pl-[28px] py-[4px] font-sora text-[13px] leading-[1.5] text-[#4A5563] hover:text-celeste">{{ $producto->t('nombre') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </details>
                {!! $separador !!}
            @endforeach
        </div>
    </details>

    {{-- productos --}}
    <div>
        @if ($buscar !== '')
            <div class="mb-[32px] flex flex-wrap items-center gap-3 text-[16px]">
                <span>Resultados para <strong>“{{ $buscar }}”</strong> ({{ $productos->count() }})</span>
                <a href="{{ route('productos') }}" class="text-celeste font-bold text-[14px]">Ver todos</a>
            </div>
        @endif

        @if ($productos->isEmpty())
            <p class="text-[16px]">No encontramos productos{{ $buscar !== '' ? ' con esa búsqueda' : ($familiaActual ? ' en esta línea' : '') }}.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-[24px] justify-items-center xl:justify-items-start">
                @foreach ($productos as $producto)
                    <x-producto-card :producto="$producto" />
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
