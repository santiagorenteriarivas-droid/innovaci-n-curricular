<?php

require_once __DIR__ . '/../config/configbd.php';
class ControlConexionPdo {

    private $conn;

    public function __construct() {
        $this->conn = null;
    }

    public function abrirBd() {
        try {
            $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";port=" . DB_PORT;

            $this->conn = new PDO($dsn, DB_USER, DB_PASS);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->conn->exec("SET CHARACTER SET utf8");

        } catch (PDOException $e) {
            echo "ERROR AL CONECTARSE: " . $e->getMessage();
            exit();
        }
    }

    public function cerrarBd() {
        $this->conn = null;
    }

    public function ejecutarComandoSql($sql, $parametros = []) {
        try {
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute($parametros);

        } catch (PDOException $e) {
            echo "Error SQL: " . $e->getMessage();
            return false;
        }
    }

    public function ejecutarSelect($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);

            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Error SELECT: " . $e->getMessage();
            return [];
        }
    }
}
?>