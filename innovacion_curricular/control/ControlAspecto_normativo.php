<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Aspecto_normativo.php';

class ControlAspecto_normativo {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM aspecto_normativo");

        $this->conexion->cerrarBd();

        $aspecto_normativo = [];
        foreach ($resultado as $fila) {
            $aspecto_normativo[] = new Aspecto_normativo($fila['id'], $fila['tipo'], $fila['descripcion'], $fila['fuente']);
        }
        return $aspecto_normativo;
    }

    public function buscarPorId(string $id): ?Aspecto_normativo {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM aspecto_normativo WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Aspecto_normativo($f['id'], $f['tipo'], $f['descripcion'], $f['fuente']);
        }
        return null; 
    }

    public function guardar(Aspecto_normativo $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO aspecto_normativo (id, tipo, descripcion, fuente) VALUES (?, ?, ?, ?)",
            [$a->getId(), $a->getTipo(), $a->getDescripcion(), $a->getFuente()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Aspecto_normativo $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE aspecto_normativo SET tipo = ?, descripcion = ?, fuente = ? WHERE id = ?",
            [$a->getTipo(), $a->getDescripcion(), $a->getFuente(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM aspecto_normativo WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM aspecto_normativo");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>