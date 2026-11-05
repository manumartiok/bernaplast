@php
    $contacto = App\Models\Contacto::first() ?? new App\Models\Contacto();

    // Mapa: acepta el link de Google Maps o el <iframe> completo que da "Compartir > Insertar mapa"
    $mapa = $contacto->ubicacion;
    if ($mapa && preg_match('/src=["\']([^"\']+)["\']/', $mapa, $m)) {
        $mapa = $m[1];
    }

    $wa = fn ($numero) => 'https://wa.me/' . preg_replace('/\D+/', '', $numero);

    // Tarjetas de datos: [ícono, título, texto, link]
    $tarjetas = array_filter([
        $contacto->direccion ? ['map-pin', 'Dirección', $contacto->direccion, 'https://maps.google.com/?q=' . urlencode($contacto->direccion)] : null,
        $contacto->mail ? ['mail', 'Email', $contacto->mail, 'mailto:' . $contacto->mail] : null,
        $contacto->telefono ? ['whatsapp', 'Consulta', $contacto->telefono, $wa($contacto->telefono)] : null,
        $contacto->telefono2 ? ['whatsapp', 'Ventas', $contacto->telefono2, $wa($contacto->telefono2)] : null,
    ]);

    $input = 'w-full h-[40px] px-[16px] border border-linea rounded-[20px] text-[14px] leading-[1.5] placeholder:text-dark/50 focus:outline-none focus:border-celeste';
@endphp

@extends('layouts.web')

@section('title', 'Contacto')

@section('content')

<x-banner-seccion seccion="contacto" :migas="['Contacto' => null]" />

{{-- datos de contacto --}}
<section class="contenedor pt-[60px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-[24px]">
    @foreach ($tarjetas as [$icono, $titulo, $texto, $link])
        <a href="{{ $link }}" target="_blank" class="block min-h-[180px] p-[24px] pr-[16px] bg-white border border-linea rounded-[4px]">
            <span class="flex items-center justify-center size-[40px] rounded-full bg-fondo">
                <img src="{{ asset('assets/img/icons/' . $icono . '.svg') }}" alt="" width="20" height="20">
            </span>
            <h2 class="mt-[16px] font-sora font-semibold text-[16px] leading-[1.2]">{{ $titulo }}</h2>
            <p class="mt-[5px] text-[16px] leading-[1.5] tracking-[-0.16px] max-w-[244px] break-words">{{ $texto }}</p>
        </a>
    @endforeach
</section>

{{-- formulario y mapa --}}
<section class="contenedor pt-[60px] pb-[159px] grid grid-cols-1 lg:grid-cols-[600px_552px] gap-x-[48px] gap-y-[40px]">
    <div>
        <h2 class="font-sora font-bold text-[32px] leading-[1.2]">Escribinos un mensaje</h2>

        @if (session('success'))
            <div class="mt-[24px] px-[16px] py-[12px] rounded-[12px] bg-celeste/10 text-[14px] font-bold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-[24px] px-[16px] py-[12px] rounded-[12px] bg-red-50 text-red-700 text-[14px]">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('form.contacto') }}" method="POST" class="mt-[28px] grid grid-cols-1 sm:grid-cols-2 gap-x-[24px] gap-y-[24px]">
            @csrf

            <label class="block sm:col-span-2">
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

            <label class="block sm:col-span-2">
                <span class="block text-[16px] leading-[1.5]">Asunto</span>
                <input type="text" name="asunto" value="{{ old('asunto') }}" placeholder="¿En qué te podemos ayudar?" class="mt-[4px] {{ $input }}">
            </label>

            <label class="block sm:col-span-2">
                <span class="block text-[16px] leading-[1.5]">Mensaje</span>
                <textarea name="mensaje" rows="6" placeholder="Contanos qué necesitás"
                          class="block mt-[4px] h-[183px] py-[10px] resize-none {{ str_replace('h-[40px]', '', $input) }}">{{ old('mensaje') }}</textarea>
            </label>

            <p class="mt-[20px] sm:mt-[20px] text-[16px] leading-[1.5] text-[#303030] self-center">*Campos obligatorios</p>
            <button type="submit" class="btn-ppal w-full sm:w-[288px] mt-[20px]">Enviar consulta</button>
        </form>
    </div>

    <div class="w-full max-w-[552px] h-[400px] lg:h-[635px] rounded-[12px] overflow-hidden bg-fondo">
        @if ($mapa)
            <iframe src="{{ $mapa }}" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa"></iframe>
        @elseif ($contacto->foto)
            <img src="{{ $contacto->foto }}" alt="Bernaplast" class="w-full h-full object-cover">
        @endif
    </div>
</section>

@endsection
