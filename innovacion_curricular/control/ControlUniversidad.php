<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Universidad.php';

class ControlUniversidad {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM universidad");

        $this->conexion->cerrarBd();

        $universidad = [];
        foreach ($resultado as $fila) {
            $universidad[] = new Universidad($fila['id'], $fila['nombre'], $fila['tipo'], $fila['ciudad']);
        }
        return $universidad;
    }

    public function buscarPorId(string $id): ?Universidad {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM universidad WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Universidad($f['id'], $f['nombre'], $f['tipo'], $f['ciudad']);
        }
        return null; 
    }

    public function guardar(Universidad $u): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO universidad (id, nombre, tipo, ciudad) VALUES (?, ?, ?, ?)",
            [$u->getId(), $u->getNombre(), $u->getTipo(), $u->getCiudad()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Universidad $u): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE universidad SET nombre = ?, tipo = ?, ciudad = ? WHERE id = ?",
            [$u->getNombre(), $u->getTipo(), $u->getCiudad(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM universidad WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM universidad");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>