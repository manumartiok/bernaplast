@use('App\Support\Idiomas')

@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

   {{-- cabezal --}}
    <div>
        <h3 class="text-[20px] font-bold text-gray-500">Editar banner {{ $titulo }}</h3>
        <hr class="mx-6">
    </div>

    {{-- Editor --}}
    <div class="mx-20 pt-6">
        <div class="border border-rounded p-4">
            <div>
                <h3 class="text-[18px] font-semibold text-gray-600">Editar banner</h3>
            </div>
            <hr class="my-3">

            <form method="POST" action="{{ route('adm.banner-update', $banner->seccion) }}">
                @csrf

                {{-- Texto --}}
                @if (Idiomas::esMultiple())
                    @foreach (Idiomas::todos() as $idioma)
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Texto ({{ Idiomas::nombre($idioma) }})</label>
                            <input type="text" name="texto[{{ $idioma }}]" value="{{ $banner->texto[$idioma] ?? '' }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2">
                        </div>
                    @endforeach
                @else
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Texto</label>
                        <input type="text" name="texto" value="{{ $banner->texto ?? '' }}"
                               class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>
                @endif

                {{-- Subtítulo --}}
                @if (Idiomas::esMultiple())
                    @foreach (Idiomas::todos() as $idioma)
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Subtítulo ({{ Idiomas::nombre($idioma) }})</label>
                            <input type="text" name="subtitulo[{{ $idioma }}]" value="{{ $banner->subtitulo[$idioma] ?? '' }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2">
                        </div>
                    @endforeach
                @else
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Subtítulo</label>
                        <input type="text" name="subtitulo" value="{{ $banner->subtitulo ?? '' }}"
                               class="w-full border border-gray-300 rounded px-4 py-2">
                    </div>
                @endif

                <button type="submit" class="border rounded p-4 bg-white hover:bg-gray-50 transition-colors">Actualizar</button>
            </form>
        </div>
    </div>

    @include('includes.modulo-scripts')
@endsection
