<?php

namespace ApiGenericaPhp\Controllers;

use ApiGenericaPhp\Servicios\Abstracciones\IServicioCrud;

class EntidadesController
{
    private IServicioCrud $servicioCrud;

    public function __construct(IServicioCrud $servicioCrud)
    {
        $this->servicioCrud = $servicioCrud;
    }

    public function listarAsync(string $tabla, ?string $esquema, ?int $limite): void
    {
        try {
            $filas = $this->servicioCrud->listarAsync($tabla, $esquema, $limite);

            if (empty($filas)) {
                $this->responder(204);
                return;
            }

            $this->responder(200, [
                'tabla'   => $tabla,
                'esquema' => $esquema ?? 'por defecto',
                'limite'  => $limite,
                'total'   => count($filas),
                'datos'   => $filas,
            ]);
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al listar registros.');
        }
    }

    public function obtenerPorClaveAsync(string $tabla, string $nombreClave, string $valor, ?string $esquema): void
    {
        try {
            $filas = $this->servicioCrud->obtenerPorClaveAsync($tabla, $esquema, $nombreClave, $valor);

            if (empty($filas)) {
                $this->responder(404, [
                    'estado'  => 404,
                    'mensaje' => "No se encontraron registros con {$nombreClave} = {$valor} en {$tabla}",
                ]);
                return;
            }

            $this->responder(200, [
                'tabla'   => $tabla,
                'esquema' => $esquema ?? 'por defecto',
                'filtro'  => "{$nombreClave} = {$valor}",
                'total'   => count($filas),
                'datos'   => $filas,
            ]);
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al obtener registro.');
        }
    }

    public function crearAsync(string $tabla, array $datosEntidad, ?string $esquema, ?string $camposEncriptar): void
    {
        try {
            if (empty($datosEntidad)) {
                $this->responder(400, ['estado' => 400, 'mensaje' => 'Los datos no pueden estar vacíos.']);
                return;
            }

            $creado = $this->servicioCrud->crearAsync($tabla, $esquema, $datosEntidad, $camposEncriptar);

            if ($creado) {
                $this->responder(200, ['estado' => 200, 'mensaje' => 'Registro creado exitosamente.']);
            } else {
                $this->responder(500, ['estado' => 500, 'mensaje' => 'No se pudo crear el registro.']);
            }
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al crear registro.');
        }
    }

    public function actualizarAsync(string $tabla, string $nombreClave, string $valorClave, array $datosEntidad, ?string $esquema, ?string $camposEncriptar): void
    {
        try {
            if (empty($datosEntidad)) {
                $this->responder(400, ['estado' => 400, 'mensaje' => 'Los datos de actualización no pueden estar vacíos.']);
                return;
            }

            $filasAfectadas = $this->servicioCrud->actualizarAsync($tabla, $esquema, $nombreClave, $valorClave, $datosEntidad, $camposEncriptar);

            if ($filasAfectadas > 0) {
                $this->responder(200, ['estado' => 200, 'mensaje' => 'Registro actualizado exitosamente.']);
            } else {
                $this->responder(404, ['estado' => 404, 'mensaje' => 'No se encontró el registro a actualizar.']);
            }
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al actualizar registro.');
        }
    }

    public function eliminarAsync(string $tabla, string $nombreClave, string $valorClave, ?string $esquema): void
    {
        try {
            $filasEliminadas = $this->servicioCrud->eliminarAsync($tabla, $esquema, $nombreClave, $valorClave);

            if ($filasEliminadas > 0) {
                $this->responder(200, ['estado' => 200, 'mensaje' => 'Registro eliminado exitosamente.']);
            } else {
                $this->responder(404, ['estado' => 404, 'mensaje' => 'No se encontró el registro a eliminar.']);
            }
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al eliminar registro.');
        }
    }

    public function verificarContrasenaAsync(string $tabla, array $datos, ?string $esquema): void
    {
        try {
            $resultado = $this->servicioCrud->verificarContrasenaAsync(
                $tabla,
                $esquema,
                $datos['campoUsuario'] ?? '',
                $datos['campoContrasena'] ?? '',
                $datos['valorUsuario'] ?? '',
                $datos['valorContrasena'] ?? ''
            );

            $this->responder($resultado['codigo'], $resultado);
        } catch (\Throwable $e) {
            $this->responderError500($e, $tabla, 'Error interno al verificar credenciales.');
        }
    }

    public function obtenerInformacion(): void
    {
        $this->responder(200, [
            'controlador' => 'EntidadesController',
            'version'     => '1.0',
            'descripcion' => 'Controlador genérico para operaciones CRUD',
        ]);
    }

    public function inicio(): void
    {
        $this->responder(200, [
            'Mensaje'       => 'Bienvenido a la API Genérica en PHP',
            'Version'       => '1.0',
            'Descripcion'   => 'API genérica para operaciones CRUD sobre cualquier tabla',
            'Documentacion' => '/docs',
        ]);
    }

    private function responder(int $codigo, ?array $datos = null): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');

        if ($datos !== null) {
            echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    private function responderError500(\Throwable $excepcion, string $tabla, string $mensaje): void
    {
        $this->responder(500, [
            'estado'    => 500,
            'mensaje'   => $mensaje,
            'tabla'     => $tabla,
            'detalle'   => $excepcion->getMessage(),
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        ]);
    }
}
