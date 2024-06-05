<?php

require_once 'Conexion.php';


class Pedido {
    protected $conexion = null;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function crearPedido($idUsuario, $direccionEnvio, $localidad, $estado, $detalles) {
        try {
            $this->conexion->getConBD()->beginTransaction();

            $stmt = $this->conexion->getConBD()->prepare("INSERT INTO pedido (id_usuario, fecha_pedido, direccion_envio, localidad, estado) VALUES (?, NOW(), ?, ?, ?)");
            $stmt->execute([$idUsuario, $direccionEnvio, $localidad, $estado]);

            $idPedido = $this->conexion->getConBD()->lastInsertId();

            $stmtDetalle = $this->conexion->getConBD()->prepare("INSERT INTO detalle_pedido (id_pedido, id_juego, cantidad) VALUES (?, ?, ?)");
            foreach ($detalles as $detalle) {
                $stmtDetalle->execute([$idPedido, $detalle['id_juego'], $detalle['cantidad']]);
            }

            $this->conexion->getConBD()->commit();
            return $idPedido;
        } catch (PDOException $e) {
            $this->conexion->getConBD()->rollBack();
            die("¡Error al crear el pedido!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarPedidosPorUsuario($idUsuario) {
        try {
            $query = "SELECT pedido.*, detalle_pedido.*, juego.nombre_juego, juego.precio, juego.foto FROM pedido
                      INNER JOIN detalle_pedido ON pedido.id_pedido = detalle_pedido.id_pedido INNER JOIN juego ON detalle_pedido.id_juego = juego.id_juego
                      WHERE pedido.id_usuario = ?";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->execute([$idUsuario]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("¡Error al listar los pedidos por usuario!: " . $e->getMessage() . "<br/>");
        }
    }

    public function obtenerPedidoPorId($idPedido) {
        try {
            $query = "SELECT pedido.*, detalle_pedido.*, juego.nombre_juego, juego.precio, juego.foto FROM pedido
                      INNER JOIN detalle_pedido ON pedido.id_pedido = detalle_pedido.id_pedido INNER JOIN juego ON detalle_pedido.id_juego = juego.id_juego
                      WHERE pedido.id_pedido = ?";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->execute([$idPedido]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("¡Error al obtener el pedido por ID!: " . $e->getMessage() . "<br/>");
        }
    }

    public function __destruct(){
        return $this->conexion = null;
    }
}

?>
