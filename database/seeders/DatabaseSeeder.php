<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Calidade;
use App\Models\Categoria;
use App\Models\Certificado;
use App\Models\Contacto;
use App\Models\Desarrollo;
use App\Models\Familia;
use App\Models\Home;
use App\Models\HomeBanner;
use App\Models\Logo;
use App\Models\Matriceria;
use App\Models\Metadato;
use App\Models\Nosotro;
use App\Models\Novedade;
use App\Models\Producto;
use App\Models\Usuario;
use App\Support\ImportadorProductos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Textos del sitio tomados del Figma de Bernaplast.
 * Las fotos se cargan desde el administrador.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Usuario::create([
            'usuario' => 'pablo',
            'contraseña' => Hash::make('pablopablo'),
            'rol' => 'Administrador',
        ]);

        Logo::create([]);

        // ---------------- Banners de cada sección ----------------
        $banners = [
            'nosotros'    => ['Nosotros', 'Más de 45 años de trayectoria en el diseño, desarrollo y fabricación de envases, tapas y artículos plásticos.'],
            'productos'   => ['Productos', 'Estos son nuestros principales productos.'],
            'desarrollo'  => ['Desarrollo de producto', 'Matricería - Moldes exclusivos'],
            'calidad'     => ['Calidad', 'Certificación de productos'],
            'clientes'    => ['Clientes', 'Confían en nosotros'],
            'novedades'   => ['Novedades', 'Enterate de los últimos lanzamientos y novedades de Bernaplast.'],
            'presupuesto' => ['Solicitud de presupuesto', 'Comunicate con nosotros por teléfono, WhatsApp o email.'],
            'contacto'    => ['Contacto', 'Comunicate con nosotros por teléfono, WhatsApp o email.'],
        ];

        foreach ($banners as $seccion => [$texto, $subtitulo]) {
            Banner::create(['seccion' => $seccion, 'texto' => $texto, 'subtitulo' => $subtitulo]);
        }

        // ---------------- Home ----------------
        $slide = new HomeBanner();
        $slide->orden = 'AA';
        $slide->titulo = 'Berna Plast';
        $slide->texto = 'Tecnología y experiencia en cada envase.';
        $slide->save();

        Home::create([
            'titulo_productos' => 'Productos destacados',
            'texto_productos'  => 'Una selección del catálogo. Mirá todo lo disponible en la sección Productos.',
            'titulo_nosotros'  => 'Quiénes somos',
            'texto_nosotros'   => '<p>En Bernaplast somos una empresa familiar con más de 45 años de trayectoria en el diseño, desarrollo y fabricación de envases, tapas y artículos plásticos. Hoy, la tercera generación continúa impulsando este legado, ofreciendo soluciones confiables para la industria alimenticia, química, agro y muchas más.</p>'
                . '<p>Nuestra prioridad es la calidad y la innovación constante. Por eso, además de un amplio catálogo, brindamos un asesoramiento personalizado, adaptándonos a las necesidades de cada cliente para garantizar envases que realzan sus productos y potencian sus ventas.</p>',
            'titulo_novedades' => 'Novedades',
            'texto_novedades'  => 'Enterate de las últimas novedades',
            'titulo_clientes'  => 'Clientes',
            'texto_clientes'   => 'Confían en nosotros',
        ]);

        // ---------------- Nosotros ----------------
        Nosotro::create([
            'titulo' => 'Quiénes somos',
            'texto'  => '<p>En Bernaplast somos una empresa familiar con más de 45 años de trayectoria en el diseño, desarrollo y fabricación de envases, tapas y artículos plásticos. Hoy, la tercera generación continúa impulsando este legado, ofreciendo soluciones confiables para la industria alimenticia, química, agro y muchas más.</p>'
                . '<p>Nuestra prioridad es la calidad y la innovación constante. Por eso, además de un amplio catálogo, brindamos un asesoramiento personalizado, adaptándonos a las necesidades de cada cliente para garantizar envases que realzan sus productos y potencian sus ventas.</p>'
                . '<p>En Bernaplast nos enfocamos en la fabricación de envases plásticos de alta calidad, diseñados con prestaciones específicas para cada rubro. Nuestro compromiso con la seguridad y la confianza se refleja en la certificación del INAL (Instituto Nacional de Alimentos) bajo la órbita de la ANMAT, que garantiza que nuestros productos cumplen con los más altos estándares para el contacto con alimentos.</p>',
            'titulo_valores' => 'Valores',
            'valores' => [
                ['titulo' => 'Calidad', 'texto' => 'Envases de alta resistencia, hechos para durar y proteger tu producto.'],
                ['titulo' => 'Innovación', 'texto' => 'Mejoramos y desarrollamos formatos de forma constante.'],
                ['titulo' => 'Trato personal', 'texto' => 'Nos adaptamos a las necesidades de cada cliente.'],
                ['titulo' => 'Confianza', 'texto' => 'Certificación INAL, bajo la órbita de ANMAT, para contacto con alimentos.'],
            ],
        ]);

        // ---------------- Productos ----------------
        // Familias (líneas de producto), en el orden del Figma
        $familias = [];
        foreach (['Bidón', 'Balde', 'Garrafa', 'Dusen', '3000 cc', '1500 cc', '750 cc', '330 cc'] as $i => $nombre) {
            $familias[$nombre] = Familia::create(['orden' => 'A' . chr(65 + $i), 'nombre' => $nombre])->id;
        }

        // Productos del Figma (nombres y textos del diseño), vinculados por código de envase al Excel
        Producto::create([
            'familia_id'      => $familias['330 cc'],
            'codigo'          => 'FR-330-T-60-PET',
            'orden'           => 'AA',
            'nombre'          => 'Frasco plástico de 325 cc',
            'caracteristicas' => '<p>Materia prima: Plástico PET<br>Pico: 28/410<br>Color: Cristal<br>Gramaje: 18 gr</p>',
            'medidas'         => '<p>Alto: 13,7 cm<br>Diámetro: 4,1 cm<br>Altura máxima de etiqueta: 8,5 cm</p>',
            'packaging'       => 'Bolsas de 100 unidades',
            'apto'            => 'alimentos',
            'nota'            => 'Contamos con la posibilidad de realizar el envase y la tapa en colores personalizados',
            'destacado'       => true,
        ]);

        $delFigma = [
            'AB' => ['FR-1.5-T-100-PET', 'Frasco plástico de 1500 cc', '1500 cc', true],
            'AC' => ['FR-3-T-100-PET', 'Frasco plástico de 3000 cc', '3000 cc', true],
            'AD' => ['GA-8-T-100-PP', 'Garrafa plástica cuadrada de 8 litros', 'Garrafa', true],
            'AE' => ['BID-10-PEAD', 'Bidón', 'Bidón', false],
            'AF' => ['BALDE-8000', 'Balde plástico de 8 litros con tapa a presión', 'Balde', false],
            'AG' => ['GA-8-T-100-PET', 'Garrafa plástica', 'Garrafa', false],
        ];
        foreach ($delFigma as $orden => [$codigo, $nombre, $familia, $destacado]) {
            Producto::create(['familia_id' => $familias[$familia], 'codigo' => $codigo, 'orden' => $orden, 'nombre' => $nombre, 'destacado' => $destacado]);
        }

        // Tapas, características y el resto de los productos: desde el Excel de códigos (igual que "Importar" en el admin)
        $excel = database_path('seeders/datos/codigo productos.xlsx');
        if (is_file($excel)) {
            (new ImportadorProductos())->importar($excel);
        }

        // ---------------- Desarrollo de producto ----------------
        Desarrollo::create([
            'texto' => '<p>En Berna Plast, contamos con un departamento de matricería propio, especializado en el diseño y fabricación de moldes exclusivos para envases plásticos. Gracias a esta capacidad, podemos ofrecer envases innovadores y funcionales, totalmente adaptados a las necesidades de cada cliente.</p>'
                . '<p><strong>¿Cómo funciona?</strong></p>',
            'pasos' => [
                ['texto' => 'Compartinos tu idea y medidas del envase que necesitás.'],
                ['texto' => 'Desarrollamos un molde exclusivo y personalizado para tu producto, asegurando que se ajuste perfectamente a tus requerimientos.'],
                ['texto' => 'Una vez listo el molde, iniciamos la producción directa en fábrica, garantizando calidad y eficiencia en todo el proceso.'],
            ],
            'texto2' => '<p>Además de nuestra propia línea de envases, trabajamos con empresas que desean desarrollar su propio diseño, brindándoles asesoramiento personalizado durante todo el proceso para lograr el producto a medida. Si estás buscando un envase único para tu marca o negocio, ¡contactanos y trabajemos juntos en tu proyecto!</p>',
            'titulo_matricerias' => 'Matricerías',
        ]);

        foreach (['AA' => 'Botella 125 cc', 'AB' => 'Botella 500 cc', 'AC' => 'Botella 1 lt', 'AD' => 'Tapas'] as $orden => $nombre) {
            Matriceria::create(['orden' => $orden, 'nombre' => $nombre]);
        }

        // ---------------- Calidad ----------------
        Calidade::create([
            'texto' => '<p>En BERNAPLAST, con más de 45 años de trayectoria y el compromiso de tres generaciones, nos dedicamos al diseño, desarrollo y fabricación de envases, tapas y artículos plásticos de alta calidad para la industria alimenticia, química, agro e industrial.</p>'
                . '<p>Nuestra Política de Calidad se fundamenta en los siguientes pilares:</p>',
            'pilares' => [
                ['titulo' => 'Inocuidad y Seguridad Certificada', 'texto' => 'Garantizamos productos seguros e higiénicos mediante el estricto cumplimiento de la normativa vigente del INAL (Instituto Nacional de Alimentos) bajo la órbita de ANMAT, asegurando la máxima protección para el contacto directo con alimentos.'],
                ['titulo' => 'Orientación al Cliente y Asesoramiento', 'texto' => 'Nos adaptamos a las necesidades específicas de cada cliente, brindando soluciones integrales que potencian el valor, la presentación y la funcionalidad de sus productos finalizados.'],
                ['titulo' => 'Mejora Continua e Innovación', 'texto' => 'Promovemos la optimización constante de nuestros procesos productivos, la modernización de moldes y tecnología, y el desarrollo continuo de nuestro equipo de trabajo para mantener la máxima competitividad en el mercado.'],
            ],
            'titulo_certificados' => 'Certificados',
        ]);

        Certificado::create(['orden' => 'AA', 'nombre' => 'Certificado INAL']);

        // ---------------- Novedades ----------------
        $empresa = Categoria::create(['orden' => 'AA', 'nombre' => 'Empresa']);
        Categoria::create(['orden' => 'AB', 'nombre' => 'Productos']);
        $calidad = Categoria::create(['orden' => 'AC', 'nombre' => 'Calidad']);

        $novedades = [
            ['AA', $calidad, 'Compromiso con la calidad industria alimenticia', '<p>En Bernaplast sabemos que la seguridad de tus productos es lo primero. Por eso, toda…</p>'],
            ['AB', $empresa, 'Soluciones versátiles en plástico', '<p>Desde frascos gourmet de distintas capacidades hasta garrafas y baldes con sistema…</p>'],
            ['AC', $empresa, 'Más de 45 años fabricando el futuro de tus productos', '<p>Tres generaciones impulsando un legado de diseño, desarrollo y fabricación de artículos…</p>'],
        ];

        foreach ($novedades as [$orden, $categoria, $titulo, $texto]) {
            Novedade::create([
                'orden'        => $orden,
                'categoria_id' => $categoria->id,
                'fecha'        => now()->toDateString(),
                'titulo'       => $titulo,
                'texto'        => $texto,
                'destacado'    => true,
            ]);
        }

        $masNovedades = [
            ['AD', 'Innovación en seguridad y practicidad', '<p>Optimizá el transporte y conservación de tus productos con nuestras líneas baldes con…</p>'],
            ['AE', 'Packs por mayor con envíos a todo el país', '<p>Ya sea que tengas un emprendimiento gastronómico, una fábrica de conserva o una…</p>'],
        ];

        foreach ($masNovedades as [$orden, $titulo, $texto]) {
            Novedade::create([
                'orden'        => $orden,
                'categoria_id' => $empresa->id,
                'fecha'        => now()->toDateString(),
                'titulo'       => $titulo,
                'texto'        => $texto,
            ]);
        }

        // ---------------- Contacto ----------------
        $contacto = new Contacto();
        $contacto->direccion = 'Benito Lynch 3014, B1674 Sáenz Peña, Buenos Aires. Argentina';
        $contacto->mail = 'info@bernaplast.com.ar';
        $contacto->telefono = '+54 9 11 3109-2323';
        $contacto->telefono2 = '+54 9 11 6489-8352';
        $contacto->whatssap = '+54 9 11 3109-2323';
        $contacto->ubicacion = 'https://www.google.com/maps?q=' . urlencode('Benito Lynch 3014, Sáenz Peña, Buenos Aires, Argentina') . '&output=embed';
        $contacto->save();

        // ---------------- Metadatos (por nombre de ruta) ----------------
        $metadatos = [
            'home'        => 'Bernaplast: diseño, desarrollo y fabricación de envases, tapas y artículos plásticos. Tecnología y experiencia en cada envase.',
            'nosotros'    => 'Empresa familiar con más de 45 años de trayectoria en envases, tapas y artículos plásticos.',
            'productos'   => 'Frascos, garrafas y tapas plásticas para la industria alimenticia, química y agro.',
            'desarrollo'  => 'Matricería propia: moldes exclusivos y envases a medida para tu marca.',
            'calidad'     => 'Política de calidad y certificación INAL bajo la órbita de ANMAT para contacto con alimentos.',
            'clientes'    => 'Empresas que confían en Bernaplast.',
            'novedades'   => 'Lanzamientos y novedades de Bernaplast.',
            'presupuesto' => 'Solicitá un presupuesto de envases y tapas plásticas.',
            'contacto'    => 'Comunicate con Bernaplast por teléfono, WhatsApp o email.',
        ];

        foreach ($metadatos as $seccion => $descripcion) {
            Metadato::forceCreate([
                'seccion'     => $seccion,
                'descripcion' => $descripcion,
                'keywords'    => 'envases plásticos, tapas, frascos, garrafas, matricería, Bernaplast',
            ]);
        }
    }
}
