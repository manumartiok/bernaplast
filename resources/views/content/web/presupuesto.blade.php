@php
    $items = App\Http\Controllers\PresupuestoController::itemsConProducto(session('presupuesto', []));

    // Opciones de cada ítem para los selectores: grupo de tapa => colores
    $opciones = [];
    foreach ($items as $id => $item) {
        $opciones[$id] = $item['producto']->gruposDeTapas()->map(fn ($filas) => array_column($filas, 'color'))->all();
    }

    $etiqueta = 'text-[12px] font-bold leading-[1.5] text-dark/60';
    $caja = 'h-[41px] w-[124px] bg-white border border-linea rounded-[12px] text-[14px] leading-[1.5] text-[#1C1710]';
    $select = "appearance-none pl-[15px] pr-[36px] bg-no-repeat bg-[right_12px_center] {$caja}";
    $input = 'w-full h-[40px] px-[16px] border border-linea rounded-[20px] text-[14px] leading-[1.5] placeholder:text-dark/50 focus:outline-none focus:border-celeste';
@endphp

@extends('layouts.web')

@section('title', 'Solicitud de presupuesto')

@section('content')

<x-banner-seccion seccion="presupuesto" :migas="['Presupuesto' => null]" />

<section class="contenedor pt-[53px] pb-[126px]">

    @if (session('success'))
        <div class="mb-[24px] px-[16px] py-[12px] rounded-[12px] bg-celeste/10 text-[14px] font-bold">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-[24px] px-[16px] py-[12px] rounded-[12px] bg-red-50 text-red-700 text-[14px]">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div id="sin-items" class="{{ $items ? 'hidden' : '' }} py-[40px] border-y border-linea">
        <p class="text-[16px] leading-[1.5]">Todavía no agregaste productos a tu solicitud.</p>
        <a href="{{ route('productos') }}" class="btn-ppal w-[184px] mt-[16px]">VER PRODUCTOS</a>
    </div>

    @if ($items)
        {{-- productos elegidos --}}
        <div id="items" class="border-t border-linea">
            @foreach ($items as $id => $item)
                @php $grupos = $opciones[$id] ?? []; @endphp
                <div class="item py-[8px] border-b border-linea grid grid-cols-[80px_1fr] lg:grid-cols-[80px_395px_minmax(420px,1fr)_0px_24px] gap-x-[24px] gap-y-[12px] items-center lg:pr-[33px]"
                     data-id="{{ $id }}" data-grupos='@json($grupos)'>
                    <div class="size-[80px] rounded-[6px] overflow-hidden bg-fondo">
                        @if ($item['producto']->foto)
                            <img src="{{ $item['producto']->foto }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>

                    <a href="{{ route('productos.detalle', $item['producto']->id) }}" class="font-sora font-bold text-[16px] leading-[1.2] max-w-[148px]">
                        {{ $item['producto']->t('nombre') }}
                    </a>

                    <div class="col-span-2 lg:col-span-1 lg:self-start lg:pt-[7px] flex flex-wrap gap-[24px]">
                        @if ($grupos)
                            <label>
                                <span class="block {{ $etiqueta }}">TAPA</span>
                                <select class="sel-tapa block mt-[1px] !w-auto min-w-[124px] max-w-full {{ $select }}" style="background-image: url('{{ asset('assets/img/icons/chevron-down.svg') }}')">
                                    @foreach ($grupos as $grupo => $colores)
                                        <option value="{{ $grupo }}" title="{{ $grupo }}" @selected($item['tapa'] === $grupo)>{{ App\Models\Producto::grupoCorto($grupo) }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>
                                <span class="block {{ $etiqueta }}">COLOR</span>
                                <select class="sel-color block mt-[1px] {{ $select }}" style="background-image: url('{{ asset('assets/img/icons/chevron-down.svg') }}')">
                                    @foreach ($grupos[$item['tapa']] ?? reset($grupos) as $color)
                                        <option value="{{ $color }}" @selected($item['color'] === $color)>{{ $color }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif

                        <div>
                            <span class="block {{ $etiqueta }}">CANTIDAD</span>
                            <div class="mt-[1px] flex items-center justify-between px-[15px] {{ $caja }}">
                                <button type="button" class="cant-menos" aria-label="Restar">
                                    <img src="{{ asset('assets/img/icons/minus.svg') }}" alt="" width="16" height="16">
                                </button>
                                <input type="number" min="1" value="{{ $item['cantidad'] }}"
                                       class="cant w-[50px] text-center bg-transparent focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" class="cant-mas" aria-label="Sumar">
                                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="" width="16" height="16">
                                </button>
                            </div>
                        </div>
                    </div>

                    <span class="hidden lg:block"></span>

                    <button type="button" class="quitar justify-self-end col-start-2 row-start-1 lg:col-start-auto lg:row-start-auto" aria-label="Quitar">
                        <img src="{{ asset('assets/img/icons/trash.svg') }}" alt="" width="24" height="24">
                    </button>
                </div>
            @endforeach
        </div>

        {{-- datos de contacto --}}
        <form id="form-presupuesto" action="{{ route('presupuesto.enviar') }}" method="POST" enctype="multipart/form-data"
              class="mt-[52px] grid grid-cols-1 md:grid-cols-2 gap-x-[24px] gap-y-[24px]">
            @csrf

            <label class="block">
                <span class="block text-[16px] leading-[1.5]">Nombre y apellido*</span>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Juan Perez" required class="mt-[4px] {{ $input }}">
            </label>

            <label class="block">
                <span class="block text-[16px] leading-[1.5]">Teléfono*</span>
                <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Código de área + 12345678" required class="mt-[4px] {{ $input }}">
            </label>

            <label class="block">
                <span class="block text-[16px] leading-[1.5]">Email*</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="juanperez@mail.com" required class="mt-[4px] {{ $input }}">
            </label>

            <label class="block cursor-pointer">
                <span class="block text-[16px] leading-[1.5]">Adjuntar archivo</span>
                <span class="mt-[4px] flex items-center justify-between pr-[24px] {{ $input }}">
                    <span id="archivo-nombre" class="truncate text-dark/50">Seleccionar archivo</span>
                    <img src="{{ asset('assets/img/icons/upload.svg') }}" alt="" width="24" height="24" class="shrink-0">
                </span>
                <input id="archivo" type="file" name="archivo" class="hidden">
            </label>

            <div class="md:col-span-2 mt-[8px] flex flex-col-reverse sm:flex-row items-start sm:items-center justify-between gap-[16px]">
                <p class="text-[16px] leading-[1.5]">*Campos obligatorios</p>
                <button type="submit" class="btn-ppal w-[288px]">Enviar solicitud</button>
            </div>
        </form>
    @endif
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const urlActualizar = @json(route('presupuesto.actualizar', '__id__'));
    const urlQuitar = @json(route('presupuesto.quitar', '__id__'));

    const enviar = (url, datos = {}) => fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(datos),
    }).then(r => r.json());

    document.querySelectorAll('.item').forEach(fila => {
        const id = fila.dataset.id;
        const grupos = JSON.parse(fila.dataset.grupos || '{}');
        const tapa = fila.querySelector('.sel-tapa');
        const color = fila.querySelector('.sel-color');
        const cant = fila.querySelector('.cant');
        let espera;

        const guardar = () => enviar(urlActualizar.replace('__id__', id), {
            tapa: tapa ? tapa.value : null,
            color: color ? color.value : null,
            cantidad: Math.max(1, parseInt(cant.value) || 1),
        });
        const guardarLuego = () => { clearTimeout(espera); espera = setTimeout(guardar, 400); };

        if (tapa) {
            tapa.addEventListener('change', () => {
                color.innerHTML = '';
                (grupos[tapa.value] || []).forEach(c => color.add(new Option(c, c)));
                guardar();
            });
            color.addEventListener('change', guardar);
        }

        fila.querySelector('.cant-menos').addEventListener('click', () => { cant.value = Math.max(1, (parseInt(cant.value) || 1) - 1); guardarLuego(); });
        fila.querySelector('.cant-mas').addEventListener('click', () => { cant.value = (parseInt(cant.value) || 0) + 1; guardarLuego(); });
        cant.addEventListener('input', guardarLuego);

        fila.querySelector('.quitar').addEventListener('click', () => {
            enviar(urlQuitar.replace('__id__', id)).then(r => {
                fila.remove();
                if (r.quedan === 0) {
                    document.getElementById('items').remove();
                    document.getElementById('form-presupuesto').remove();
                    document.getElementById('sin-items').classList.remove('hidden');
                }
            });
        });
    });

    const archivo = document.getElementById('archivo');
    if (archivo) {
        archivo.addEventListener('change', () => {
            const nombre = document.getElementById('archivo-nombre');
            nombre.textContent = archivo.files[0] ? archivo.files[0].name : 'Seleccionar archivo';
            nombre.classList.toggle('text-dark/50', !archivo.files[0]);
        });
    }
});
</script>
@endsection
