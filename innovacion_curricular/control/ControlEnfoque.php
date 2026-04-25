<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Enfoque.php';

class ControlEnfoque {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM enfoque");

        $this->conexion->cerrarBd();

        $enfoque = [];
        foreach ($resultado as $fila) {
            $enfoque[] = new Enfoque($fila['id'], $fila['nombre'], $fila['descripcion']);
        }
        return $enfoque;
    }

    public function buscarPorId(string $id): ?Enfoque {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM enfoque WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Enfoque($f['id'], $f['nombre'], $f['descripcion']);
        }
        return null; 
    }

    public function guardar(Enfoque $c): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO enfoque (id, nombre, descripcion) VALUES (?, ?, ?)",
            [$c->getId(), $c->getNombre(), $c->getDescripcion()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Enfoque $c): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE enfoque SET nombre = ?, descripcion = ? WHERE id = ?",
            [$c->getNombre(), $c->getDescripcion(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM enfoque WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM enfoque");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>