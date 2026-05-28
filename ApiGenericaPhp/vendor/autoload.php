<?php
spl_autoload_register(function (string $clase) {

    $prefijo = 'ApiGenericaPhp\\';

    $dirBase = __DIR__ . '/../src/';

    $longitudPrefijo = strlen($prefijo);
    if (strncmp($prefijo, $clase, $longitudPrefijo) !== 0) {
        return; 
    }

    $claseRelativa = substr($clase, $longitudPrefijo);

    $archivo = $dirBase . str_replace('\\', '/', $claseRelativa) . '.php';

    if (file_exists($archivo)) {
        require $archivo;
    }
});
