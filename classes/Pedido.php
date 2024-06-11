<?php

require_once 'Conexion.php';

class Pedido {
    protected $conexion = null;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function crearPedido($idUsuario, $direccionFacturacion, $cif_nif, $estado, $provincia, 
    $localidad, $codigoPostal,$direccionEnvio,$nombreEnvio,$apellidosEnvio, $telefonoEnvio,$localidadEnvio, $provinciaEnvio, $codigoPostalEnvio,$nroSeguimiento,$metodoEnvio, $metodoPago, $detalles) {
        try {
            $this->conexion->getConBD()->beginTransaction();

            $stmt = $this->conexion->getConBD()->prepare("INSERT INTO pedido (fecha_pedido, id_usuario, direccion_facturacion, cif_nif, id_estado, provincia, localidad, codigo_postal, direccion_envio, nombre_envio, apellidos_envio, telefono_envio, localidad_envio, provincia_envio, codigo_postal_envio, nro_seguimiento, metodo_envio, metodo_pago) VALUES (NOW(), :idUsuario, :direccionFacturacion, :cif_nif, :estado, :provincia, :localidad, :codigoPostal, :direccionEnvio, :nombreEnvio, :apellidosEnvio, :telefonoEnvio, :localidadEnvio, :provinciaEnvio, :codigoPostalEnvio, :nroSeguimiento, :metodoEnvio, :metodoPago)");

            $stmt->execute(array(
                ':idUsuario' => $idUsuario,
                ':direccionFacturacion' => $direccionFacturacion,
                ':cif_nif' => $cif_nif,
                ':estado' => $estado,
                ':provincia' => $provincia,
                ':localidad' => $localidad,
                ':codigoPostal' => $codigoPostal,
                ':direccionEnvio' => $direccionEnvio,
                ':nombreEnvio' => $nombreEnvio,
                ':apellidosEnvio' => $apellidosEnvio,
                ':telefonoEnvio' => $telefonoEnvio,
                ':localidadEnvio' => $localidadEnvio,
                ':provinciaEnvio' => $provinciaEnvio,
                ':codigoPostalEnvio' => $codigoPostalEnvio,
                ':nroSeguimiento' => $nroSeguimiento,
                ':metodoEnvio' => $metodoEnvio,
                ':metodoPago' => $metodoPago


            ));

            $idPedido = $this->conexion->getConBD()->lastInsertId();

            $stmtDetalle = $this->conexion->getConBD()->prepare("INSERT INTO detalle_pedido (id_pedido, id_juego, cantidad) VALUES (:idPedido, :idJuego, :cantidad)");
            foreach ($detalles as $detalle) {
                $stmtDetalle->execute(array(
                    ':idPedido' => $idPedido,
                    ':idJuego' => $detalle['id_juego'],
                    ':cantidad' => $detalle['cantidad']
                ));
            }

            $this->conexion->getConBD()->commit();
            return "Pedido creado correctamente";
        } catch (PDOException $e) {
            $this->conexion->getConBD()->rollBack();
            die("¡Error al crear el pedido!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarPedidosPorUsuario($idUsuario) {
        try {
            $query = "SELECT pedido.id_pedido, pedido.fecha_pedido, pedido.direccion_envio, pedido.id_usuario, pedido.cif_nif, pedido.id_estado, pedido.provincia, pedido.localidad, pedido.codigo_postal,metodo_pago, metodo_envio, detalle_pedido.id_detalle, detalle_pedido.cantidad, juego.nombre_juego, juego.precio, juego.foto 
                      FROM pedido
                      INNER JOIN detalle_pedido ON pedido.id_pedido = detalle_pedido.id_pedido 
                      INNER JOIN juego ON detalle_pedido.id_juego = juego.id_juego
                      WHERE pedido.id_usuario = :idUsuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->execute(array(':idUsuario' => $idUsuario));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("¡Error al listar los pedidos por usuario!: " . $e->getMessage() . "<br/>");
        }
    }
    
    public function obtenerPedidoPorSeguimiento($idSeguimiento) {
        try {
            $query = "SELECT pedido.id_pedido, pedido.fecha_pedido, pedido.id_usuario, pedido.direccion_facturacion, pedido.cif_nif, pedido.id_estado, pedido.provincia, pedido.localidad, pedido.codigo_postal, pedido.direccion_envio, pedido.nombre_envio, pedido.apellidos_envio, pedido.telefono_envio, pedido.localidad_envio, pedido.provincia_envio, pedido.codigo_postal_envio, pedido.nro_seguimiento, pedido.metodo_envio, pedido.metodo_pago, detalle_pedido.id_detalle, detalle_pedido.id_juego, detalle_pedido.cantidad 
                      FROM pedido
                      INNER JOIN detalle_pedido ON pedido.id_pedido = detalle_pedido.id_pedido 
                      WHERE pedido.nro_seguimiento = :nro_seguimiento";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->execute(array(':nro_seguimiento' => $idSeguimiento));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("¡Error al obtener el pedido por ID!: " . $e->getMessage() . "<br/>");
        }
    }
    public function obtenerPedidoPorUsuario($idUsuario) {
        try {
            $query = "SELECT pedido.id_pedido, pedido.fecha_pedido, pedido.id_usuario, pedido.direccion_facturacion, pedido.cif_nif, pedido.id_estado, pedido.provincia, pedido.localidad, pedido.codigo_postal, pedido.direccion_envio, pedido.nombre_envio, pedido.apellidos_envio, pedido.telefono_envio, pedido.localidad_envio, pedido.provincia_envio, pedido.codigo_postal_envio, pedido.nro_seguimiento, pedido.metodo_envio, pedido.metodo_pago, detalle_pedido.id_detalle, detalle_pedido.id_juego, detalle_pedido.cantidad 
                      FROM pedido
                      INNER JOIN detalle_pedido ON pedido.id_pedido = detalle_pedido.id_pedido 
                      WHERE pedido.id_usuario = :id_usuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->execute(array(':id_usuario' => $idUsuario));
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("¡Error al obtener el pedido por ID!: " . $e->getMessage() . "<br/>");
        }
    }
      
    public function __destruct() {
        $this->conexion = null;
    }
}

?>
