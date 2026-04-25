<?php
namespace ApiGenericaPhp\Servicios\Abstracciones;

interface IProveedorConexion
{
    public function getProveedorActual(): string;

    public function obtenerDsn(): string;

    public function obtenerUsuario(): string;

    public function obtenerContrasena(): string;
}
