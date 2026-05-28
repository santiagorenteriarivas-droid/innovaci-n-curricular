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
    // Agrega esta línea temporal para ver el error real en pantalla:
    echo "<div style='background:red; color:white; padding:10px;'>ERROR BD (Comando): " . $e->getMessage() . "</div>";
    echo "Error SQL: " . $e->getMessage(); // Línea original
    return false; // Línea original
 }
}
    public function ejecutarSelect($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);

            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

       } catch (PDOException $e) {
        // Alerta naranja visible en la parte superior del navegador
        echo "<div style='background:orange; color:black; padding:15px; font-weight:bold; text-align:center; z-index:9999; position:relative;'>ERROR BD (Select): " . $e->getMessage() . "</div>";
        
        // Retornamos el arreglo vacío original para no romper el resto de la vista
        return [];
    }
} // Esta llave cierra el método ejecutarSelect
} // Esta llave cierra la clase ControlConexionPdo
?>