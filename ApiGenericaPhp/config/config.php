<?php
// config/config.php

return [

    // Motor de base de datos
    'DatabaseProvider' => 'MariaDB',

    // Datos de conexión
    'ConnectionStrings' => [
        'MariaDB' => [
            'host'     => 'sql311.infinityfree.com',
            'port'     => 3306,
            'database' => 'if0_42043544_dbinnovacion',
            'username' => 'if0_42043544',
            'password' => 'UEbOGj2uofwx',
            'charset'  => 'utf8mb4',
        ],

        'MySQL' => [
            'host'     => 'sql311.infinityfree.com',
            'port'     => 3306,
            'database' => 'if0_42043544_dbinnovacion',
            'username' => 'if0_42043544',
            'password' => 'UEbOGj2uofwx',
            'charset'  => 'utf8mb4',
        ],
    ],

    // Tablas bloqueadas (ninguna por ahora)
    'TablasProhibidas' => [],

    // Configuración CORS
    'Cors' => [
        'AllowedOrigins' => 'Apiinnovacion.infinityfreeapp.com',
        'AllowedMethods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'AllowedHeaders' => 'Content-Type, Authorization, X-Requested-With',
    ],

    // Puerto de referencia para servidor PHP
    'ServerPort' => 8000,
];
