@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Home</h3>
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

            <form method="POST" action="{{ route('adm.home-update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Titulo Productos --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título productos destacados</label>
                    <input type="text" name="titulo_productos" value="{{ old('titulo_productos', $item->titulo_productos) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto Productos --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo productos destacados</label>
                    <input type="text" name="texto_productos" value="{{ old('texto_productos', $item->texto_productos) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Foto Nosotros --}}
                <div class="mb-4 campo-archivo">
                    <label class="block text-gray-700 font-medium mb-2">Foto quiénes somos (recomendado 599x400) (2MB máximo)</label>
                    <img src="{{ $item->foto_nosotros }}" alt=""
                         class="vista-previa mb-3 max-w-[300px] max-h-[200px] object-cover rounded {{ empty($item->foto_nosotros) ? 'hidden' : '' }}">
                    <input type="file" name="foto_nosotros" accept="image/*" onchange="previsualizarImagen(this)"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                    @if (!empty($item->foto_nosotros))
                        <label class="inline-flex items-center gap-2 mt-2 text-sm text-gray-600">
                            <input type="checkbox" name="quitar_foto_nosotros" value="1"> Quitar Foto Nosotros
                        </label>
                    @endif
                </div>

                {{-- Titulo Nosotros --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título quiénes somos</label>
                    <input type="text" name="titulo_nosotros" value="{{ old('titulo_nosotros', $item->titulo_nosotros) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto Nosotros --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Texto quiénes somos</label>
                    <textarea name="texto_nosotros" rows="6" class="ckeditor w-full border border-gray-300 rounded px-4 py-2">{{ old('texto_nosotros', $item->texto_nosotros) }}</textarea>
                </div>

                {{-- Titulo Novedades --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título novedades</label>
                    <input type="text" name="titulo_novedades" value="{{ old('titulo_novedades', $item->titulo_novedades) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto Novedades --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo novedades</label>
                    <input type="text" name="texto_novedades" value="{{ old('texto_novedades', $item->texto_novedades) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Titulo Clientes --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título clientes</label>
                    <input type="text" name="titulo_clientes" value="{{ old('titulo_clientes', $item->titulo_clientes) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                {{-- Texto Clientes --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Subtítulo clientes</label>
                    <input type="text" name="texto_clientes" value="{{ old('texto_clientes', $item->texto_clientes) }}"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                </div>

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">Actualizar</button>
            </form>
        </div>
    </div>

    @include('includes.modulo-scripts')
    @include('includes.ckeditor-init')
@endsection
