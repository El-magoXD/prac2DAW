<?php
/**
 * Práctica 7 y 8 - Fichero separado con los datos de los anuncios del sitio web.
 * Incluye los anuncios por identificador (1 a 5) para el panel "Últimos anuncios visitados"
 * y mantiene también las claves 'impar' y 'par' para cualquier otro identificador numérico.
 */

$anunciosPorId = [
    1 => [
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
    ],
    2 => [
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
    ],
    3 => [
        'id'                 => 3,
        'tipo_anuncio'       => 'Venta',
        'tipo_vivienda'      => 'Vivienda',
        'foto_principal'     => 'img/piso3.jpg',
        'alt_foto_principal' => 'Vista principal del piso reformado en Madrid',
        'titulo'             => 'Piso reformado en Madrid',
        'precio'             => '275.000 €',
        'texto'              => 'Amplio piso íntegramente reformado en una zona residencial tranquila de Madrid. Dispone de suelos de parquet, carpintería exterior con rotura de puente térmico, calefacción individual y excelente conexión con metro y autobuses.',
        'fecha'              => '23/09/2026',
        'ciudad'             => 'Madrid',
        'pais'               => 'España',
        'caracteristicas'    => [
            'superficie'        => '105 m²',
            'habitaciones'      => 3,
            'banos'             => 2,
            'planta'            => '3ª planta',
            'anio_construccion' => 2015,
        ],
        'miniaturas'         => [
            [
                'src'    => 'img/interior.jpg',
                'alt'    => 'Salón principal del piso reformado en Madrid',
                'titulo' => 'Salón comedor',
            ],
            [
                'src'    => 'img/interior2.jpg',
                'alt'    => 'Cocina independiente con electrodomésticos',
                'titulo' => 'Cocina independiente',
            ],
            [
                'src'    => 'img/interior3.jpg',
                'alt'    => 'Baño completo con acabados cerámicos',
                'titulo' => 'Baño completo',
            ],
            [
                'src'    => 'img/interior4.jpg',
                'alt'    => 'Habitación principal exterior en Madrid',
                'titulo' => 'Dormitorio principal',
            ],
        ],
        'usuario'            => 'usuario1',
    ],
    4 => [
        'id'                 => 4,
        'tipo_anuncio'       => 'Alquiler',
        'tipo_vivienda'      => 'Vivienda',
        'foto_principal'     => 'img/piso4.jpg',
        'alt_foto_principal' => 'Vista principal de la casa con terraza en Sevilla',
        'titulo'             => 'Casa con terraza en Sevilla',
        'precio'             => '1.150 € / mes',
        'texto'              => 'Encantadora vivienda con amplia terraza privada en Sevilla. Destaca por su luminosidad natural, patio interior tradicional, amplios dormitorios con armarios empotrados y cercanía a zonas verdes y comerciales.',
        'fecha'              => '22/09/2026',
        'ciudad'             => 'Sevilla',
        'pais'               => 'España',
        'caracteristicas'    => [
            'superficie'        => '130 m²',
            'habitaciones'      => 4,
            'banos'             => 2,
            'planta'            => 'Bajo y 1ª planta',
            'anio_construccion' => 2018,
        ],
        'miniaturas'         => [
            [
                'src'    => 'img/interior4.jpg',
                'alt'    => 'Dormitorio principal con salida a la terraza',
                'titulo' => 'Dormitorio con terraza',
            ],
            [
                'src'    => 'img/interior.jpg',
                'alt'    => 'Salón principal de la casa en Sevilla',
                'titulo' => 'Salón de estar',
            ],
            [
                'src'    => 'img/interior2.jpg',
                'alt'    => 'Cocina amplia y luminosa',
                'titulo' => 'Cocina',
            ],
            [
                'src'    => 'img/interior3.jpg',
                'alt'    => 'Cuarto de baño principal',
                'titulo' => 'Baño principal',
            ],
        ],
        'usuario'            => 'maria',
    ],
    5 => [
        'id'                 => 5,
        'tipo_anuncio'       => 'Venta',
        'tipo_vivienda'      => 'Obra nueva',
        'foto_principal'     => 'img/piso5.jpg',
        'alt_foto_principal' => 'Vista principal del piso céntrico en Barcelona',
        'titulo'             => 'Piso céntrico en Barcelona',
        'precio'             => '320.000 €',
        'texto'              => 'Exclusivo piso céntrico en Barcelona con techos altos y acabados modernos. Ubicado a pocos minutos del transporte público, cuenta con balcón exterior, cocina equipada de última generación y gran aislamiento acústico.',
        'fecha'              => '21/09/2026',
        'ciudad'             => 'Barcelona',
        'pais'               => 'España',
        'caracteristicas'    => [
            'superficie'        => '98 m²',
            'habitaciones'      => 3,
            'banos'             => 2,
            'planta'            => '5ª planta',
            'anio_construccion' => 2021,
        ],
        'miniaturas'         => [
            [
                'src'    => 'img/interior2.jpg',
                'alt'    => 'Cocina de diseño en piso de Barcelona',
                'titulo' => 'Cocina de diseño',
            ],
            [
                'src'    => 'img/interior.jpg',
                'alt'    => 'Salón exterior con balcón en Barcelona',
                'titulo' => 'Salón exterior',
            ],
            [
                'src'    => 'img/interior4.jpg',
                'alt'    => 'Dormitorio doble luminoso',
                'titulo' => 'Dormitorio doble',
            ],
            [
                'src'    => 'img/interior3.jpg',
                'alt'    => 'Baño principal con ducha',
                'titulo' => 'Baño principal',
            ],
        ],
        'usuario'            => 'admin',
    ],
];

// Compatibilidad directa por clave par/impar además del acceso por ID específico
$anunciosDetalle = [
    'impar' => $anunciosPorId[1],
    'par'   => $anunciosPorId[2],
];
