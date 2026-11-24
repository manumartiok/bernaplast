@php
    $producto = App\Models\Producto::where('active', true)->findOrFail($id);

    // Tapas por grupo: nombre del grupo => filas. En el HTML y el script cada grupo se identifica por su posición.
    $grupos = $producto->gruposDeTapas()->values();
    $nombresGrupo = $producto->gruposDeTapas()->keys();

    // Datos para el script: una entrada por tapa
    $tapas = [];
    foreach ($grupos as $g => $filas) {
        foreach ($filas as $i => $fila) {
            $tapas[] = [
                'grupo'       => $g,
                'indice'      => $i,
                'color'       => $fila['color'],
                'foto'        => $fila['foto'] ?? null,
                // Foto que acompaña al texto explicativo; si no hay, se usa la del listado
                'fotoDescripcion' => ($fila['foto_descripcion'] ?? null) ?: ($fila['foto'] ?? null),
                'descripcion' => $fila['descripcion'],
            ];
        }
    }

    // Al entrar no hay ninguna tapa elegida: se ve la foto del producto
    $fotoInicial = $producto->foto;

    $etiqueta = 'text-[14px] font-bold leading-[1.5] text-dark/60';
    $caja = 'h-[41px] w-full bg-white border border-linea rounded-[12px] text-[14px] leading-[1.5] text-[#1C1710]';
    $tituloDato = 'text-[20px] font-bold leading-[1.5] tracking-[-0.4px] text-[#303030]';
    $textoDato = 'text-[16px] leading-[1.5] tracking-[-0.32px] text-[#303030]';
@endphp

@extends('layouts.web')

@section('title', $producto->t('nombre'))

@section('content')

