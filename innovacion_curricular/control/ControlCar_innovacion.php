<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Car_innovacion.php';

class ControlCar_innovacion {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM car_innovacion");

        $this->conexion->cerrarBd();

        $car_innovacion = [];
        foreach ($resultado as $fila) {
            $car_innovacion[] = new Car_innovacion($fila['id'], $fila['nombre'], $fila['descripcion'], $fila['tipo']);
        }
        return $car_innovacion;
    }

    public function buscarPorId(string $id): ?Car_innovacion {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM car_innovacion WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Car_innovacion($f['id'], $f['nombre'], $f['descripcion'], $f['tipo']);
        }
        return null; 
    }

    public function guardar(Car_innovacion $c): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO car_innovacion (id, nombre, descripcion, tipo) VALUES (?, ?, ?, ?)",
            [$c->getId(), $c->getNombre(), $c->getDescripcion(), $c->getTipo()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Car_innovacion $c): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE car_innovacion SET nombre = ?, descripcion = ?, tipo = ? WHERE id = ?",
            [$c->getNombre(), $c->getDescripcion(), $c->getTipo(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM car_innovacion WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM car_innovacion");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>