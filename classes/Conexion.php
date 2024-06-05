<?php
    class Conexion{
        protected $db = null;
        public function __construct(){
            require_once 'config.php';
            $dsn = "mysql:host=". BBDD_HOST.";dbname=".BBDD_NAME.";charset=utf8";
            $usuario=BBDD_USER;
            $pass =BBDD_PASSWORD;

            try {
                $this->db = new PDO($dsn, $usuario, $pass);
            } catch (PDOException $e) {
                die("¡Error!: " . $e->getMessage() . "<br/>");
            }
        }

        public function getConBD(){ 
            return $this->db; 
        }
    }





?>
