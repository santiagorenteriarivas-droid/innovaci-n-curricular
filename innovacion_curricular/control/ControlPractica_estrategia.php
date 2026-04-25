<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Practica_estrategia.php';

class ControlPractica_estrategia {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM practica_estrategia");

        $this->conexion->cerrarBd();

        $practica_estrategia = [];
        foreach ($resultado as $fila) {
            $practica_estrategia[] = new Practica_estrategia($fila['id'], $fila['tipo'], $fila['nombre'], $fila['descripcion']);
        }
        return $practica_estrategia;
    }

    public function buscarPorId(string $id): ?Practica_estrategia {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM practica_estrategia WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Practica_estrategia($f['id'], $f['tipo'], $f['nombre'], $f['descripcion']);
        }
        return null; 
    }

    public function guardar(Practica_estrategia $p): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO practica_estrategia (id, tipo, nombre, descripcion) VALUES (?, ?, ?, ?)",
            [$p->getId(), $p->getTipo(), $p->getNombre(), $p->getDescripcion()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Practica_estrategia $p): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE practica_estrategia SET tipo = ?, nombre = ?, descripcion = ? WHERE id = ?",
            [$p->getTipo(), $p->getNombre(), $p->getDescripcion(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM practica_estrategia WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM practica_estrategia");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>