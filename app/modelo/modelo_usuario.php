<?php 
/**
* Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo es el modelo, encargado de establecer la comunicación directa con la base de datos. Aquí se envían,
 * insertan, consultan y actualizan datos. Todos los métodos utilizan consultas preparadas para evitar la inyección SQL y garantizar
 * la seguridad de las operaciones realizadas en la base de datos.
 */



include_once "../config/base_de_datos.php";


class modelo_usuario {
    private $conexion;

    public function __construct() 
    {
        $database = new database();
        $this->conexion = $database->ObtenerConexion();
    }

    public function listarUsuarios()
	{
		$query = "SELECT * FROM user";
		$statement = $this->conexion->prepare($query);
		$statement->execute();
		$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC); 
		return $usuarios;
	}


    public function crearUsuario($fullname, $email, $pass, $openid)
    {
        $query = "INSERT INTO user (fullname, email, pass, openid) VALUES (:fullname, :email, :pass, :openid)";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(':fullname', $fullname);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':pass', $pass);
        $statement->bindParam(':openid', $openid);
        $resultados = $statement->execute();
        
        if ($resultados) {
            $fila_afectadas = $statement->rowCount();
            if ($fila_afectadas > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
   public function actualizarUsuario($id, $fullname, $email, $pass, $openid, $update_date)
    {
        $query = "UPDATE user SET fullname = :fullname, email = :email, pass = :pass, openid = :openid, update_date = :update_date WHERE id = :id";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->bindParam(':fullname', $fullname);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':pass', $pass);
        $statement->bindParam(':openid', $openid);
        $statement->bindParam(':update_date', $update_date);
        $resultados = $statement->execute();
        
        if ($resultados) {
            $fila_afectadas = $statement->rowCount();
            return $fila_afectadas > 0;
        }else{
            return false;
        }
    }

    public function eliminarUsuario($id)
    {
        $query = "DELETE FROM user WHERE  id = :id";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(":id", $id);
        $statement->execute();

        $fila_afectadas = $statement->rowCount();
        return $fila_afectadas > 0;
    }

    public function mostrarUsuario($id)
    {
        $query ="SELECT * FROM user WHERE id = :id";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();

        $usuario = $statement->fetch(PDO::FETCH_ASSOC);
        return $usuario;
    }

    public function validarEmail($email)
    {
        $query ="SELECT * FROM user WHERE  email = :email";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    public function validarOpenID($openid)
    {
        $query ="SELECT * FROM user WHERE  openid = :openid";
        $statement = $this->conexion->prepare($query);
        $statement->bindParam(':openid', $openid);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }



}

?>