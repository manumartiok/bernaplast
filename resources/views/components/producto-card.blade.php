@props(['producto'])

{{-- Tarjeta "Producto" del Figma (288x488) --}}
<a href="{{ route('productos.detalle', $producto->id) }}"
   class="flex flex-col w-full max-w-[288px] h-[488px] bg-white border border-linea rounded-[20px] overflow-hidden">
    <div class="relative h-[288px] shrink-0 bg-fondo">
        @if ($producto->foto)
            <img src="{{ $producto->foto }}" alt="{{ $producto->t('nombre') }}" class="absolute inset-0 w-full h-full object-cover">
        @endif
        <span class="absolute inset-0 bg-black/[0.03]"></span>
    </div>

    <div class="flex flex-col flex-1 px-[24px] pt-[24px] pb-[34px]">
        <h3 class="font-sora font-bold text-[20px] leading-[1.2] text-black line-clamp-3 min-h-[48px]">{{ $producto->t('nombre') }}</h3>
        <hr class="mt-auto border-linea">
        <span class="mt-[24px] flex items-center justify-center h-[44px] rounded-[22px] border border-dark text-dark text-[14px] font-bold leading-[1.5]">Consultar</span>
    </div>
</a>
