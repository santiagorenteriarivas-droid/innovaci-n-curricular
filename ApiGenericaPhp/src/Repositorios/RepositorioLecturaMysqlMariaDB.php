<?php
namespace ApiGenericaPhp\Repositorios;

use ApiGenericaPhp\Repositorios\Abstracciones\IRepositorioLecturaTabla;
use ApiGenericaPhp\Servicios\Abstracciones\IProveedorConexion;
use PDO;
use PDOException;

class RepositorioLecturaMysqlMariaDB implements IRepositorioLecturaTabla
{
    private IProveedorConexion $proveedorConexion;

    public function __construct(IProveedorConexion $proveedorConexion)
    {
        $this->proveedorConexion = $proveedorConexion;
    }

    private function crearConexion(): PDO
    {
        return new PDO(
            $this->proveedorConexion->obtenerDsn(),
            $this->proveedorConexion->obtenerUsuario(),
            $this->proveedorConexion->obtenerContrasena(),
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            ]
        );
    }

    public function obtenerFilasAsync(string $nombreTabla, ?string $esquema, ?int $limite): array
    {
        $limiteFinal = $limite ?? 1000;
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';
        $sql = "SELECT * FROM {$esquemaFinal}`{$nombreTabla}` LIMIT :limite";

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limiteFinal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerPorClaveAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valor): array
    {
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';
        $sql = "SELECT * FROM {$esquemaFinal}`{$nombreTabla}` WHERE `{$nombreClave}` = :valor";

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':valor', $valor);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function crearAsync(string $nombreTabla, ?string $esquema, array $datos, ?string $camposEncriptar = null): bool
    {
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';

        if (!empty($camposEncriptar)) {
            $campos = array_map('trim', explode(',', $camposEncriptar));
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datos[$campo] = password_hash((string)$datos[$campo], PASSWORD_BCRYPT, ['cost' => 10]);
                }
            }
        }

        $columnas = array_map(fn($c) => "`{$c}`", array_keys($datos));
        $placeholders = array_map(fn($c) => ":{$c}", array_keys($datos));

        $sql = sprintf(
            "INSERT INTO %s`%s` (%s) VALUES (%s)",
            $esquemaFinal,
            $nombreTabla,
            implode(', ', $columnas),
            implode(', ', $placeholders)
        );

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);

        foreach ($datos as $columna => $valor) {
            $stmt->bindValue(":{$columna}", $valor);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

public function actualizarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave, array $datos, ?string $camposEncriptar = null): int
    {
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';

        // 1. Encriptación (tu código original)
        if (!empty($camposEncriptar)) {
            $campos = array_map('trim', explode(',', $camposEncriptar));
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datos[$campo] = password_hash((string)$datos[$campo], PASSWORD_BCRYPT, ['cost' => 10]);
                }
            }
        }

        // 2. Preparar los campos a actualizar (SET campo = :p_campo)
        $asignaciones = [];
        foreach ($datos as $columna => $valor) {
            $asignaciones[] = "`{$columna}` = :p_{$columna}";
        }

        // 3. SEPARAR LLAVES COMPUESTAS: Cortamos por las comas que envía el frontend
        $claves = array_map('trim', explode(',', $nombreClave));
        $valores = array_map('trim', explode(',', $valorClave));

        // 4. Preparar el WHERE dinámico (WHERE clave1 = :vc_0 AND clave2 = :vc_1)
        $condicionesWhere = [];
        foreach ($claves as $indice => $clave) {
            $condicionesWhere[] = "`{$clave}` = :vc_{$indice}";
        }
        $whereSql = implode(' AND ', $condicionesWhere);

        // 5. Armar la consulta SQL completa
        $sql = sprintf(
            "UPDATE %s`%s` SET %s WHERE %s",
            $esquemaFinal,
            $nombreTabla,
            implode(', ', $asignaciones),
            $whereSql
        );

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);

        // 6. Inyectar los valores a actualizar (los nuevos datos)
        foreach ($datos as $columna => $valor) {
            $stmt->bindValue(":p_{$columna}", $valor);
        }

        // 7. Inyectar los valores de las llaves de búsqueda (los IDs antiguos)
        foreach ($valores as $indice => $valor) {
            $stmt->bindValue(":vc_{$indice}", $valor);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function eliminarAsync(string $nombreTabla, ?string $esquema, string $nombreClave, string $valorClave): int
    {
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';

        // 🔥 MAGIA: Separar llaves compuestas si vienen separadas por comas
        $claves = array_map('trim', explode(',', $nombreClave));
        $valores = array_map('trim', explode(',', $valorClave));

        $condicionesWhere = [];
        foreach ($claves as $indice => $clave) {
            $condicionesWhere[] = "`{$clave}` = :vc_{$indice}";
        }
        $whereSql = implode(' AND ', $condicionesWhere);

        $sql = "DELETE FROM {$esquemaFinal}`{$nombreTabla}` WHERE {$whereSql}";

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);

        // Bind dinámico para las llaves del WHERE
        foreach ($valores as $indice => $valor) {
            $stmt->bindValue(":vc_{$indice}", $valor);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function obtenerHashContrasenaAsync(string $nombreTabla, ?string $esquema, string $campoUsuario, string $campoContrasena, string $valorUsuario): ?string
    {
        $esquemaFinal = (!empty($esquema)) ? "`{$esquema}`." : '';
        $sql = "SELECT `{$campoContrasena}` FROM {$esquemaFinal}`{$nombreTabla}` WHERE `{$campoUsuario}` = :usuario LIMIT 1";

        $pdo = $this->crearConexion();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':usuario', $valorUsuario);
        $stmt->execute();

        $resultado = $stmt->fetchColumn();
        return ($resultado === false) ? null : (string)$resultado;
    }

    public function obtenerDiagnosticoConexionAsync(): array
    {
        try {
            $pdo = $this->crearConexion();
            $stmt = $pdo->query("SELECT DATABASE() AS nombre_base_datos, VERSION() AS version_servidor, USER() AS usuario_actual");
            $info = $stmt->fetch();

            return [
                'baseDatos' => $info['nombre_base_datos'],
                'version'   => $info['version_servidor'],
                'usuario'   => $info['usuario_actual'],
            ];
        } catch (PDOException $e) {
            throw new \RuntimeException("Error al obtener diagnóstico de conexión: {$e->getMessage()}", 0, $e);
        }
    }
}
