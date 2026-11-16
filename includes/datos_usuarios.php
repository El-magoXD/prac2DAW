<?php
/**
 * Práctica 8 - Fichero separado que almacena los datos de los cuatro usuarios permitidos:
 * contraseña, estilo CSS preferido y token único para la cookie "Recordarme en este equipo".
 */

$usuariosPermitidos = [
    'pedro' => [
        'password'      => 'pedro123',
        'estilo'        => 'style.css',
        'titulo_estilo' => 'Estilo principal',
        'token'         => 'tok_pe_9f8a7b6c5d4e3f2a1b',
    ],
    'juan' => [
        'password'      => 'Juan123',
        'estilo'        => 'css/grande.css',
        'titulo_estilo' => 'Texto grande',
        'token'         => 'tok_jn_1a2b3c4d5e6f7a8b9c',
    ],
    'maria' => [
        'password'      => 'Maria123',
        'estilo'        => 'css/contraste.css',
        'titulo_estilo' => 'Alto contraste',
        'token'         => 'tok_mr_5e4d3c2b1a9f8e7d6c',
    ],
    'admin' => [
        'password'      => 'Admin123',
        'estilo'        => 'css/contrasteygrande.css',
        'titulo_estilo' => 'Alto contraste y texto grande',
        'token'         => 'tok_ad_7c8d9e0f1a2b3c4d5e',
    ],
];
