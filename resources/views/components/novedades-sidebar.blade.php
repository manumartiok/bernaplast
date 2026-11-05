{{-- Barra lateral de Novedades: buscador, categorías y archivo por mes (con cantidad de notas) --}}
@php
    $activas = App\Models\Novedade::where('active', true);

    $categorias = App\Models\Categoria::where('active', true)->orderBy('orden')->get()
        ->map(fn ($c) => ['id' => $c->id, 'nombre' => $c->t('nombre'), 'cantidad' => (clone $activas)->where('categoria_id', $c->id)->count()])
        ->filter(fn ($c) => $c['cantidad'] > 0);

    $nombresMes = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $meses = (clone $activas)->whereNotNull('fecha')->orderByDesc('fecha')->get(['fecha'])->pluck('fecha')
        ->groupBy(fn ($fecha) => $fecha->format('Y-m'))
        ->map(function ($fechas, $clave) use ($nombresMes) {
            [$anio, $mes] = explode('-', $clave);
            $nombre = $nombresMes[(int) $mes] . ($anio != date('Y') ? " {$anio}" : '');

            return ['clave' => $clave, 'nombre' => $nombre, 'cantidad' => $fechas->count()];
        });

    $fila = 'flex items-center justify-between h-[40px] px-[16px] border-b border-linea-clara text-[16px] text-[#333]';
@endphp

<aside class="w-full max-w-[392px] flex flex-col gap-[34px]">
    <form action="{{ route('novedades') }}" method="GET"
          class="flex items-center h-[46px] pl-[16px] pr-[16px] bg-white border border-linea-clara rounded-[24px]">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar"
               class="flex-1 min-w-0 bg-transparent text-[16px] leading-[1.5] text-[#333] placeholder:text-[#333] focus:outline-none">
        <button type="submit" aria-label="Buscar">
            <img src="{{ asset('assets/img/icons/search-celeste.svg') }}" alt="" width="24" height="24">
        </button>
    </form>

    @foreach (['Categorías' => [$categorias, 'categoria', 'id'], 'Archivo' => [$meses, 'mes', 'clave']] as $titulo => [$lista, $parametro, $clave])
        @if ($lista->isNotEmpty())
            <details open class="group {{ $loop->last ? 'mt-[30px]' : '' }}">
                <summary class="flex items-center justify-between h-[46px] px-[16px] bg-fondo rounded-[4px] cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span class="font-sora font-bold text-[20px] leading-[1.2]">{{ $titulo }}</span>
                    <img src="{{ asset('assets/img/icons/chevron-dark.svg') }}" alt="" width="24" height="24" class="group-open:rotate-180 transition-transform">
                </summary>
                <ul>
                    @foreach ($lista as $item)
                        @php $activo = (string) request($parametro) === (string) $item[$clave]; @endphp
                        <li>
                            <a href="{{ route('novedades', [$parametro => $item[$clave]]) }}" class="{{ $fila }} {{ $activo ? 'font-bold text-dark' : '' }}">
                                <span>{{ $item['nombre'] }}</span>
                                <span class="font-semibold">{{ $item['cantidad'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
        @endif
    @endforeach
</aside>
