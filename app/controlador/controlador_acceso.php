<?php 

/**
* Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo es el controlador de acceso, se encarga de validar el inicio de sesion y mostrar las vistas despues de loguearse correctamente
 * asi como tambien de destruir la sesion.
 */


session_start();

require_once "../modelo/modelo_acceso.php";

$acceso = new Acceso();



switch ($_GET["op"]) {
	
	case 'verificar':

	$user = isset($_POST['user']) ? trim($_POST['user']) : null;
	$pass = isset($_POST['pass']) ? trim($_POST['pass']) : null;



	$rspta=$acceso->verificar($user, $pass);
	
	if ($rspta !== false) {
		$_SESSION['id'] = $rspta['id'];
		$_SESSION['fullname'] = $rspta['fullname'];
		$_SESSION['openid'] = $rspta['openid'];
	}


	echo json_encode($rspta);

	break;

	case 'salir':
	session_unset();

	  //destruimos la sesion
	session_destroy();
		  //redireccionamos al login
	header("Location: ../../index.html");
	break;
	
}
?>

