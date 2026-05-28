<?php
// config/config.php

return [

    // Motor de base de datos
    'DatabaseProvider' => 'MariaDB',

    // Datos de conexión
    'ConnectionStrings' => [
        'MariaDB' => [
            'host'     => 'localhost',
            'port'     => 3306,
            'database' => 'innovacion_curricular',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4',
        ],

        'MySQL' => [
            'host'     => 'localhost',
            'port'     => 3306,
            'database' => 'innovacion_curricular',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4',
        ],
    ],

    // Tablas bloqueadas (ninguna por ahora)
    'TablasProhibidas' => [],

    // Configuración CORS
    'Cors' => [
        'AllowedOrigins' => '*',
        'AllowedMethods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'AllowedHeaders' => 'Content-Type, Authorization, X-Requested-With',
    ],

    // Puerto de referencia para servidor PHP
    'ServerPort' => 8000,
];
