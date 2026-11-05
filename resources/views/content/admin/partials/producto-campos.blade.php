{{-- Familia --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Familia (línea de producto)</label>
    <select name="familia_id" class="w-full border border-gray-300 rounded px-4 py-2">
        <option value="">-- Sin familia --</option>
        @foreach ($familias as $opcion)
            <option value="{{ $opcion->id }}" @selected(old('familia_id', $item->familia_id) == $opcion->id)>
                {{ \App\Support\Idiomas::texto($opcion->nombre) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Orden</label>
    <input type="text" name="orden" value="{{ old('orden', $item->orden) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Código --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Código (columna Código de la hoja Conjunto del Excel; la importación lo usa para encontrar el producto)</label>
    <input type="text" name="codigo" value="{{ old('codigo', $item->codigo) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Nombre --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $item->nombre) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Foto --}}
<div class="mb-4 campo-archivo">
    <label class="block text-gray-700 font-medium mb-2">Foto principal (recomendado 600x496) (2MB máximo)</label>
    <img src="{{ $item->foto }}" alt=""
         class="vista-previa mb-3 max-w-[300px] max-h-[200px] object-cover rounded {{ empty($item->foto) ? 'hidden' : '' }}">
    <input type="file" name="foto" accept="image/*" onchange="previsualizarImagen(this)"
           class="w-full border border-gray-300 rounded px-4 py-2">
    @if (!empty($item->foto))
        <label class="inline-flex items-center gap-2 mt-2 text-sm text-gray-600">
            <input type="checkbox" name="quitar_foto" value="1"> Quitar Foto
        </label>
    @endif
</div>

{{-- Caracteristicas --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Características</label>
    <textarea name="caracteristicas" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('caracteristicas', $item->caracteristicas) }}</textarea>
</div>

{{-- Medidas --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Medidas</label>
    <textarea name="medidas" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('medidas', $item->medidas) }}</textarea>
</div>

{{-- Packaging --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Packaging</label>
    <input type="text" name="packaging" value="{{ old('packaging', $item->packaging) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Apto para --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Apto para</label>
    <select name="apto" class="w-full border border-gray-300 rounded px-4 py-2">
        <option value="">-- Sin especificar --</option>
        @foreach (\App\Models\Producto::APTOS as $valor => $texto)
            <option value="{{ $valor }}" @selected(old('apto', $item->apto) === $valor)>{{ $texto }}</option>
        @endforeach
    </select>
</div>

{{-- Nota --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Nota</label>
    <input type="text" name="nota" value="{{ old('nota', $item->nota) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Tapas --}}
@php
    $tapasFilas = array_values(old('tapas', $item->tapas ?? []));
    $filaTapa = function ($i, $fila) {
        $v = fn ($k) => e($fila[$k] ?? '');
        $oculto = empty($fila['foto']) ? 'hidden' : '';
        // Selector de grupo: si el valor guardado no es uno de los tipos (p. ej. viene del Excel), se conserva como opción extra
        $actual = trim((string) ($fila['grupo'] ?? ''));
        $tipos = \App\Models\Producto::GRUPOS_TAPA;
        $coincide = collect($tipos)->first(fn ($t) => mb_strtolower($t) === mb_strtolower($actual));
        if ($actual !== '' && !$coincide) {
            $tipos[] = $actual;
        }
        $opciones = '<option value="">-- Elegir tipo de tapa --</option>';
        foreach ($tipos as $tipo) {
            $sel = $tipo === ($coincide ?: $actual) ? 'selected' : '';
            $opciones .= '<option value="' . e($tipo) . '" ' . $sel . '>' . e($tipo) . '</option>';
        }
        return <<<HTML
        <div class="fila-lista border rounded p-3 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Grupo (tipo de tapa; las filas con el mismo grupo se muestran juntas)</label>
                    <select name="tapas[{$i}][grupo]" class="w-full border border-gray-300 rounded px-4 py-2">{$opciones}</select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Color</label>
                    <input type="text" name="tapas[{$i}][color]" value="{$v('color')}" class="w-full border border-gray-300 rounded px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Código de tapa (Excel)</label>
                    <input type="text" name="tapas[{$i}][codigo]" value="{$v('codigo')}" class="w-full border border-gray-300 rounded px-4 py-2">
                </div>
                <div class="campo-archivo">
                    <label class="block text-sm text-gray-600 mb-1">Foto (recomendado 600x496, reemplaza la foto principal al elegirla)</label>
                    <input type="hidden" name="tapas[{$i}][foto_actual]" value="{$v('foto')}">
                    <img src="{$v('foto')}" alt="" class="vista-previa h-[80px] object-cover rounded mb-2 {$oculto}">
                    <input type="file" name="tapas[{$i}][foto]" accept="image/*" onchange="previsualizarImagen(this)" class="w-full border border-gray-300 rounded px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Descripción (texto explicativo de la tapa)</label>
                    <textarea name="tapas[{$i}][descripcion]" rows="3" class="w-full border border-gray-300 rounded px-4 py-2">{$v('descripcion')}</textarea>
                </div>
            </div>
            <button type="button" onclick="quitarFila(this)" class="mt-2 text-sm text-red-600 hover:underline">Quitar fila</button>
        </div>
        HTML;
    };
@endphp
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Tapas (una fila por tipo y color)</label>
    <div id="lista-tapas" class="space-y-3" data-siguiente="{{ count($tapasFilas) }}">
        @foreach ($tapasFilas as $i => $fila)
            {!! $filaTapa($i, $fila) !!}
        @endforeach
    </div>

    <template id="lista-tapas-template">
        {!! $filaTapa('__i__', []) !!}
    </template>

    <button type="button" onclick="agregarFila('lista-tapas')"
            class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-50">
        <i class="fa-solid fa-plus mr-1"></i> Agregar fila
    </button>
</div>
