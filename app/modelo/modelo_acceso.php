<?php 
//conexion de base de datos
include_once "../config/base_de_datos.php";


class Acceso{

	private $conexion;


	//implementamos nuestro constructor
	public function __construct(){
		$database = new database();
        $this->conexion = $database->ObtenerConexion();
	}


	public function verificar($user, $pass){
	
		$query="SELECT * FROM user WHERE email = :user OR openid = :user AND pass = :pass";
		$statement = $this->conexion->prepare($query);
		$statement->bindParam(':user', $user, PDO::PARAM_STR);
		$statement->bindParam(':pass', $pass, PDO::PARAM_STR);
		$statement->execute();
		return $statement->fetch(PDO::FETCH_ASSOC);

	}


}

?>
