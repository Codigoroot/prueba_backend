<?php 

ob_start();
session_start();
if (!isset($_SESSION['fullname'])) {
	header("Location: ../../index.html");
}else{

/**
 * 	Autor: Javier Aguilar
 * 	Fecha: 18/03/2024

  
  **Descripción de la funcionalidad del archivo principal**

Este archivo cumple la función de ser la página principal de la aplicación. En esta página, se muestran dos tablas separadas para usuarios y comentarios. La aplicación presenta varias restricciones y funcionalidades adicionales, que incluyen:

1. **Funcionalidad de Like y Dislike:** Se ha implementado la capacidad de dar like o dislike a los comentarios, lo que permite aumentar o disminuir el número de likes de cada comentario respectivamente.

2. **Validaciones de Registros Únicos:** Se han añadido validaciones para evitar la inserción de registros duplicados basados en el email o el openid de los usuarios. Esto asegura que no se puedan agregar usuarios con información repetida.

3. **Gestión de Comentarios:** Los usuarios tienen la capacidad de agregar, editar y eliminar sus propios comentarios, pero no tienen permisos para modificar los comentarios de otros usuarios. Además, los usuarios solo pueden visualizar los comentarios de otros y darles like o dislike sin editarlos.

4. **Eliminación de Usuarios con Comentarios Activos:** Se ha implementado una restricción que impide la eliminación de usuarios que tienen comentarios activos. Antes de eliminar un usuario, es necesario eliminar todos sus comentarios.

En resumen, esta página principal proporciona una interfaz clara y funcionalidades específicas que mejoran la experiencia del usuario al interactuar con usuarios y comentarios en la aplicación.

 */

	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Prueba Backend</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
		<!-- Default theme -->
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
		<!-- Semantic UI theme -->
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
		<!-- Bootstrap theme -->
		<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="estilos/estilos.css">

	</head>
	<body>

		<div class="container mt-5" >
			<div class="row">
				<div class="col-md-12 d-flex justify-content-end" >
					<p style="font-weight: bold">Bienvenido/a,
						<input type="hidden" name="id_user" id="id_user" value="<?php echo $_SESSION['id']; ?>">
						<?php echo $_SESSION['fullname']; ?>
					</p>
					<button class="btn btn-outline-danger ml-3 border-0" id="CerrarSesion" name="CerrarSesion" data-toggle="modal" onclick="CerrarSesion()">
						<i class="fas fa-sign-out-alt"></i> Cerrar Sesión
					</button>
				</div>
			</div>



			<div class="row">
				<ul class="nav nav-pills" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="pill" href="#comentarios">Mis comentarios</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="pill" href="#usuarios">Usuarios</a>
					</li>
				</ul>                   
			</div>



			<div class="tab-content">
				<div id="comentarios" class="tab-pane active"><br>
					<div class="tile panel_comentarios">
						<h3 class="tile-title">Mis comentarios</h3>
						<div class="row p-4">
							<button class="btn btn-success" id="agregarComentario" name="agregarComentario" data-toggle="modal" data-target="#ModalComentarios"> <i class="fas fa-comments"></i> Agregar comentario</button>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="table-responsive scrollbar ">
									<table class="table nowrap" name="tabla_comentarios" id="tabla_comentarios">
										<thead>
											<tr>
												<td>Opciones</td>
												<td>Autor Comentario</td>
												<td>Comentario</td>
												<td>Likes</td>
												<td>Reaccionar</td>
												<td>Fecha creación</td>
												<td>Fecha Edicion</td>
												
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="usuarios" class="tab-pane fade"><br>
					<div class="tile panel_usuarios ">
						<h3 class="tile-title">Todos los usuarios</h3>
						<div class="row p-4">
							<button class="btn btn-success" id="agregarUsuario" name="agregarUsuario" data-toggle="modal" data-target="#ModalUsuarios"> <i class="fa fa-user-plus"></i> Agregar usuario</button>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="table-responsive scrollbar ">
									<table class="table nowrap" name="tabla_usuarios" id="tabla_usuarios">
										<thead>
											<tr>
												<td>Opciones</td>
												<td>Id Usuario</td>
												<td>Nombre</td>
												<td>Correo</td>
												<td>Contraseña</td>
												<td>OpenID</td>
												<td>Fecha creación</td>
												<td>Fecha Edicion</td>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>


			<div class="modal fade" id="comentarios_usuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-custom" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Comentarios del usuario : <span id="c_usuario"  style="max-width: 90vw; overflow: hidden; text-overflow: ellipsis;"></span>
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-12">
									<div class="table-responsive scrollbar ">
										<table class="table nowrap" name="tabla_comentarios_user" id="tabla_comentarios_user">
											<thead>
												<tr>
													<td>Comentario</td>
													<td>Likes</td>
													<td>Fecha creación</td>
													<td>Fecha Edicion</td>
													<td>Likes</td>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="ModalUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Datos de usuario</h5>
							<label id="mensaje"></label>
							<label id="mensaje1"></label>
							<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form name="formulario" id="formulario">
								<div class="row">
									<div class="col-md-6">
										<input type="hidden" name="id" id="id">
										<label>Nombre:</label>
										<input class="form-control" type="text" name="fullname" id="fullname" required>
									</div>
									<div class="col-md-6">
										<label>Correo:</label>
										<input class="form-control" type="text" name="email" id="email" required>
									</div>
									<div class="col-md-6">
										<label>Contraseña:</label>
										<input class="form-control" type="text" name="pass" id="pass" required>
									</div>
									<div class="col-md-6">
										<label>Open ID:</label>
										<input class="form-control" type="text" name="openid" id="openid" required>
									</div>

								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" id="btn_user" name="btn_user" onclick="validarDatos()">Guardar cambios</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="ModalComentarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Comentarios</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form name="formularioComentarios" id="formularioComentarios">
								<div class="row">
									<div class="col-md-6">
										<input type="hidden" name="id_comentario" id="id_comentario">
										<input type="hidden" name="id_user" id="id_user" value="<?php echo $_SESSION['id']; ?>">
									</div>
									<div class="col-md-12">
										<label>Comentario:</label>
										<textarea class="form-control" id="coment_text" name="coment_text"></textarea>
									</div>

								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-primary" onclick="guardarEditarComentario()">Guardar cambios</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	</html>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/js/all.min.js" integrity="sha512-MNA4ve9aW825/nbJKWOW0eo0S5f2HWQYQEIw4TkgLYMgqk88gHpSHJuMkJhYMQWKE7LmJMBdJZMs5Ua19QbF8Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script type="text/javascript" src="scripts/usuarios.js"></script>
	<script type="text/javascript" src="scripts/comentarios.js"></script>
	<script type="text/javascript" src="scripts/acceso.js"></script>
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

	<?php 
}
ob_end_flush();
?>

