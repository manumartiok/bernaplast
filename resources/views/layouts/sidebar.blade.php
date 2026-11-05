@php
  $logo_admin = App\Models\Logo::first();
@endphp
<aside id="sidebar" class="w-64 bg-gray-800 text-white min-h-screen p-4 sidebar-transition overflow-hidden">
  <div>
    <div class="flex flex-col items-center mb-4">
     <a href="{{route('adm.dashboard')}}"><img src="{{ $logo_admin?->foto_footer }}" alt="Logo" class="h-[40px] mb-[10px]"></a>
      <h2 class="text-xl font-bold ml-2">Administrador</h2>
    </div>
  </div>
  <hr class="mx-3 my-4">

  <!-- Web Links -->
  <div class="mb-6">
    <ul>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-home mr-2"></i>Home</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.home-ban')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.home')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-info-circle mr-2"></i>Nosotros</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'nosotros')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.nosotro')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-box mr-2"></i>Productos</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'productos')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.familia')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Familias</a></li>
          <li><a href="{{route('adm.producto')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Productos</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-industry mr-2"></i>Desarrollo de producto</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'desarrollo')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.desarrollo')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
          <li><a href="{{route('adm.matriceria')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Matricerías</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-certificate mr-2"></i>Calidad</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'calidad')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.calidade')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
          <li><a href="{{route('adm.certificado')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Certificados</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-handshake mr-2"></i>Clientes</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'clientes')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.cliente')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-newspaper mr-2"></i>Novedades</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'novedades')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.categoria')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Categorías</a></li>
          <li><a href="{{route('adm.novedade')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-envelope mr-2"></i>Contacto</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'contacto')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
          <li><a href="{{route('adm.contacto')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Contenido</a></li>
        </ul>
      </li>
      <li class="group">
        <button class="flex justify-between items-center w-full text-left py-2 px-3 hover:bg-gray-700 rounded transition focus:outline-none" onclick="toggleSubMenu(this)">
          <span><i class="fas fa-file-invoice mr-2"></i>Presupuesto</span>
          <i class="fas fa-chevron-down transition-transform duration-300 group-[.open]:rotate-180"></i>
        </button>
        <ul class="submenu hidden pl-8 mt-1 space-y-1">
          <li><a href="{{route('adm.banner', 'presupuesto')}}" class="block py-1 px-2 hover:bg-gray-600 rounded">Banner</a></li>
        </ul>
      </li>
      {{-- make:modulo: los módulos nuevos se agregan arriba de esta línea (no borrar) --}}
    </ul>
  </div>

  <hr class="mx-3 my-4">

  <!-- Configuración -->
  <div>
    <ul>
      <li><a href="{{route('adm.newsletter')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fa-solid fa-newspaper mr-2"></i>Newsletter</a></li>
      <li><a href="{{route('adm.logo')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-image mr-2"></i>Logos</a></li>
      <li><a href="{{route('adm.usuarios')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-users mr-2"></i>Usuarios</a></li>
      <li><a href="{{route('adm.metadatos')}}" class="block py-2 px-3 hover:bg-gray-700 rounded transition"><i class="fas fa-cog mr-2"></i>Metadatos</a></li>
    </ul>
  </div>

</aside>


  <script>
  function toggleSubMenu(button) {
    const li = button.parentElement;
    const submenu = li.querySelector('.submenu');
    submenu.classList.toggle('hidden');
    li.classList.toggle('open');
  }
</script>
