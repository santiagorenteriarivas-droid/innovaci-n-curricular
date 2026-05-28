<?php
namespace ApiGenericaPhp\Servicios\Abstracciones;

interface IPoliticaTablasProhibidas
{
    public function esTablaPermitida(string $nombreTabla): bool;
}
