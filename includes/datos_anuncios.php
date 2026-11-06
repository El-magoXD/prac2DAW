<?php
/**
 * Práctica 7 - Fichero separado que almacena únicamente los datos de dos anuncios distintos.
 * Se emplea en la página de detalle de anuncio (y ver anuncio) eligiendo uno u otro
 * según si el identificador recibido es impar o par.
 */

$anunciosDetalle = [
    'impar' => [
        'id'                 => 1,
        'tipo_anuncio'       => 'Venta',
        'tipo_vivienda'      => 'Vivienda',
        'foto_principal'     => 'img/piso.jpg',
        'alt_foto_principal' => 'Vista principal del piso luminoso en Alicante',
        'titulo'             => 'Piso luminoso en Alicante',
        'precio'             => '185.000 €',
        'texto'              => 'Magnífico piso muy luminoso situado en una de las mejores zonas de Alicante. Totalmente amueblado y listo para entrar a vivir. Cuenta con excelentes vistas despejadas y está muy bien comunicado con todos los servicios públicos, comercios y transporte de la ciudad.',
        'fecha'              => '25/09/2026',
        'ciudad'             => 'Alicante',
        'pais'               => 'España',
        'caracteristicas'    => [
            'superficie'        => '90 m²',
            'habitaciones'      => 3,
            'banos'             => 2,
            'planta'            => '4ª planta',
            'anio_construccion' => 2010,
        ],
        'miniaturas'         => [
            [
                'src'    => 'img/interior.jpg',
                'alt'    => 'Vista amplia del salón comedor iluminado',
                'titulo' => 'Salón principal',
            ],
            [
                'src'    => 'img/interior2.jpg',
                'alt'    => 'Vista de la cocina totalmente equipada',
                'titulo' => 'Cocina equipada',
            ],
            [
                'src'    => 'img/interior3.jpg',
                'alt'    => 'Vista del cuarto de baño principal reformado',
                'titulo' => 'Baño principal',
            ],
            [
                'src'    => 'img/interior4.jpg',
                'alt'    => 'Vista del dormitorio principal con armario empotrado',
                'titulo' => 'Dormitorio principal',
            ],
        ],
        'usuario'            => 'juan',
        'paginas_folleto'    => 5,
        'fotos_folleto'      => 6,
    ],
    'par' => [
        'id'                 => 2,
        'tipo_anuncio'       => 'Alquiler',
        'tipo_vivienda'      => 'Obra nueva',
        'foto_principal'     => 'img/piso2.jpg',
        'alt_foto_principal' => 'Vista principal del apartamento moderno en Valencia',
        'titulo'             => 'Apartamento moderno en Valencia',
        'precio'             => '950 € / mes',
        'texto'              => 'Espectacular apartamento de obra nueva en pleno centro de Valencia. Diseño contemporáneo con acabados de primera calidad, climatización por conductos, gran eficiencia energética y terraza soleada ideal para disfrutar todo el año.',
        'fecha'              => '24/09/2026',
        'ciudad'             => 'Valencia',
        'pais'               => 'España',
        'caracteristicas'    => [
            'superficie'        => '75 m²',
            'habitaciones'      => 2,
            'banos'             => 1,
            'planta'            => '2ª planta',
            'anio_construccion' => 2022,
        ],
        'miniaturas'         => [
            [
                'src'    => 'img/interior2.jpg',
                'alt'    => 'Cocina americana integrada en el salón del apartamento',
                'titulo' => 'Cocina americana',
            ],
            [
                'src'    => 'img/interior4.jpg',
                'alt'    => 'Dormitorio principal exterior con ventanal en Valencia',
                'titulo' => 'Dormitorio exterior',
            ],
            [
                'src'    => 'img/interior3.jpg',
                'alt'    => 'Baño moderno con plato de ducha a ras de suelo',
                'titulo' => 'Baño de diseño',
            ],
            [
                'src'    => 'img/interior.jpg',
                'alt'    => 'Zona de estar luminosa junto al balcón',
                'titulo' => 'Zona de estar',
            ],
        ],
        'usuario'            => 'maria',
        'paginas_folleto'    => 8,
        'fotos_folleto'      => 15,
    ],
];
