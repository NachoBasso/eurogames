<?php

require_once 'Conexion.php';

class Juego
{
    protected $conexion = null;
    private $idJuego;
    private $nombre;
    private $precio;
    private $descripcion;
    private $stock;
    private $editor;
    private $anioEdicion;
    private $cantidadJugadores;
    private $foto;
    private $edadMinima;
    private $duracion;
    private $idCategoria;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function getIdJuego()
    {
        return $this->idJuego;
    }

    public function setIdJuego($idJuego)
    {
        $this->idJuego = $idJuego;
    }
    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }
    public function getEditor()
    {
        return $this->editor;
    }

    public function setEditor($editor)
    {
        $this->editor = $editor;
    }
    public function getAnioEdicion()
    {
        return $this->anioEdicion;
    }

    public function setAnioEdicion($anioEdicion)
    {
        $this->anioEdicion = $anioEdicion;
    }
    public function getCantidadJugadores()
    {
        return $this->cantidadJugadores;
    }

    public function setCantidadJugadores($cantidadJugadores)
    {
        $this->cantidadJugadores = $cantidadJugadores;
    }
    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function getEdadMinima()
    {
        return $this->edadMinima;
    }

    public function setEdadMinima($edadMinima)
    {
        $this->edadMinima = $edadMinima;
    }

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;
    }


    public function ejecutarConsulta($query)
    {
        try {
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al ejecutar la consulta!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegos()
    {
        try {
            $query = "SELECT  id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, 
                              edad_minima, duracion_minutos, id_categoria FROM JUEGO";
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al listar juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function listarJuegosPorCategoria($categoria)
    {
        try {
            $query = "SELECT j.nombre_juego, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, 
                            j.duracion_minutos, j.edad_minima
                      FROM juego j
                      JOIN categoria c ON j.id_categoria = c.id_categoria
                      WHERE c.nombre_categoria LIKE :categoria";

            $stmt = $this->conexion->getConBD()->prepare($query);
            $categoriaParam = '%' . $categoria . '%';
            $stmt->bindParam(':categoria', $categoriaParam, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al listar los juegos por categoria!: " . $e->getMessage() . "<br/>");
        }
    }


    public function listarJuegosCrud(){
        try {
            $query = "SELECT j.id_juego, j.nombre_juego, j.precio, j.descripcion, j.stock, j.editor, j.anio_edicion, 
            j.cantidad_jugadores, j.foto, j.edad_minima, j.duracion_minutos, c.nombre_categoria 
            FROM juego j
            JOIN categoria c ON j.id_categoria = c.id_categoria";
            $resultado = $this->conexion->getConBD()->query($query);
            $resultado->setFetchMode(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            die("¡Error al listar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function borrarJuego($idJuego)
    {
        try {
            $datos = array(':id' => $idJuego);
            $resultado = $this->conexion->getConBD()->prepare("DELETE FROM juego WHERE id_juego = :idJuego");
            $resultado->execute($datos);

            if ($resultado->rowCount() == 0) {
                echo "El id $idJuego es incorrecto. No es posible borrar el juego.";
            }
        } catch (PDOException $e) {
            die("¡Error al borrar el juego!: " . $e->getMessage() . "<br/>");
        }
    }

    public function crearJuego($nombre, $precio, $descripcion, $stock, $editor, $anio_edicion, $cantidad_jugadores, $rutaImagen, $edad_minima, $duracion, $id_categoria)
    {
        try {
            $query = "INSERT INTO juego (nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos, id_categoria) 
                    VALUES (:nombre, :precio, :descripcion, :stock, :editor, :anio_edicion, :cantidad_jugadores, :foto, :edad_minima, :duracion_minutos, :id_categoria)";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':editor', $editor);
            $stmt->bindParam(':anio_edicion', $anio_edicion);
            $stmt->bindParam(':cantidad_jugadores', $cantidad_jugadores);
            $stmt->bindParam(':foto', $rutaImagen);
            $stmt->bindParam(':edad_minima', $edad_minima);
            $stmt->bindParam(':duracion_minutos', $duracion);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->execute();
        } catch (PDOException $e) {
            die("¡Error al ingresar el juego!: " . $e->getMessage() . "<br/>");
        }
    }

    public function obtenerJuegoPorId($idJuego)
    {
        try {
            $query = "SELECT id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos, id_categoria FROM juego WHERE id_juego = :idJuego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':idJuego', $idJuego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error al obtener el juego por el id: ' . $e->getMessage());
        }
    }

    public function obtenerStockPorId($idJuego)
    {
        try {
            $query = "SELECT stock FROM juego WHERE id_juego = :id_juego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':id_juego', $idJuego, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            die('Error al obtener el stock: ' . $e->getMessage());
        }
    }

    public function buscarJuego($busqueda)
    {
        try {
            $sql = "SELECT id_juego, nombre_juego, descripcion, precio, edad_minima, duracion_minutos, editor, anio_edicion, foto 
                    FROM juego 
                    WHERE nombre_juego LIKE :busqueda OR descripcion LIKE :busqueda OR edad_minima LIKE :busqueda OR editor LIKE :busqueda
                    OR anio_edicion LIKE :busqueda";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $busqueda = "%$busqueda%";
            $stmt->bindParam(':busqueda', $busqueda, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error al buscar el juego: ' . $e->getMessage());
        }
    }

    public function filtrarPorEditor($editor)
    {
        try {
            $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos FROM juego WHERE editor = :editor";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':editor', $editor);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por editor: ' . $e->getMessage());
        }
    }

    public function filtrarPorPrecio($precio)
    {
        try {
            $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, duracion_minutos, edad_minima FROM juego WHERE precio <= :precio";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':precio', $precio);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al filtrar por precio!: " . $e->getMessage() . "<br/>");
        }
    }

    public function filtrarPorDuracion($duracion)
    {
        try {
            $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos FROM juego WHERE duracion_minutos <= :duracion_minutos";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':duracion_minutos', $duracion);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por la duración del juego: ' . $e->getMessage());
        }
    }

    public function filtrarPorEdadMinima($edadMinima)
    {
        try {
            $sql = "SELECT nombre_juego, precio, descripcion, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos FROM juego WHERE edad_minima <= :edad_minima";
            $stmt = $this->conexion->getConBD()->prepare($sql);
            $stmt->bindValue(':edad_minima', $edadMinima);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            die('Error al filtrar por el campo edad mínima: ' . $e->getMessage());
        }
    }

    public function obtenerEditores()
    {
        try {
            $query = "SELECT DISTINCT editor FROM juego";
            $stmt = $this->conexion->getConBD()->query($query);
            $editores = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $editores;
        } catch (PDOException $e) {
            die("¡Error al obtener los editores!: " . $e->getMessage() . "<br/>");
        }
    }

    public function seleccionarJuegosRandom($limit)
    {
        try {
            $query = "SELECT id_juego, nombre_juego, precio, descripcion, stock, editor, anio_edicion, cantidad_jugadores, foto, edad_minima, duracion_minutos, id_categoria FROM JUEGO ORDER BY RAND() LIMIT $limit";
            $stmt = $this->conexion->getConBD()->query($query);
            $juegosRandom = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $juegosRandom;
        } catch (PDOException $e) {
            die("¡Error al seleccionar juegos random!: " . $e->getMessage() . "<br/>");
        }
    }

    /*public function devolverFotoJuego($id_juego)
    {
        try {
            $query = "SELECT foto FROM juego WHERE id_juego = :id_juego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindValue(':id_juego', $id_juego);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return $resultado['foto'];
            } else {
                return null;
            }
        } catch (PDOException $e) {
            die("¡Error al obtener la foto del juego!: " . $e->getMessage() . "<br/>");
        }
    }*/



    public function obtenerCategorias()
    {
        try {
            $query = "SELECT id_categoria, nombre_categoria FROM categoria";
            $stmt = $this->conexion->getConBD()->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("¡Error al obtener las categorías!: " . $e->getMessage() . "<br/>");
        }
    }



    public function listarJuegosFiltrados($editor = null, $categoria = null, $orden = null, $edad = null, $duracion = null, $precio = null)
    {
        try {
            $query = "SELECT j.nombre_juego, j.precio, j.descripcion, j.editor, j.anio_edicion, j.cantidad_jugadores, j.foto, j.duracion_minutos, j.edad_minima
                    FROM juego j
                    JOIN categoria c ON j.id_categoria = c.id_categoria
                    WHERE 1=1";

            if ($editor !== null) {
                $query .= " AND j.editor = :editor";
            }
            if ($categoria !== null) {
                $query .= " AND c.nombre_categoria LIKE :categoria";
            }
            if ($edad !== null) {
                $query .= " AND j.edad_minima >= :edad";
            }
            if ($duracion !== null) {
                $query .= " AND j.duracion_minutos >= :duracion";
            }
            if ($precio !== null) {
                $precioParam = explode('-', $precio);
                $query .= " AND j.precio >= :precio";
            }

            switch ($orden) {
                case 'precio_asc':
                    $query .= " ORDER BY j.precio ASC";
                    break;
                case 'precio_desc':
                    $query .= " ORDER BY j.precio DESC";
                    break;
                case 'nombre_asc':
                    $query .= " ORDER BY j.nombre_juego ASC";
                    break;
                case 'nombre_desc':
                    $query .= " ORDER BY j.nombre_juego DESC";
                    break;
                default:
                    break;
            }

            $stmt = $this->conexion->getConBD()->prepare($query);

            if ($editor !== null) {
                $stmt->bindParam(':editor', $editor);
            }
            if ($categoria !== null) {
                $categoriaParam = '%' . $categoria . '%';
                $stmt->bindParam(':categoria', $categoriaParam);
            }
            if ($edad !== null) {
                $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
            }
            if ($duracion !== null) {
                $stmt->bindParam(':duracion', $duracion, PDO::PARAM_INT);
            }
            if ($precio !== null) {
                $stmt->bindParam(':precio', $precioParam[0], PDO::PARAM_INT);
            }

            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt;
        } catch (PDOException $e) {
            die("¡Error al filtrar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }

    public function editarJuego($nombre, $precio, $descripcion, $stock, $editor, $anioEdicion, $cantidadJugadores, $foto, $edadMinima, $duracion, $idCategoria, $idJuego)
    {
        try {
            $query = "UPDATE JUEGO SET nombre_juego = :nombre, precio = :precio, descripcion = :descripcion, 
                  stock = :stock, editor = :editor, anio_edicion = :anioEdicion, cantidad_jugadores = :cantidadJugadores,
                  foto = :foto, edad_minima = :edadMinima, duracion_minutos = :duracionMinutos, id_categoria = :idCategoria
                  WHERE id_juego = :idJuego";
            $stmt = $this->conexion->getConBD()->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':editor', $editor);
            $stmt->bindParam(':anioEdicion', $anioEdicion);
            $stmt->bindParam(':cantidadJugadores', $cantidadJugadores);
            $stmt->bindParam(':foto', $foto);
            $stmt->bindParam(':edadMinima', $edadMinima);
            $stmt->bindParam(':duracionMinutos', $duracion);
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->bindParam(':idJuego', $idJuego);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("¡Error al editar los juegos!: " . $e->getMessage() . "<br/>");
        }
    }




    public function __destruct()
    {
        return $this->conexion = null;
    }
}
