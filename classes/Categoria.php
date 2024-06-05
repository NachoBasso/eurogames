<?php
require_once 'Conexion.php';

class Categoria {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerCategorias() {
        try {
            $query = "SELECT id_categoria, nombre_categoria FROM categoria";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("¡Error al obtener las categorías!: " . $e->getMessage() . "<br/>");
        }
    }

    public function __destruct(){
        return $this->conexion = null;
    }
}
