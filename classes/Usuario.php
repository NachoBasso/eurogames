<?php

require_once 'Conexion.php';

class Usuario
{
    private $conexion;
    private $id_usuario;
    private $nombre_usuario;
    private $apellidos_usuario;
    private $email;
    private $password;
    private $telefono;
    private $es_administrador;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setNombreUsuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }

    public function setApellidosUsuario($apellidos_usuario)
    {
        $this->apellidos_usuario = $apellidos_usuario;
    }

    public function getApellidosUsuario()
    {
        return $this->apellidos_usuario;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setEsAdministrador($es_administrador)
    {
        $this->es_administrador = $es_administrador;
    }

    public function getEsAdministrador()
    {
        return $this->es_administrador;
    }



    public function crearUsuario($nombre, $apellidos, $email, $password, $telefono, $es_administrador = 0)
    {
        try {
            if ($this->usuarioExiste($email)) {
                return "Los datos no son correctos o el usuario ya existe. Por favor vuelva a intentarlo.";
            }
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO usuario (nombre_usuario, apellidos_usuario, email, password, telefono, es_administrador) 
                      VALUES (:nombre, :apellidos, :email, :password, :telefono, :es_administrador)";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hash_password);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':es_administrador', $es_administrador);
            $stmt->execute();
            return "Usuario creado correctamente";
        } catch (PDOException $e) {
            die("Error al craer el usuario existe: " . $e->getMessage() . "<br/>");
        }
    }



    public function eliminarUsuario($id_usuario)
    {
        $query = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->getConBD()->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }

    public function listarUsuarios()
    {
        $query = "SELECT id_usuario, nombre_usuario, apellidos_usuario, email, password, telefono, es_administrador FROM usuario";
        $stmt = $this->conexion->getConBD()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*public function editarUsuario($id_usuario, $nombre, $apellidos, $email, $password, $telefono, $es_administrador) {
        $query = "UPDATE USUARIO SET nombre_usuario = :nombre, apellidos_usuario = :apellidos, email = :email, 
                  password = :password, telefono = :telefono, es_administrador = :es_administrador 
                  WHERE id_usuario = :id_usuario";
        $hash_password= password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conexion->getConBD()->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':es_administrador', $es_administrador);
        return $stmt->execute();
    }*/

    public function usuarioExiste($email)
    {
        try {
            $query = "SELECT COUNT(*) FROM usuario WHERE email = :email";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            die("Error al verificar si el usuario existe: " . $e->getMessage() . "<br/>");
        }
    }

    public function obtenerUsuarioPorEmail($email)
    {
        try {
            $query = "SELECT id_usuario, nombre_usuario, apellidos_usuario, email, password, telefono, es_administrador FROM usuario WHERE email = :email";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al intentar obtener un usuario por su email: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarDatosPersonales($id_usuario)
    {
        try {

            $query = "SELECT  nombre_usuario, apellidos_usuario, email, telefono FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al listar datos personales del usuario: " . $e->getMessage() . "<br/>");
        }
    }
    public function validarPassword($id_usuario, $contrasena)
    {
        try {
            $query = "SELECT contrasena FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return password_verify($contrasena, $usuario['contrasena']);
        } catch (PDOException $e) {
            die("Error al validar el password del usuario: " . $e->getMessage() . "<br/>");
        }
    }

    public function actualizarPassword($id_usuario, $nueva_contrasena)
    {
        try {

            $hash_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $query = "UPDATE usuario SET contrasena = :nueva_contrasena WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nueva_contrasena', $hash_password);
            $stmt->bindParam(':id_usuario', $id_usuario);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al actualizar el password del usuario: " . $e->getMessage() . "<br/>");
        }
    }

    public function editarUsuario($id_usuario, $nombre, $apellidos, $email, $telefono)
    {

        try {
            $query = "UPDATE usuario SET nombre_usuario = :nombre, apellidos_usuario = :apellidos, email = :email, telefono = :telefono WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':id_usuario', $id_usuario);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al editar el usuario: " . $e->getMessage() . "<br/>");
        }
    }
}
