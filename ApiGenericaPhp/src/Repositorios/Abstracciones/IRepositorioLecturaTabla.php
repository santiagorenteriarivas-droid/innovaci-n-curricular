<?php
namespace ApiGenericaPhp\Repositorios\Abstracciones;

interface IRepositorioLecturaTabla
{
    public function obtenerFilasAsync(string $nombreTabla, ?string $esquema, ?int $limite): array;

    public function obtenerPorClaveAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valor): array;

    public function crearAsync(string $nombreTabla, ?string $esquema, array $datos, ?string $camposEncriptar = null): bool;

    public function actualizarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave, array $datos, ?string $camposEncriptar = null): int;

    public function eliminarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave): int;

    public function obtenerHashContrasenaAsync(string $nombreTabla, ?string $esquema, string $campoUsuario, string $campoContrasena, string $valorUsuario): ?string;

    public function obtenerDiagnosticoConexionAsync(): array;
}
