@props([
    'seccion',            // fila de la tabla banners: nosotros, productos, desarrollo, ...
    'migas' => [],        // ['Texto' => url] (la última va sin link)
])

@php
    $banner = App\Models\Banner::seccion($seccion);
@endphp

{{-- Banner de sección del Figma: 1366x221, migas de pan, título y subtítulo --}}
<section class="relative bg-[#ECF1F5] border-b border-linea overflow-hidden">
    @if ($banner->foto)
        <img src="{{ $banner->foto }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    @endif

    <div class="contenedor relative min-h-[221px] pt-[23px] pb-[42px]">
        <nav class="text-[12px] leading-[1.5] text-[#303030]">
            <a href="{{ route('home') }}" class="font-bold">Inicio</a>
            @foreach ($migas as $texto => $url)
                <span>/</span>
                @if ($url)
                    <a href="{{ $url }}" class="font-bold">{{ $texto }}</a>
                @else
                    <span>{{ $texto }}</span>
                @endif
            @endforeach
        </nav>

        @if ($banner->t('texto'))
            <h1 class="mt-[58px] titulo-seccion">{{ $banner->t('texto') }}</h1>
        @endif
        @if ($banner->t('subtitulo'))
            <p class="mt-[8px] text-[16px] leading-[1.5] max-w-[900px]">{{ $banner->t('subtitulo') }}</p>
        @endif
    </div>
</section>
