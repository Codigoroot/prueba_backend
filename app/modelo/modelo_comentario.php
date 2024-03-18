<?php 

/**
* Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo es el modelo, encargado de establecer la comunicación directa con la base de datos. Aquí se envían,
 * insertan, consultan y actualizan datos. Todos los métodos utilizan consultas preparadas para evitar la inyección SQL y garantizar
 * la seguridad de las operaciones realizadas en la base de datos.
 */




include_once "../config/base_de_datos.php";


class modelo_comentario {
    private $conexion;

    public function __construct() 
    {
        $database = new database();
        $this->conexion = $database->ObtenerConexion();
    }

    public function listarComentariosUser($id)
    {
      $query = "SELECT u.fullname, uc.id, uc.user, uc.coment_text, uc.likes, uc.creation_date, uc.update_date
      FROM user_comment uc inner join user u on uc.user = u.id where uc.user = :id";
      $statement = $this->conexion->prepare($query);
      $statement->bindParam(':id', $id);
      $statement->execute();
      $comentarios = $statement->fetchAll(PDO::FETCH_ASSOC); 
      return $comentarios;
  }


  public function crearComentario($id_user, $coment_text, $likes  )
  {
    $query = "INSERT INTO user_comment (user, coment_text, likes) VALUES (:id_user, :coment_text, :likes)";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':id_user', $id_user);
    $statement->bindParam(':coment_text', $coment_text);
    $statement->bindParam(':likes', $likes);
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


public function actualizarComentario($id_comentario,$coment_text, $update_date)
{
    $query = "UPDATE user_comment SET coment_text = :coment_text, update_date = :update_date WHERE id = :id_comentario";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':id_comentario', $id_comentario);
    $statement->bindParam(':coment_text', $coment_text);
    $statement->bindParam(':update_date', $update_date);
    $resultados = $statement->execute();
    
    if ($resultados) {
        $fila_afectadas = $statement->rowCount();
        return $fila_afectadas > 0;
    }else{
        return false;
    }
}


public function mostrarComentario($id)
{
    $query ="SELECT * FROM user_comment WHERE id = :id";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':id', $id);
    $statement->execute();

    $comentario = $statement->fetch(PDO::FETCH_ASSOC);
    return $comentario;
}

public function eliminarComentario($id)
{
    $query = "DELETE FROM user_comment WHERE  id = :id";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(":id", $id);
    $statement->execute();

    $fila_afectadas = $statement->rowCount();
    return $fila_afectadas > 0;
}

public function validarComentario($user)
{
    $query ="SELECT COUNT(coment_text) as total FROM user_comment WHERE user = :user;";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':user', $user);
    $statement->execute();
    $comentario = $statement->fetch(PDO::FETCH_ASSOC);

    $totalComentarios = $comentario['total'];
    return $totalComentarios;
}


public function like($id)
{
    $query ="UPDATE user_comment SET likes = likes + 1 WHERE id = :id;";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':id', $id);
    $success = $statement->execute();
    return $success;
}

public function dislike($id)
{
    $query = "UPDATE user_comment SET likes = CASE WHEN likes > 0 THEN likes - 1 ELSE likes END WHERE id = :id;";
    $statement = $this->conexion->prepare($query);
    $statement->bindParam(':id', $id);
    $success = $statement->execute();

    return $success;
}



}

?>