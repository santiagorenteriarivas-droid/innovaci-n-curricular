<?php
namespace ApiGenericaPhp\Servicios\Abstracciones;

interface IServicioCrud
{
    public function listarAsync(string $nombreTabla, ?string $esquema, ?int $limite): array;

    public function obtenerPorClaveAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valor): array;

    public function crearAsync(string $nombreTabla, ?string $esquema, array $datos, ?string $camposEncriptar = null): bool;

    public function actualizarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave, array $datos, ?string $camposEncriptar = null): int;

    public function eliminarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave): int;

    public function verificarContrasenaAsync(string $nombreTabla, ?string $esquema, string $campoUsuario, string $campoContrasena, string $valorUsuario, string $valorContrasena): array;
}
