@php
    $home = App\Models\Home::first() ?? new App\Models\Home();
    $slides = App\Models\HomeBanner::where('active', true)->orderBy('orden')->get();
    $destacados = App\Models\Producto::where('active', true)->where('destacado', true)->orderBy('orden')->limit(4)->get();
    $novedades = App\Models\Novedade::with('categoria')->where('active', true)->where('destacado', true)->orderBy('orden')->limit(3)->get();
    $clientes = App\Models\Cliente::where('active', true)->orderBy('orden')->limit(6)->get();
@endphp

@extends('layouts.web')

@section('title', 'Bernaplast')

@section('content')

{{-- banner --}}
<section class="relative h-[520px] md:h-[678px] bg-dark overflow-hidden">
    @foreach ($slides as $i => $slide)
        <div class="slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
            @if ($slide->foto)
                @if ($slide->tipo_medio === 'video')
                    <video src="{{ $slide->foto }}" autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover"></video>
                @else
                    <img src="{{ $slide->foto }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                @endif
            @endif

            <div class="contenedor relative h-full flex flex-col justify-center md:justify-start md:pt-[262px]">
                <h1 class="font-sora font-bold text-white text-[40px] md:text-[48px] leading-[1.2] max-w-[582px]">{{ $slide->titulo }}</h1>
                <p class="mt-[1px] text-white text-[20px] leading-[1.5] max-w-[539px]">{!! $slide->texto !!}</p>
                <a href="{{ route('productos') }}" class="btn-ppal w-[184px] mt-[34px]">VER PRODUCTOS</a>
            </div>
        </div>
    @endforeach

    @if ($slides->count() > 1)
        <div class="absolute bottom-[30px] left-0 w-full">
            <div class="contenedor flex gap-[10px]">
                @foreach ($slides as $i => $slide)
                    <button type="button" class="slide-punto size-[10px] rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}" aria-label="Slide {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
    @endif
</section>

{{-- productos destacados --}}
@if ($destacados->isNotEmpty())
<section class="contenedor pt-[80px]">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="titulo-seccion">{{ $home->titulo_productos }}</h2>
            <p class="mt-[8px] text-[16px] leading-[1.5] max-w-[582px]">{{ $home->texto_productos }}</p>
        </div>
        <a href="{{ route('productos') }}" class="btn-sec shrink-0">Ver todos</a>
    </div>

    <div class="mt-[24px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px] justify-items-center">
        @foreach ($destacados as $producto)
            <x-producto-card :producto="$producto" />
        @endforeach
    </div>
</section>
@endif

{{-- quiénes somos --}}
<section class="contenedor pt-[80px] pb-[80px] grid grid-cols-1 lg:grid-cols-[599px_1fr] gap-[24px] items-start">
    <div class="w-full max-w-[599px] h-[300px] md:h-[400px] rounded-[12px] overflow-hidden bg-fondo">
        @if ($home->foto_nosotros)
            <img src="{{ $home->foto_nosotros }}" alt="{{ $home->titulo_nosotros }}" class="w-full h-full object-cover">
        @endif
    </div>
    <div class="lg:pt-[13px] max-w-[602px]">
        <h2 class="font-sora font-bold text-[32px] leading-[1.2]">{{ $home->titulo_nosotros }}</h2>
        <div class="mt-[22px] text-[15px] leading-[1.5] [&_p+p]:mt-[22px]">{!! $home->texto_nosotros !!}</div>
        <a href="{{ route('nosotros') }}" class="btn-ppal w-[156px] h-[39px] mt-[44px]">Más info</a>
    </div>
</section>

{{-- novedades --}}
@if ($novedades->isNotEmpty())
<section class="bg-fondo">
    <div class="contenedor pt-[40px] pb-[57px]">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="titulo-seccion">{{ $home->titulo_novedades }}</h2>
                <p class="mt-[8px] text-[16px] leading-[1.5]">{{ $home->texto_novedades }}</p>
            </div>
            <a href="{{ route('novedades') }}" class="btn-sec shrink-0">Ver todas</a>
        </div>

        <div class="mt-[24px] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[24px] justify-items-center">
            @foreach ($novedades as $novedad)
                <x-novedad-card :novedad="$novedad" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- clientes --}}
@if ($clientes->isNotEmpty())
<section class="contenedor pt-[80px] pb-[74px]">
    <h2 class="titulo-seccion">{{ $home->titulo_clientes }}</h2>
    <p class="mt-[8px] text-[16px] leading-[1.5]">{{ $home->texto_clientes }}</p>

    <div class="mt-[25px] grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-[24px]">
        @foreach ($clientes as $cliente)
            <div class="aspect-square max-w-[184px] w-full border border-linea rounded-[6px] overflow-hidden">
                @if ($cliente->foto)
                    <img src="{{ $cliente->foto }}" alt="{{ $cliente->t('nombre') }}" class="w-full h-full object-cover">
                @endif
            </div>
        @endforeach
    </div>
</section>
@endif

@endsection

@section('scripts')
@if ($slides->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    const puntos = document.querySelectorAll('.slide-punto');
    let actual = 0;

    const mostrar = (i) => {
        slides[actual].classList.replace('opacity-100', 'opacity-0');
        slides[actual].classList.add('pointer-events-none');
        puntos[actual].classList.replace('bg-white', 'bg-white/40');
        actual = i;
        slides[actual].classList.replace('opacity-0', 'opacity-100');
        slides[actual].classList.remove('pointer-events-none');
        puntos[actual].classList.replace('bg-white/40', 'bg-white');
    };

    puntos.forEach((p, i) => p.addEventListener('click', () => mostrar(i)));
    setInterval(() => mostrar((actual + 1) % slides.length), 6000);
});
</script>
@endif
@endsection
