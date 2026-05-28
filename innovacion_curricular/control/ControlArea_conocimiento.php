<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Area_conocimiento.php';

class ControlArea_conocimiento {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM area_conocimiento");

        $this->conexion->cerrarBd();

        $area_conocimiento = [];
        foreach ($resultado as $fila) {
            $area_conocimiento[] = new Area_conocimiento($fila['id'], $fila['gran_area'], $fila['area'], $fila['disciplina']);
        }
        return $area_conocimiento;
    }

    public function buscarPorId(string $id): ?Area_conocimiento {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM area_conocimiento WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Area_conocimiento($f['id'], $f['gran_area'], $f['area'], $f['disciplina']);
        }
        return null; 
    }

    public function guardar(Area_conocimiento $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO area_conocimiento (id, gran_area, area, disciplina) VALUES (?, ?, ?, ?)",
            [$a->getId(), $a->getGran_area(), $a->getArea(), $a->getDisciplina()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $idOriginal, Area_conocimiento $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE area_conocimiento SET gran_area = ?, area = ?, disciplina = ? WHERE id = ?",
            [$a->getGran_area(), $a->getArea(), $a->getDisciplina(), $idOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $id): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM area_conocimiento WHERE id = ?", [$id]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM area_conocimiento");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>