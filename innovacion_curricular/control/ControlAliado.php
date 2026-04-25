<?php

require_once __DIR__ . '/ControlConexionPdo.php';
require_once __DIR__ . '/../modelo/Aliado.php';

class ControlAliado {

    private ControlConexionPdo $conexion;

    public function __construct() {
        $this->conexion = new ControlConexionPdo();
    }

    public function listar(): array {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM aliado");

        $this->conexion->cerrarBd();

        $aliado = [];
        foreach ($resultado as $fila) {
            $aliado[] = new Aliado($fila['nit'], $fila['razon_social'], $fila['nombre_contacto'], $fila['correo'], $fila['telefono'], $fila['ciudad']);
        }
        return $aliado;
    }

    public function buscarPorId(string $nit): ?Aliado {
        $this->conexion->abrirBd();

        $resultado = $this->conexion->ejecutarSelect("SELECT * FROM aliado WHERE nit = ?", [$nit]);

        $this->conexion->cerrarBd();

        if (!empty($resultado)) {
            $f = $resultado[0];
            return new Aliado($f['nit'], $f['razon_social'], $f['nombre_contacto'], $f['correo'], $f['telefono'], $f['ciudad'],);
        }
        return null; 
    }

    public function guardar(Aliado $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "INSERT INTO aliado (nit, razon_social, nombre_contacto, correo, telefono, ciudad) VALUES (?, ?, ?, ?, ?, ?)",
            [$a->getNit(), $a->getRazon_social(), $a->getNombre_contacto(), $a->getCorreo(), $a->getTelefono(), $a->getCiudad()]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function modificar(string $nitOriginal, Aliado $a): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql(
            "UPDATE aliado SET razon_social = ?, nombre_contacto = ?, correo = ?, telefono = ?, ciudad = ? WHERE nit = ?",
            [$a->getRazon_social(), $a->getNombre_contacto(), $a->getCorreo(), $a->getTelefono(), $a->getCiudad(), $nitOriginal]
        );

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function borrar(string $nit): bool {
        $this->conexion->abrirBd();

        $ok = $this->conexion->ejecutarComandoSql("DELETE FROM aliado WHERE nit = ?", [$nit]);

        $this->conexion->cerrarBd();
        return $ok;
    }

    public function contar(): int {
        $this->conexion->abrirBd();

        $r = $this->conexion->ejecutarSelect("SELECT COUNT(*) as total FROM aliado");

        $this->conexion->cerrarBd();

        return (int)($r[0]['total'] ?? 0);
    }
}
?>