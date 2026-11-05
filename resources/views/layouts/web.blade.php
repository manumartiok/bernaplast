@php
    $contacto_contenido = App\Models\Contacto::first() ?? new App\Models\Contacto();
    $logos = App\Models\Logo::first() ?? new App\Models\Logo();

    $menu = [
        'nosotros'   => 'Nosotros',
        'productos'  => 'Productos',
        'desarrollo' => 'Desarrollo de producto',
        'calidad'    => 'Calidad',
        'clientes'   => 'Clientes',
        'novedades'  => 'Novedades',
        'contacto'   => 'Contacto',
    ];

    $wa = fn ($numero) => 'https://wa.me/' . preg_replace('/\D+/', '', $numero);
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @php
        $currentRoute = request()->route() ? request()->route()->getName() : null;
        $metadato = \App\Models\Metadato::where('seccion', $currentRoute)->first();
    @endphp

    @if($metadato)
        <meta name="description" content="{{ $metadato->descripcion }}">
        <meta name="keywords" content="{{ $metadato->keywords }}">
    @else
        <meta name="description" content="Bernaplast">
        <meta name="keywords" content="Bernaplast">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('assets/img/bernaplast.png')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Colores y tipografías del Figma
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#001F62',
                        celeste: '#00B1F6',
                        linea: '#DCD7CD',
                        'linea-clara': '#ECEBEB',
                        fondo: '#F9F9F9',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        sora: ['Sora', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .contenedor { @apply w-full max-w-[1366px] mx-auto px-4 md:px-[71px]; }
            .btn-ppal { @apply inline-flex items-center justify-center h-[41px] px-[10px] rounded-[22px] bg-celeste text-white text-[14px] font-bold leading-[1.5]; }
            .btn-sec { @apply inline-flex items-center justify-center h-[41px] w-[128px] px-[10px] rounded-[22px] bg-white border border-celeste text-celeste text-[14px] font-bold leading-[1.5]; }
            .titulo-seccion { @apply font-sora font-bold text-dark text-[32px] md:text-[40px] leading-[1.2]; }
        }
    </style>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full flex flex-col font-sans text-dark bg-white">

    @if($contacto_contenido->whatssap)
    <a href="{{ $wa($contacto_contenido->whatssap) }}" target="_blank" class="fixed bottom-[28px] right-[28px] z-[900]" aria-label="WhatsApp">
        <img src="{{ asset('assets/img/whatsapp-flotante.svg') }}" alt="WhatsApp" width="96" height="96">
    </a>
    @endif

    <!-- navbar -->
    <nav class="sticky top-0 left-0 w-full z-50 bg-white border-b border-linea">
        <div id="navbar" class="contenedor flex items-center justify-between h-[90px] md:pl-[64px] md:pr-[71px]">

            <!-- Logo -->
            <a href="{{route('home')}}" class="block h-[77px] w-[231px] flex-shrink-0">
                <img src="{{ $logos->foto_nav ?: asset('assets/img/logo-nav.png') }}" alt="Bernaplast" class="h-full w-full object-cover">
            </a>

            <!-- Botón Hamburguesa (solo en móvil) -->
            <button id="menu-toggle" class="lg:hidden text-2xl text-dark" aria-label="Menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Menú (desktop) -->
            <div class="hidden lg:flex items-center">
                <div class="flex items-center text-[14px] leading-[1.5]">
                    @foreach ($menu as $ruta => $texto)
                        <a href="{{ route($ruta) }}" class="p-[10px] {{ request()->routeIs($ruta, $ruta . '.*') ? 'font-bold' : '' }}">{{ $texto }}</a>
                    @endforeach
                </div>

                <span class="ml-[11px] text-[14px]">|</span>

                <!-- Buscador -->
                <form id="buscador" action="{{ route('productos') }}" method="GET" class="flex items-center ml-[14px]">
                    <input id="buscador-input" type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar productos"
                           class="hidden w-[180px] h-[34px] px-3 mr-1 border border-linea rounded-[22px] text-[14px] focus:outline-none focus:border-celeste">
                    <button id="buscador-toggle" type="button" class="flex items-center justify-center size-[40px]" aria-label="Buscar">
                        <img src="{{ asset('assets/img/icons/search.svg') }}" alt="" width="20" height="20">
                    </button>
                </form>

                <a href="{{ route('presupuesto') }}" class="relative ml-[16px] {{ request()->routeIs('presupuesto') ? 'btn-ppal w-[128px]' : 'btn-sec' }}">
                    Presupuesto
                    @if (count(session('presupuesto', [])))
                        <span class="absolute -top-[6px] -right-[6px] min-w-[20px] h-[20px] px-[5px] rounded-full bg-dark text-white text-[11px] leading-[20px] text-center">{{ count(session('presupuesto', [])) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Menú mobile -->
        <div id="mobile-menu" class="hidden lg:hidden absolute top-[90px] left-0 w-full bg-white shadow-md">
            <div class="flex flex-col items-center gap-3 py-6 text-[16px]">
                @foreach ($menu as $ruta => $texto)
                    <a href="{{ route($ruta) }}" class="{{ request()->routeIs($ruta, $ruta . '.*') ? 'font-bold' : '' }}">{{ $texto }}</a>
                @endforeach

                <form action="{{ route('productos') }}" method="GET" class="flex items-center w-full max-w-[260px] mt-2">
                    <input type="text" name="buscar" placeholder="Buscar productos"
                           class="flex-1 h-[38px] px-3 border border-linea rounded-[22px] text-[14px] focus:outline-none focus:border-celeste">
                    <button type="submit" class="flex items-center justify-center size-[40px]" aria-label="Buscar">
                        <img src="{{ asset('assets/img/icons/search.svg') }}" alt="" width="20" height="20">
                    </button>
                </form>

                <a href="{{ route('presupuesto') }}" class="btn-sec">Presupuesto</a>
            </div>
        </div>
    </nav>

    <!-- ===================== CONTENIDO ===================== -->
    <div class="flex-grow">
        @yield('content')
    </div>

    <!-- ===================== FOOTER ===================== -->
    <footer class="bg-dark text-white">
        <div class="contenedor md:pl-[64px] pt-[68px] pb-[69px] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[321px_310px_315px_1fr] gap-y-10">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="block w-[231px] h-[77px]">
                @if ($logos->foto_footer)
                    <img src="{{ $logos->foto_footer }}" alt="Bernaplast" class="w-full h-full object-contain">
                @else
                    <span class="block w-[231px] h-[74px] bg-white"
                          style="-webkit-mask: url('{{ asset('assets/img/logo-footer-mask.png') }}') no-repeat 0 0 / 231px 77px; mask: url('{{ asset('assets/img/logo-footer-mask.png') }}') no-repeat 0 0 / 231px 77px;"></span>
                @endif
            </a>

            <!-- Secciones -->
            <div class="pt-[17px]">
                <h3 class="font-sora font-bold text-[20px] leading-[1.2]">Secciones</h3>
                <div class="flex gap-[20px] mt-[26px] text-[16px] leading-[2]">
                    <ul>
                        <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                        <li><a href="{{ route('productos') }}">Productos</a></li>
                        <li><a href="{{ route('desarrollo') }}">Desarrollo de producto</a></li>
                        <li><a href="{{ route('clientes') }}">Clientes</a></li>
                    </ul>
                    <ul>
                        <li><a href="{{ route('novedades') }}">Novedades</a></li>
                        <li><a href="{{ route('presupuesto') }}">Presupuesto</a></li>
                        <li><a href="{{ route('contacto') }}">Contacto</a></li>
                    </ul>
                </div>
            </div>

            <!-- Redes -->
            <div class="pt-[14px]">
                <h3 class="font-sora font-bold text-[20px] leading-[1.2]">Seguinos en</h3>
                <div class="flex gap-[16px] mt-[26px]">
                    @if ($contacto_contenido->instagram)
                        <a href="{{ $contacto_contenido->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <img src="{{ asset('assets/img/icons/instagram.svg') }}" alt="" width="24" height="24">
                        </a>
                    @endif
                    @if ($contacto_contenido->facebook)
                        <a href="{{ $contacto_contenido->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="text-[22px] leading-none">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Contacto -->
            <div class="pt-[17px]">
                <h3 class="font-sora font-bold text-[20px] leading-[1.2]">Contacto</h3>
                <ul class="flex flex-col gap-[14px] mt-[26px] text-[16px] leading-[1.5]">
                    @if ($contacto_contenido->direccion)
                        <li>
                            <a href="https://maps.google.com/?q={{ urlencode($contacto_contenido->direccion) }}" target="_blank" class="flex items-start gap-[12px]">
                                <img src="{{ asset('assets/img/icons/map-pin.svg') }}" alt="" width="20" height="20" class="mt-[3px] shrink-0">
                                <span class="max-w-[255px]">{{ $contacto_contenido->direccion }}</span>
                            </a>
                        </li>
                    @endif
                    @if ($contacto_contenido->mail)
                        <li>
                            <a href="mailto:{{ $contacto_contenido->mail }}" class="flex items-center gap-[12px]">
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="" width="20" height="20" class="shrink-0">
                                {{ $contacto_contenido->mail }}
                            </a>
                        </li>
                    @endif
                    @foreach (array_filter([$contacto_contenido->telefono, $contacto_contenido->telefono2]) as $telefono)
                        <li>
                            <a href="{{ $wa($telefono) }}" target="_blank" class="flex items-center gap-[13px]">
                                <img src="{{ asset('assets/img/icons/whatsapp.svg') }}" alt="" width="20" height="20" class="shrink-0">
                                {{ $telefono }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-black/[0.07]">
            <div class="contenedor md:pl-[74px] min-h-[67px] py-3 flex flex-col sm:flex-row gap-1 justify-between items-center text-[14px] leading-[1.5]">
                <p>© Copyright {{ date('Y') }} Bernaplast. Tecnología y experiencia en cada envase.</p>
                <a href="https://osole.com.ar/" target="_blank">by <span class="font-bold">Osole</span></a>
            </div>
        </div>
    </footer>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')

    {{-- ===================== SCRIPTS ===================== --}}

    {{-- Menú móvil y buscador --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle     = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        toggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        const buscador = document.getElementById('buscador');
        const input    = document.getElementById('buscador-input');
        document.getElementById('buscador-toggle').addEventListener('click', () => {
            if (input.classList.contains('hidden')) {
                input.classList.remove('hidden');
                input.focus();
            } else if (input.value.trim() !== '') {
                buscador.submit();
            } else {
                input.classList.add('hidden');
            }
        });
    });
    </script>

</body>
</html>
