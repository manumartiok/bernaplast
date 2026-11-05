@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Desarrollo de producto</h3>
        <hr class="mx-6">
    </div>

    {{-- Editor --}}
    <div class="mx-20 pt-6">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar contenido</h3>
            </div>
            <hr class="my-3">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('adm.desarrollo-update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Foto --}}
                <div class="mb-4 campo-archivo">
                    <label class="block text-gray-700 font-medium mb-2">Foto (recomendado 599x500) (2MB máximo)</label>
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
                    <label class="block text-gray-700 font-medium mb-2">Texto principal</label>
                    <textarea name="texto" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('texto', $item->texto) }}</textarea>
                </div>

                {{-- Pasos --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">¿Cómo funciona? (pasos con check)</label>
                    <div id="lista-pasos" class="space-y-3" data-siguiente="{{ count($item->pasos ?? []) }}">
                        @foreach (array_values($item->pasos ?? []) as $i => $fila)
                            <div class="fila-lista border rounded p-3 bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Texto</label>
                                        <textarea name="pasos[{{ $i }}][texto]" rows="3" class="w-full border border-gray-300 rounded px-4 py-2">{{ $fila['texto'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <button type="button" onclick="quitarFila(this)" class="mt-2 text-sm text-red-600 hover:underline">Quitar fila</button>
                            </div>
                        @endforeach
                    </div>

                    <template id="lista-pasos-template">
                        <div class="fila-lista border rounded p-3 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Texto</label>
                                    <textarea name="pasos[__i__][texto]" rows="3" class="w-full border border-gray-300 rounded px-4 py-2"></textarea>
                                </div>
                            </div>
                            <button type="button" onclick="quitarFila(this)" class="mt-2 text-sm text-red-600 hover:underline">Quitar fila</button>
                        </div>
                    </template>

                    <button type="button" onclick="agregarFila('lista-pasos')"
                            class="mt-3 border rounded px-4 py-2 bg-white hover:bg-gray-50">
                        <i class="fa-solid fa-plus mr-1"></i> Agregar fila
                    </button>
                </div>

                {{-- Texto2 --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto final</label>
                    <textarea name="texto2" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('texto2', $item->texto2) }}</textarea>
                </div>

                {{-- Titulo Matricerias --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título matricerías</label>
                    <input type="text" name="titulo_matricerias" value="{{ old('titulo_matricerias', $item->titulo_matricerias) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">Actualizar</button>
            </form>
        </div>
    </div>

    @include('includes.modulo-scripts')
    @include('includes.ckeditor-init')
@endsection