<section class="contenedor pt-[24px] pb-[94px]">
    {{-- migas --}}
    <nav class="text-[12px] leading-[1.5] text-[#303030]">
        <a href="{{ route('home') }}" class="font-bold">Inicio</a> /
        <a href="{{ route('productos') }}" class="font-bold">Productos</a> /
        <span>{{ $producto->t('nombre') }}</span>
    </nav>

    <form action="{{ route('presupuesto.agregar') }}" method="POST"
          class="mt-[40px] lg:mt-[78px] grid grid-cols-1 lg:grid-cols-[600px_minmax(0,600px)] gap-x-[24px] gap-y-[31px]">
        @csrf
        <input type="hidden" name="producto_id" value="{{ $producto->id }}">

        {{-- foto principal --}}
        <div class="w-full max-w-[600px] h-[320px] sm:h-[496px] border-2 border-linea rounded-[12px] overflow-hidden bg-white">
            <img id="foto-principal" src="{{ $fotoInicial }}" alt="{{ $producto->t('nombre') }}"
                 class="w-full h-full object-contain {{ $fotoInicial ? '' : 'hidden' }}">
        </div>

        {{-- datos --}}
        <div class="flex flex-col lg:min-h-[496px]">
            <h1 class="lg:mt-[29px] font-sora font-bold text-[32px] leading-[1.2]">{{ $producto->t('nombre') }}</h1>
            <hr class="mt-[5px] border-linea">

            <div class="mt-[17px] grid grid-cols-1 sm:grid-cols-[311px_1fr] gap-y-[11px]">
                @if ($producto->t('caracteristicas'))
                    <div class="flex gap-[9px]">
                        <img src="{{ asset('assets/img/icons/lista.svg') }}" alt="" width="27" height="27" class="w-[27px] h-[27px] mt-[4px] shrink-0 self-start">
                        <div>
                            <h2 class="{{ $tituloDato }}">Características</h2>
                            <div class="{{ $textoDato }}">{!! $producto->t('caracteristicas') !!}</div>
                        </div>
                    </div>
                @endif

                @if ($producto->t('medidas'))
                    <div class="flex gap-[9px] sm:pt-[4px]">
                        <img src="{{ asset('assets/img/icons/ruler.svg') }}" alt="" width="24" height="24" class="w-[24px] h-[24px] mt-[3px] shrink-0 self-start">
                        <div>
                            <h2 class="{{ $tituloDato }}">Medidas</h2>
                            <div class="{{ $textoDato }}">{!! $producto->t('medidas') !!}</div>
                        </div>
                    </div>
                @endif

                @if ($producto->t('packaging'))
                    <div class="flex gap-[9px]">
                        <img src="{{ asset('assets/img/icons/package.svg') }}" alt="" width="24" height="24" class="w-[24px] h-[24px] mt-[3px] shrink-0 self-start">
                        <div>
                            <h2 class="{{ $tituloDato }}">Packaging</h2>
                            <p class="{{ $textoDato }}">{{ $producto->t('packaging') }}</p>
                        </div>
                    </div>
                @endif

                @if (isset(\App\Models\Producto::APTOS[$producto->apto]))
                    @php $aptoContenido = ['icono' => asset('assets/img/icons/apto-' . $producto->apto . '.svg'), 'texto' => \App\Models\Producto::APTOS[$producto->apto]]; @endphp
                    @if ($producto->apto_archivo)
                        <a href="{{ $producto->apto_archivo }}" target="_blank" rel="noopener" class="flex gap-[7px] hover:underline">
                            <img src="{{ $aptoContenido['icono'] }}" alt="" width="26" height="26" class="w-[26px] h-[26px] mt-[3px] shrink-0 self-start">
                            <h2 class="{{ $tituloDato }}">{{ $aptoContenido['texto'] }}</h2>
                        </a>
                    @else
                        <div class="flex gap-[7px]">
                            <img src="{{ $aptoContenido['icono'] }}" alt="" width="26" height="26" class="w-[26px] h-[26px] mt-[3px] shrink-0 self-start">
                            <h2 class="{{ $tituloDato }}">{{ $aptoContenido['texto'] }}</h2>
                        </div>
                    @endif
                @endif
            </div>

            @if ($producto->t('nota'))
                <p class="mt-[24px] lg:mt-auto flex gap-[9px] {{ $textoDato }}">
                    <img src="{{ asset('assets/img/icons/info.svg') }}" alt="" width="24" height="24" class="w-[24px] h-[24px] shrink-0 self-center">
                    <span><span class="font-semibold">NOTA:</span> {{ $producto->t('nota') }}</span>
                </p>
            @endif
            <hr class="mt-[7px] border-linea {{ $producto->t('nota') ? '' : 'lg:mt-auto' }}">
        </div>

        {{-- tapa elegida de cada grupo, con su texto explicativo --}}
        <div class="flex flex-col gap-[24px] order-last lg:order-none">
            @foreach ($grupos as $g => $filas)
                <div data-resumen="{{ $g }}" class="hidden">
                    <h3 class="{{ $etiqueta }} uppercase">{{ $nombresGrupo[$g] }}</h3>
                    <div class="mt-[5px] flex gap-[12px]">
                        <div class="resumen-foto w-[92px] h-[80px] shrink-0 border border-linea rounded-[4px] overflow-hidden bg-fondo">
                            @php $fotoResumen = ($filas[0]['foto_descripcion'] ?? null) ?: ($filas[0]['foto'] ?? null); @endphp
                            @if ($fotoResumen)
                                <img src="{{ $fotoResumen }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <p class="resumen-texto mt-[6px] {{ $textoDato }}">{{ $filas[0]['descripcion'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- tapas, selectores y botón --}}
        <div class="flex flex-col">
            @foreach ($grupos as $g => $filas)
                <div class="mb-[24px]">
                    <h3 class="{{ $etiqueta }} uppercase">{{ $nombresGrupo[$g] }}</h3>
                    <div class="mt-[5px] grid grid-cols-5 gap-[8px] max-w-[496px]">
                        @foreach ($filas as $i => $fila)
                            @php $color = $fila['color']; @endphp
                            <button type="button" class="tapa h-[80px] rounded-[4px] overflow-hidden bg-fondo border border-linea text-[12px] text-dark/70"
                                    data-grupo="{{ $g }}" data-indice="{{ $i }}" title="{{ $color }}">
                                @if (!empty($fila['foto']))
                                    <img src="{{ $fila['foto'] }}" alt="{{ $color }}" class="w-full h-full object-cover">
                                @else
                                    {{ $color }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-[12px] flex flex-wrap items-end justify-between gap-x-[8px] gap-y-[24px]">
                <div class="flex flex-wrap items-end gap-[24px]">
                @if ($grupos->isNotEmpty())
                    <label class="min-w-[124px] max-w-full">
                        <span class="{{ $etiqueta }}">TAPA</span>
                        <select id="select-tapa" name="tapa" class="mt-[7px] appearance-none pl-[15px] pr-[36px] bg-no-repeat bg-[right_12px_center] {{ $caja }}"
                                style="background-image: url('{{ asset('assets/img/icons/chevron-down.svg') }}')">
                            <option value="">Elegir</option>
                            @foreach ($nombresGrupo as $nombre)
                                <option value="{{ $nombre }}" title="{{ $nombre }}">{{ App\Models\Producto::grupoCorto($nombre) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="w-[124px]">
                        <span class="{{ $etiqueta }}">COLOR</span>
                        <select id="select-color" name="color" class="mt-[7px] appearance-none pl-[15px] pr-[36px] bg-no-repeat bg-[right_12px_center] {{ $caja }}"
                                style="background-image: url('{{ asset('assets/img/icons/chevron-down.svg') }}')"><option value="">Elegir</option></select>
                    </label>
                @endif

                <div class="w-[124px]">
                    <span class="{{ $etiqueta }}">CANTIDAD</span>
                    <div class="mt-[7px] flex items-center justify-between px-[15px] {{ $caja }}">
                        <button type="button" id="cantidad-menos" aria-label="Restar">
                            <img src="{{ asset('assets/img/icons/minus.svg') }}" alt="" width="16" height="16">
                        </button>
                        <input id="cantidad" type="number" name="cantidad" value="100" min="1"
                               class="w-[50px] text-center bg-transparent focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" id="cantidad-mas" aria-label="Sumar">
                            <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="" width="16" height="16">
                        </button>
                    </div>
                </div>
                </div>

                <button type="submit" class="btn-ppal w-[156px] h-[39px]">Consultar</button>
            </div>
        </div>
    </form>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tapas = @json($tapas);
    const fotoProducto = @json($producto->foto);
    const fotoPrincipal = document.getElementById('foto-principal');
    const selectTapa = document.getElementById('select-tapa');
    const selectColor = document.getElementById('select-color');

    let seleccion = null; // { grupo, indice } o null si no hay ninguna tapa elegida

    // Estado sin tapa elegida: foto del producto, sin resumen, selectores vacíos
    const deseleccionar = () => {
        seleccion = null;
        document.querySelectorAll('.tapa').forEach(b => {
            b.classList.remove('border-2', 'border-celeste');
            b.classList.add('border-linea');
        });
        if (fotoProducto) {
            fotoPrincipal.src = fotoProducto;
            fotoPrincipal.classList.remove('hidden');
        } else {
            fotoPrincipal.classList.add('hidden');
        }
        document.querySelectorAll('[data-resumen]').forEach(r => r.classList.add('hidden'));
        selectTapa.selectedIndex = 0;
        selectColor.innerHTML = '';
        selectColor.add(new Option('Elegir', ''));
    };

    // Elige una tapa: marca la miniatura, cambia la foto principal, el resumen y los selectores
    const elegir = (grupo, indice) => {
        const tapa = tapas.find(t => t.grupo === grupo && t.indice === indice);
        if (!tapa) return;
        seleccion = { grupo, indice };

        document.querySelectorAll('.tapa').forEach(b => {
            const activa = Number(b.dataset.grupo) === grupo && Number(b.dataset.indice) === indice;
            b.classList.toggle('border-2', activa);
            b.classList.toggle('border-celeste', activa);
            b.classList.toggle('border-linea', !activa);
        });

        const foto = tapa.foto || fotoProducto;
        if (foto) {
            fotoPrincipal.src = foto;
            fotoPrincipal.classList.remove('hidden');
        }

        // Solo se muestra el resumen del grupo elegido
        document.querySelectorAll('[data-resumen]').forEach(r => r.classList.toggle('hidden', Number(r.dataset.resumen) !== grupo));

        const resumen = document.querySelector(`[data-resumen="${grupo}"]`);
        resumen.querySelector('.resumen-foto').innerHTML = tapa.fotoDescripcion ? `<img src="${tapa.fotoDescripcion}" alt="" class="w-full h-full object-cover">` : '';
        resumen.querySelector('.resumen-texto').textContent = tapa.descripcion || '';

        selectTapa.selectedIndex = grupo + 1; // la primera opción es "Elegir"
        selectColor.innerHTML = '';
        tapas.filter(t => t.grupo === grupo).forEach(t => selectColor.add(new Option(t.color, t.color, false, t.indice === indice)));
    };

    if (tapas.length) {
        // Clic en la tapa ya elegida: se deselecciona
        document.querySelectorAll('.tapa').forEach(b => b.addEventListener('click', () => {
            const grupo = Number(b.dataset.grupo), indice = Number(b.dataset.indice);
            if (seleccion && seleccion.grupo === grupo && seleccion.indice === indice) deseleccionar();
            else elegir(grupo, indice);
        }));
        selectTapa.addEventListener('change', () => selectTapa.selectedIndex === 0 ? deseleccionar() : elegir(selectTapa.selectedIndex - 1, 0));
        selectColor.addEventListener('change', () => seleccion && elegir(seleccion.grupo, selectColor.selectedIndex));
        deseleccionar();
    }

    const cantidad = document.getElementById('cantidad');
    document.getElementById('cantidad-menos').addEventListener('click', () => cantidad.value = Math.max(1, (parseInt(cantidad.value) || 1) - 1));
    document.getElementById('cantidad-mas').addEventListener('click', () => cantidad.value = (parseInt(cantidad.value) || 0) + 1);
});
</script>
@endsection
