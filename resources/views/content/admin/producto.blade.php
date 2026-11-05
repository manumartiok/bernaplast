@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div class="flex items-center justify-between px-4 pt-4 pb-2">
        <h3 class="text-[20px] font-bold text-gray-500">Productos</h3>
        <button type="button" onclick="document.getElementById('modalImport').classList.remove('hidden')"
            class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition">
            <i class="fa-solid fa-file-excel mr-1"></i> Importar Excel
        </button>
    </div>
    <hr class="mx-6 mb-2">

   {{-- Modal Import --}}
   <div id="modalImport" class="{{ $errors->has('archivo') ? '' : 'hidden' }} fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-1">Importar productos</h2>
            <p class="text-sm text-gray-500 mb-4">
                Excel de códigos con las hojas Conjunto y Tapas. Cada fila de Conjunto es un producto: el <strong>Código</strong> es la referencia,
                la <strong>Familia</strong> asigna la familia y la <strong>Descripción</strong> es el nombre.
                El código de tapa toma de la hoja Tapas el color y si es con guarnición y/o con precinto (si tiene las dos, se crean los dos tipos).
                El resto de los campos se cargan a mano y la importación no los toca.
            </p>

            @error('archivo')
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('adm.producto-importar') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Archivo (.xls, .xlsx)</label>
                    <input type="file" name="archivo" accept=".xlsx,.xls" required
                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-2 cursor-pointer
                               file:mr-3 file:py-1 file:px-3 file:rounded file:border-0
                               file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button"
                        onclick="document.getElementById('modalImport').classList.add('hidden')"
                        class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                        Importar
                    </button>
                </div>
            </form>
        </div>
   </div>

   {{-- Resultado de la importación --}}
   @if ($importacion = session('importacion'))
   <div class="px-4 pt-4">
        <div class="border rounded-2xl shadow bg-white p-4">
                <div>
                    <p class="text-sm font-semibold text-green-700">
                        Se crearon {{ $importacion['creados'] }} productos y se actualizaron {{ $importacion['actualizados'] }}.
                        @if ($importacion['familias_nuevas'])
                            Familias nuevas: {{ implode(', ', $importacion['familias_nuevas']) }}.
                        @endif
                    </p>

                    @if ($importacion['avisos'])
                        <details class="mt-3 text-sm">
                            <summary class="cursor-pointer font-semibold text-amber-700">Avisos sobre el Excel ({{ count($importacion['avisos']) }})</summary>
                            <ul class="mt-2 list-disc pl-6 text-gray-600 space-y-1">
                                @foreach ($importacion['avisos'] as $aviso)
                                    <li>{{ $aviso }}</li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                </div>
        </div>
   </div>
   @endif

   {{-- Listado --}}
   <div class="p-4">
        <div class="overflow-x-auto rounded-2xl shadow">
            <div class="flex items-center justify-between px-6 py-3 border rounded gap-4">
                {{-- Buscador --}}
                <form method="GET" action="{{ route('adm.producto') }}" class="flex items-center gap-2 flex-1 flex-wrap">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Nombre, código o código de tapa..."
                           class="w-full max-w-xs border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <select name="familia_id"
                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Todas las familias</option>
                        @foreach ($familias as $familia)
                            <option value="{{ $familia->id }}" @selected(request('familia_id') == $familia->id)>
                                {{ \App\Support\Idiomas::texto($familia->nombre) }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-lg transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    @if ($filtrando)
                        <a href="{{ route('adm.producto') }}" class="text-sm text-gray-500 hover:text-red-500 transition">
                            <i class="fa-solid fa-xmark"></i> Limpiar
                        </a>
                        <span class="text-sm text-gray-500">{{ $items->count() }} resultados (el orden por arrastre se desactiva mientras filtrás)</span>
                    @endif
                </form>

                <a href="{{ route('adm.producto-creador') }}"
                   class="hover:text-gray-400 hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <table class="min-w-full border border-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr class="text-gray-700 text-sm uppercase">
                        <th class="px-4 py-3 text-left">Orden</th>
                        <th class="px-4 py-3 text-center">Foto</th>
                        <th class="px-4 py-3 text-left">Familia</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Packaging</th>
                        <th class="px-4 py-3 text-left">Apto para</th>
                        <th class="px-4 py-3 text-center">Activo</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                        <th class="px-4 py-3 text-center">Destacado</th>
                    </tr>
                </thead>

                <tbody id="producto-tabla" class="text-gray-600">
                    @foreach($items as $item)
                        <tr class="border-t hover:bg-gray-50 cursor-move" data-id="{{ $item->id }}">
                            <td class="px-4 py-3 text-left">
                                {{ $item->orden }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if (!empty($item->foto))
                                    <img src="{{ asset($item->foto) }}" alt="" class="w-[60px] h-[60px] object-cover mx-auto rounded">
                                @endif
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ \App\Support\Idiomas::texto($item->familia?->nombre) }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ $item->nombre }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ $item->packaging }}
                            </td>
                            <td class="px-4 py-3 text-left">
                                {{ \App\Models\Producto::APTOS[$item->apto] ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('adm.producto-switch', $item->id) }}"
                                   class="badge {{ $item->active ? 'bg-green-400' : 'bg-red-400' }} text-white px-4 py-2 rounded">
                                    {{ $item->active ? 'Activo' : 'Inactivo' }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('adm.producto-editor', $item->id) }}" class="text-blue-600 hover:underline font-bold">Editar</a>
                                <span>|</span>
                                <a href="{{ route('adm.producto-destroy', $item->id) }}" class="text-red-600 hover:underline font-bold"
                                   onclick="return confirm('¿Eliminar este registro?')">Borrar</a>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('adm.producto-destacado', $item->id) }}"
                                   class="badge {{ $item->destacado ? 'bg-green-400' : 'bg-red-400' }} text-white px-4 py-2 rounded">
                                    {{ $item->destacado ? 'Destacado' : 'No destacado' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (!$filtrando)
            activarOrdenDragDrop('#producto-tabla', '{{ route('adm.producto-reordenar') }}');
        @else
            // Con filtro solo se ven algunas filas: reordenarlas mezclaría el orden del resto
            document.querySelectorAll('#producto-tabla tr').forEach(tr => tr.classList.remove('cursor-move'));
        @endif
    });
</script>

@endsection
