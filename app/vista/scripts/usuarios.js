/**
 * Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo JavaScript contiene los métodos necesarios para interactuar con los endpoints de PHP 
 * encargados de realizar operaciones CRUD en la base de datos de usuarios. Además, incluye funcionalidades para la 
 * manipulación del DOM y la actualización de vistas.
 */


var tabla_usuarios;

$('#agregarUsuario').click(function(){
    limpiarInputs_usuario();
    $('#mensaje').text('');
});

$(document).ready(function(){
    tabla_usuarios = $('#tabla_usuarios').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "ajax": {
            url: '../controlador/controlador_usuario.php?opcion=listarUsuarios',
            method: 'GET',
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown){
                console.log("Error en la petición Ajax: " + textStatus + " - " + errorThrown);
                console.log(jqXHR.responseText);
            }
        },
        "bDestroy": true,
        "iDisplaylength": 7,
        "order": [[0, "desc"]]
    }).DataTable();
});

function validarDatos(){
    var id = $('#id').val().trim();
    var email = $('#email').val().trim();
    var openid = $('#openid').val().trim();
    $('#mensaje').text('');
    $('#mensaje1').text('');

    if (openid === "") {
        $('#mensaje1').text('Ingrese un openid');
        return;
    }

    if (email === "") {
        $('#mensaje1').text('Ingrese un email');
        return;
    }

    if (id === '') {
        $.ajax({
            url: '../controlador/controlador_usuario.php?opcion=validarOpenID',
            method: 'POST',
            data: {openid: openid},
            success: function(response) {
                datos = JSON.parse(response);
                if (datos != null) {
                    $('#mensaje').text('Ya existe un registro con este openid, intente con otro.');
                } else {
                    $.ajax({
                        url: '../controlador/controlador_usuario.php?opcion=validarEmail',
                        method: 'POST',
                        data: {email: email},
                        success: function(response) {
                            datos = JSON.parse(response);
                            if (datos != null ) {
                                $('#mensaje1').text('Ya existe un registro con este email, intente con otro.');
                            } else {
                                guardarEditarUsuario();
                            }
                        }
                    });
                }
            }
        });
    } else {
        guardarEditarUsuario();
    }
}

function guardarEditarUsuario() {
    var formulario = document.getElementById('formulario');
    var datos_formulario = new FormData(formulario);

    fetch('../controlador/controlador_usuario.php?opcion=guardarEditarUsuario', {
        method: 'POST',
        body: datos_formulario
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud AJAX');
        }
        return response.text();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
        if (data === 'si') {
            Swal.fire({
                icon: 'success',
                title: 'Genial',
                text: 'Operación Exitosa',
            });
            limpiarInputs_usuario();
            $('#ModalUsuarios').modal('hide');
            tabla_usuarios.ajax.reload();
        } else {
            $('#ModalUsuarios').modal('hide');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Algo salió mal',
            });
        }
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
    });
}

function mostrarUsuario(id) {
    $('#mensaje').text('');
    limpiarInputs_usuario();
    $('#ModalUsuarios').modal('show');
    var formData = new FormData();
    formData.append('id', id);

    fetch('../controlador/controlador_usuario.php?opcion=mostrarUsuario', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud AJAX');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('id').value = data.id;
        document.getElementById('fullname').value = data.fullname;
        document.getElementById('email').value = data.email;
        document.getElementById('pass').value = data.pass;
        document.getElementById('openid').value = data.openid;
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
    });
}

function validarComentarios(user){
    var id = user;
    var formData = new FormData();
    formData.append('user', user);

    fetch('../controlador/controlador_comentario.php?opcion=validarComentario', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la solicitud AJAX');
        }
        return response.text();
    })
    .then(data => {
        if (data > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Alerta',
                text: 'No se puede eliminar el usuario ya que cuenta con ' + data + ' comentarios, elimine los comentarios desde la cuenta del usuario y posteriormente elimine al usuario.',
            });
        } else {
            eliminarUsuario(id);
        }
    });
}

function eliminarUsuario(id){
    console.log(id);
    Swal.fire({
        title: '¿Quiere eliminar el registro?',
        text: 'Esta opción no se puede revertir',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!'
    }).then((result)=>{
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('id', id);

            fetch('../controlador/controlador_usuario.php?opcion=eliminarUsuario', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud AJAX');
                }
                return response.text();
            })
            .then(data => {
                if (data === 'si') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Genial',
                        text: 'El registro se eliminó correctamente',
                    });
                    tabla_usuarios.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el registro',
                    });
                }
            });
        }
    });
}

function mostrarComentariosUser(id, fullname){
    $('#comentarios_usuarios').modal('show');
    console.log(fullname);
    document.getElementById('c_usuario').textContent = fullname;
    var url = '../controlador/controlador_comentario.php?opcion=listarComentariosUser_1&id=' + id;

    tabla_comentarios_user = $('#tabla_comentarios_user').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "ajax": {
            url: url,
            method: 'GET',
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown){
                console.log("Error en la petición Ajax: " + textStatus + " - " + errorThrown);
                console.log(jqXHR.responseText);
            }
        },
        "bDestroy": true,
        "iDisplaylength": 7,
        "order": [[0, "desc"]]
    }).DataTable();
}

function limpiarInputs_usuario() {
    document.getElementById('id').value = '';
    document.getElementById('fullname').value = '';
    document.getElementById('email').value = '';
    document.getElementById('pass').value = '';
    document.getElementById('openid').value = '';
}

