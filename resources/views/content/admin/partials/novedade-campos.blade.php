{{-- Categoria --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Categoría</label>
    <select name="categoria_id" class="w-full border border-gray-300 rounded px-4 py-2">
        <option value="">-- Sin categoría --</option>
        @foreach ($categorias as $opcion)
            <option value="{{ $opcion->id }}" @selected(old('categoria_id', $item->categoria_id) == $opcion->id)>
                {{ \App\Support\Idiomas::texto($opcion->nombre ?? $opcion->titulo) ?? '#' . $opcion->id }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Orden</label>
    <input type="text" name="orden" value="{{ old('orden', $item->orden) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Fecha --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Fecha</label>
    <input type="date" name="fecha" value="{{ old('fecha', optional($item->fecha)->format('Y-m-d')) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Titulo --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Título</label>
    <input type="text" name="titulo" value="{{ old('titulo', $item->titulo) }}"
           class="w-full border border-gray-300 rounded px-4 py-2">
</div>

{{-- Foto --}}
<div class="mb-4 campo-archivo">
    <label class="block text-gray-700 font-medium mb-2">Foto (recomendado 392x294) (2MB máximo)</label>
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

{{-- Texto --}}
<div class="mb-4">
    <label class="block text-gray-700 font-medium mb-2">Texto</label>
    <textarea name="texto" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('texto', $item->texto) }}</textarea>
</div>
