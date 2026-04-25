<?php

namespace ApiGenericaPhp\Conexion;

use ApiGenericaPhp\Servicios\Abstracciones\IProveedorConexion;

class ProveedorConexion implements IProveedorConexion
{
    private array $configuracion;

    public function __construct(array $configuracion)
    {
        $this->configuracion = $configuracion; 
    }

    public function getProveedorActual(): string
    {
        $valor = $this->configuracion['DatabaseProvider'] ?? '';
        return empty(trim($valor)) ? 'MariaDB' : trim($valor);
    }

    public function obtenerDsn(): string
    {
        $proveedor = $this->getProveedorActual();             
        $datos = $this->obtenerDatosConexion($proveedor);     

        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $datos['host'],               
            $datos['port'],                
            $datos['database'],            
            $datos['charset'] ?? 'utf8mb4' 
        );
    }

    public function obtenerUsuario(): string
    {
        $datos = $this->obtenerDatosConexion($this->getProveedorActual());
        return $datos['username'] ?? 'root'; 
    }

    public function obtenerContrasena(): string
    {
        $datos = $this->obtenerDatosConexion($this->getProveedorActual());
        return $datos['password'] ?? ''; 
    }

    private function obtenerDatosConexion(string $proveedor): array
    {
        $connectionStrings = $this->configuracion['ConnectionStrings'] ?? [];

        if (!isset($connectionStrings[$proveedor])) {
            throw new \RuntimeException(
                "No se encontró la cadena de conexión para el proveedor '{$proveedor}'. " .
                "Verificar que existe 'ConnectionStrings.{$proveedor}' en config/config.php " .
                "y que 'DatabaseProvider' esté configurado correctamente."
            );
        }

        return $connectionStrings[$proveedor]; 
    }
}
