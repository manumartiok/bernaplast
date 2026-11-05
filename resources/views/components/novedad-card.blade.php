@props(['novedad'])

{{-- Tarjeta "Novedad blog" del Figma (392x529) --}}
<a href="{{ route('novedades.detalle', $novedad->id) }}"
   class="flex flex-col w-full max-w-[392px] h-[529px] bg-white border border-linea-clara rounded-[20px] overflow-hidden">
    <div class="h-[294px] shrink-0 bg-fondo">
        @if ($novedad->foto)
            <img src="{{ $novedad->foto }}" alt="{{ $novedad->t('titulo') }}" class="w-full h-full object-cover">
        @endif
    </div>

    <div class="flex flex-col flex-1 px-[24px] pt-[24px] pb-[19px]">
        <span class="text-[12px] font-bold leading-[1.5] text-celeste uppercase">{{ \App\Support\Idiomas::texto($novedad->categoria?->nombre) }}</span>
        <h3 class="mt-[8px] font-sora font-bold text-[24px] leading-[1.2] line-clamp-2">{{ $novedad->t('titulo') }}</h3>
        <p class="mt-[17px] text-[16px] leading-[1.5] line-clamp-2">{{ strip_tags($novedad->t('texto')) }}</p>
        <span class="mt-auto text-[16px] font-bold leading-[1.5]">LEER MÁS</span>
    </div>
</a>
