/**
 * Autor: Javier Aguilar
 * Fecha: 18/03/2024
 * Descripción: Este archivo JavaScript contiene los métodos necesarios para interactuar con los endpoints de PHP 
 * encargados de realizar operaciones CRUD en la base de datos de comentarios. Además, incluye funcionalidades para la 
 * manipulación del DOM y la actualización de vistas.
 */



var tabla_comentarios;
var tabla_comentarios_user;

$('#agregarComentario').click(function(){
    limpiarInputs();
});

$(document).ready(function(){
    var id = document.getElementById('id_user').value;
    console.log(id);

    var url = '../controlador/controlador_comentario.php?opcion=listarComentariosUser&id=' + id;

    tabla_comentarios = $('#tabla_comentarios').dataTable({
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
});

function guardarEditarComentario() {
    var formulario = document.getElementById('formularioComentarios');
    var datos_formulario = new FormData(formulario);

    fetch('../controlador/controlador_comentario.php?opcion=guardarEditarComentario', {
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
        if (data === 'si') {
            Swal.fire({
                icon: 'success',
                title: 'Genial',
                text: 'Operación Exitosa',
            });
            limpiarInputs();
            $('#ModalComentarios').modal('hide');
            tabla_comentarios.ajax.reload();
        } else {
            $('#ModalComentarios').modal('hide');
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

function mostrarComentario(id) {
    limpiarInputs();
    $('#ModalComentarios').modal('show');
    var formData = new FormData();
    formData.append('id', id);

    fetch('../controlador/controlador_comentario.php?opcion=mostrarComentario', {
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
        document.getElementById('id_comentario').value = data.id;
        document.getElementById('id_user').value = data.user;
        document.getElementById('coment_text').value = data.coment_text;
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
    });
}

function eliminarComentario(id){
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

            fetch('../controlador/controlador_comentario.php?opcion=eliminarComentario', {
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
                    tabla_comentarios.ajax.reload();
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

function like(id){
    var formData = new FormData();
    formData.append('id', id);

    fetch('../controlador/controlador_comentario.php?opcion=like', {
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
            alertify.success('Like');
            tabla_comentarios.ajax.reload();
            tabla_comentarios_user.ajax.reload();
        } else {
            alertify.success('Error de Like');
        }
    });
}

function dislike(id){
    var formData = new FormData();
    formData.append('id', id);

    fetch('../controlador/controlador_comentario.php?opcion=dislike', {
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
            alertify.error('Dislike');
            tabla_comentarios.ajax.reload();
            tabla_comentarios_user.ajax.reload();
        } else {
            alertify.error('Error de Dislike');
        }
    });
}

function limpiarInputs() {
    document.getElementById('id_comentario').value = '';
    document.getElementById('id_user').value = '';
    document.getElementById('coment_text').value = '';
}
