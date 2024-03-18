<?php 

ob_start();
session_start(); 

if (!isset($_SESSION['fullname'])) {

    header("Location: ../../index.html");
}

/**
 * Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo controlador contiene todos los endpoints necesarios para realizar las operaciones CRUD de los comentarios. 
 * Se comunica directamente con el modelo para interactuar con la base de datos y gestionar la información de los comentarios.
 */


include "../modelo/modelo_comentario.php";

$comentarios = new modelo_comentario();



switch ($_REQUEST['opcion']) {


	case 'listarComentariosUser':


	$id = isset($_GET['id']) ? trim($_GET['id']) : null;
	$resultados = $comentarios->listarComentariosUser($id);
	$datos = array();

	foreach ($resultados as  $dato) {
		$datos[] = array(

			"0"=> 
			'<a href="#" data-bs-toggle="tooltip" title="Actualizar comentario" onclick="mostrarComentario('.$dato['id'].')" ><i class="fa fa-edit fa-lg text-warning"></i></a>'
			. 
			' <a href="#" data-bs-toggle="tooltip" title="Eliminar usuario" onClick="eliminarComentario('.$dato['id'].')"><i class="fa fa-trash fa-lg text-danger"></i></a>',
			"1"=>$dato['fullname'],
			"2"=>$dato['coment_text'],
			"3"=>$dato['likes'] > 5 ? '<span style="color: green; font-weight: bold">' . $dato['likes'] . '</span>' : '<span style="color: red; font-weight: bold">' . $dato['likes'] . '</span>',
			"4"=>'<a href="#" data-bs-toggle="tooltip" title="Like" onclick="like('.$dato['id'].')" ><i class="fas fa-thumbs-up fa-lg text-success "></i></a> '
			. 
			' <a href="#" data-bs-toggle="tooltip" title="Dislike" onclick="dislike('.$dato['id'].')" ><i class="fas fa-thumbs-down fa-lg text-danger"></i></a>',
			"5"=>$dato['creation_date'],
			"6"=> $dato['update_date']
			
		);
	}

	$results=array(
				 "sEcho"=>1,//info para datatables
				 "iTotalRecords"=>count($datos),//enviamos el total de registros al datatable
				 "iTotalDisplayRecords"=>count($datos),//enviamos el total de registros a visualizar
				 "aaData"=>$datos);

	echo json_encode($results);

	break;



	case 'listarComentariosUser_1':


	$id = isset($_GET['id']) ? trim($_GET['id']) : null;
	$resultados = $comentarios->listarComentariosUser($id);
	$datos = array();

	foreach ($resultados as  $dato) {
		$datos[] = array(

			"0"=>$dato['coment_text'],
			"1"=>$dato['likes'] > 5 ? '<span style="color: green; font-weight: bold">' . $dato['likes'] . '</span>' : '<span style="color: red; font-weight: bold">' . $dato['likes'] . '</span>',
			"2"=>$dato['creation_date'],
			"3"=>$dato['update_date'],
			"4"=> 
			'<a href="#" data-bs-toggle="tooltip" title="Like" onclick="like('.$dato['id'].')" ><i class="fas fa-thumbs-up fa-lg text-success "></i></a> '
			. 
			' <a href="#" data-bs-toggle="tooltip" title="Dislike" onclick="dislike('.$dato['id'].')" ><i class="fas fa-thumbs-down fa-lg text-danger"></i></a>'
		);
	}

	$results=array(
				 "sEcho"=>1,//info para datatables
				 "iTotalRecords"=>count($datos),//enviamos el total de registros al datatable
				 "iTotalDisplayRecords"=>count($datos),//enviamos el total de registros a visualizar
				 "aaData"=>$datos);

	echo json_encode($results);

	break;



	case 'guardarEditarComentario':

	$id_comentario = isset($_POST['id_comentario']) ? trim($_POST['id_comentario']) : null;
	$id_user = isset($_POST['id_user']) ? trim($_POST['id_user']) : null;
	$coment_text = isset($_POST['coment_text']) ? trim($_POST['coment_text']) : null;
	$likes = 0;
	$update_date = date('Y-m-d h:i:s');


	if (empty($id_comentario)) {	
		$respuesta = $comentarios->crearComentario($id_user, $coment_text, $likes);
		echo $respuesta ? "si" : "no";
	}else{
		$respuesta = $comentarios->actualizarComentario($id_comentario,$coment_text, $update_date);
		echo $respuesta ? "si" : "no";
	}


	break;


	case 'mostrarComentario':

	$id = $_POST['id'];
	$respuesta = $comentarios->mostrarComentario($id);
	echo json_encode($respuesta);

	break;

	case 'eliminarComentario':
	$id = $_POST['id'];
	$respuesta = $comentarios->eliminarComentario($id);
	echo $respuesta ? "si" : "no";
	break;

	case 'validarComentario':
	$user = $_POST['user'];
	$respuesta = $comentarios->validarComentario($user);
	echo $respuesta;

	break;

	case 'like':
	$id = $_POST['id'];
	$respuesta = $comentarios->like($id);
	echo $respuesta ? "si" : "no";

	break;

	case 'dislike':
	$id = $_POST['id'];
	$respuesta = $comentarios->dislike($id);
	echo $respuesta ? "si" : "no";

	break;

	

}



?>