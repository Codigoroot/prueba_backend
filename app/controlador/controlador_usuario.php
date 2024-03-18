<?php 

ob_start();
session_start(); 

if (!isset($_SESSION['fullname'])) {

    header("Location: ../../index.html");
}

/**
 * Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo controlador contiene todos los endpoints necesarios para realizar las operaciones CRUD de usuarios. 
 * Se comunica directamente con el modelo para interactuar con la base de datos y gestionar la información de los usuarios.
 */

include "../modelo/modelo_usuario.php";

$usuarios = new modelo_usuario();



switch ($_REQUEST['opcion']) {


	case 'listarUsuarios':

	$resultados = $usuarios->listarUsuarios();
	$datos = array();

	foreach ($resultados as  $dato) {
		$datos[] = array(

			"0"=> 
			'<a href="#" data-bs-toggle="tooltip" title="Actualizar usuario" onclick="mostrarUsuario('.$dato['id'].')" ><i class="fa fa-edit fa-lg text-warning"></i></a>'
			. 
			' <a href="#" data-bs-toggle="tooltip" title="Eliminar usuario" onClick="validarComentarios('.$dato['id'].')"><i class="fa fa-trash fa-lg text-danger"></i></a>'
			.
			' <a href="#" data-bs-toggle="tooltip" title="ver comentarios" onClick="mostrarComentariosUser(' . $dato['id'] . ', \'' . $dato['fullname'] . '\')"><i class="fas fa-comments"></i></a>',

			"1"=>$dato['id'],
			"2"=>$dato['fullname'],
			"3"=>$dato['email'],
			"4"=>$dato['pass'],
			"5"=>$dato['openid'],
			"6"=>$dato['creation_date'],
			"7"=>$dato['update_date'],
		);
	}

	$results=array(
				 "sEcho"=>1,//info para datatables
				 "iTotalRecords"=>count($datos),//enviamos el total de registros al datatable
				 "iTotalDisplayRecords"=>count($datos),//enviamos el total de registros a visualizar
				 "aaData"=>$datos);

	echo json_encode($results);

	break;
	

	case 'guardarEditarUsuario':

	$id = isset($_POST['id']) ? trim($_POST['id']) : null;
	$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : null;
	$email = isset($_POST['email']) ? trim($_POST['email']) : null;
	$pass = isset($_POST['pass']) ? trim($_POST['pass']) : null;
	$openid = isset($_POST['openid']) ? trim($_POST['openid']) : null;
	$update_date = date('Y-m-d h:i:s');


	if (empty($id)) {	
		$respuesta = $usuarios->crearUsuario($fullname, $email, $pass, $openid);
		echo $respuesta ? "si" : "no";
	}else{
		$respuesta = $usuarios->actualizarUsuario($id, $fullname, $email, $pass, $openid, $update_date);
		echo $respuesta ? "si" : "no";
	}


	break;


	case 'mostrarUsuario':

	$id = $_POST['id'];
	$respuesta = $usuarios->mostrarUsuario($id);
	echo json_encode($respuesta);

	break;

	case 'eliminarUsuario':
	$id = $_POST['id'];
	$respuesta = $usuarios->eliminarUsuario($id);
	echo $respuesta ? "si" : "no";
	break;


	case 'validarEmail':
	$email = $_POST['email'];
	$respuesta = $usuarios->validarEmail($email);
	if ($respuesta !== false && !empty($respuesta)) {
		echo json_encode($respuesta);
	} else {
		echo json_encode(null);
	}
	break;


	case 'validarOpenID':
	$openid = $_POST['openid'];
	$respuesta = $usuarios->validarOpenID($openid);
	if ($respuesta !== false && !empty($respuesta)) {
		echo json_encode($respuesta);
	} else {
		echo json_encode(null);
	}
	break;



}



?>