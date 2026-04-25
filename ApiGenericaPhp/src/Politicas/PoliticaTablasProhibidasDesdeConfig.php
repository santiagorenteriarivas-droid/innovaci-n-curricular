<?php

namespace ApiGenericaPhp\Politicas;

use ApiGenericaPhp\Servicios\Abstracciones\IPoliticaTablasProhibidas;

class PoliticaTablasProhibidasDesdeConfig implements IPoliticaTablasProhibidas
{
    private array $tablasProhibidas;

    public function __construct(array $configuracion)
    {
        $tablas = $configuracion['TablasProhibidas'] ?? [];

        // Normalizamos a minúsculas y filtramos vacíos
        $this->tablasProhibidas = array_map('strtolower', array_filter($tablas, function ($t) {
            return !empty(trim($t));
        }));
    }

    public function esTablaPermitida(string $nombreTabla): bool
    {
        if (empty(trim($nombreTabla))) {
            return false;
        }

        return !in_array(strtolower($nombreTabla), $this->tablasProhibidas, true);
    }

    public function obtenerTablasProhibidas(): array
    {
        return $this->tablasProhibidas;
    }

    public function tieneRestricciones(): bool
    {
        return count($this->tablasProhibidas) > 0;
    }
}
