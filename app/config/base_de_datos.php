<?php 

class database {
	
	private $servidor = "mariadb";
	private $puerto = "3306"; 
	private $nombre_db = "prueba";
	private $usuario = "prueba_web";
	private $contraseña = "123456";
	public $conn;


	public function ObtenerConexion(){
		$this->conn = null;

		try{
			$dsn = 'mysql:host=' . $this->servidor . ';port=' . $this->puerto . ';dbname=' . $this->nombre_db;
            $this->conn = new PDO($dsn, $this->usuario, $this->contraseña);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "Error de conexion: " . $e->getMessage();
		}

		return $this->conn;
	}
}

 ?>