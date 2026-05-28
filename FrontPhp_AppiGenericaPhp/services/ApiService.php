<?php
require_once __DIR__ . '/../config.php';

class ApiService {

    private $baseUrl;

    public function __construct() {
        $this->baseUrl = API_BASE_URL; 
    }

    /**
     * Lista todos los registros de una tabla con límite opcional
     * 
     * @param string $tabla Nombre de la tabla
     * @param int|null $limite Límite de registros (opcional)
     * @return array Array con los datos obtenidos
     */
    public function listar($tabla, $limite = null) {

        $url = $this->baseUrl . "/api/" . $tabla;

        if ($limite) {
            $url .= "?limite=" . $limite;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $respuesta = curl_exec($ch);

        curl_close($ch);

        if (!$respuesta) return [];

        $json = json_decode($respuesta, true);

        return $json['datos'] ?? [];
    }

    /**
     * Obtiene un registro específico por su clave primaria
     * 
     * @param string $tabla Nombre de la tabla
     * @param string $nombreClave Nombre de la columna clave
     * @param mixed $valorClave Valor de la clave a buscar
     * @return array Array con los datos del registro encontrado
     */
    public function obtenerPorClave($tabla, $nombreClave, $valorClave) {

        $url = $this->baseUrl . "/api/" . $tabla . "/" . $nombreClave . "/" . $valorClave;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $respuesta = curl_exec($ch);
        curl_close($ch);

        if (!$respuesta) return [];

        $json = json_decode($respuesta, true);
        return $json['datos'] ?? [];
    }

    /**
     * Crea un nuevo registro en la tabla especificada
     * 
     * @param string $tabla Nombre de la tabla
     * @param array $datos Array con los datos a insertar
     * @return array Array con ['exito' => bool, 'mensaje' => string]
     */
    public function crear($tabla, $datos) {
        $url = $this->baseUrl . "/api/" . $tabla;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($respuesta, true);

        $mensaje = $json['mensaje'] ?? 'Operación completada.';
 
        if (!empty($json['detalle'])) {
            $mensaje .= ' ' . $json['detalle'];  
        }

        $exito = $httpCode >= 200 && $httpCode < 300;

        return ['exito' => $exito, 'mensaje' => $mensaje];
    }

    /**
     * Actualiza un registro existente en la tabla especificada
     * 
     * @param string $tabla Nombre de la tabla
     * @param string $nombreClave Nombre de la columna clave
     * @param mixed $valorClave Valor de la clave del registro a actualizar
     * @param array $datos Array con los datos a actualizar
     * @return array Array con ['exito' => bool, 'mensaje' => string]
     */
    public function actualizar($tabla, $nombreClave, $valorClave, $datos) {
        $url = $this->baseUrl . "/api/" . $tabla . "/" . $nombreClave . "/" . $valorClave;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos)); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($respuesta, true);
        $mensaje = $json['mensaje'] ?? 'Operación completada.';
        if (!empty($json['detalle'])) {
            $mensaje .= ' ' . $json['detalle'];
        }
        $exito = $httpCode >= 200 && $httpCode < 300;

        return ['exito' => $exito, 'mensaje' => $mensaje];
    }

    /**
     * Elimina un registro de la tabla especificada
     * 
     * @param string $tabla Nombre de la tabla
     * @param string $nombreClave Nombre de la columna clave
     * @param mixed $valorClave Valor de la clave del registro a eliminar
     * @return array Array con ['exito' => bool, 'mensaje' => string]
     */
    public function eliminar($tabla, $nombreClave, $valorClave) {
        $url = $this->baseUrl . "/api/" . $tabla . "/" . $nombreClave . "/" . $valorClave;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($respuesta, true);
        $mensaje = $json['mensaje'] ?? 'Operación completada.';
        if (!empty($json['detalle'])) {
            $mensaje .= ' ' . $json['detalle'];
        }
        $exito = $httpCode >= 200 && $httpCode < 300;

        return ['exito' => $exito, 'mensaje' => $mensaje];
    }
}
?>