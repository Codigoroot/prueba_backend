document.getElementById('frmAcceso').addEventListener('submit', function(e) {
    e.preventDefault();

    var user = document.getElementById('user').value;
    var pass = document.getElementById('pass').value;

    var formData = new FormData();
    formData.append('user', user);
    formData.append('pass', pass);

    fetch('../app/controlador/controlador_acceso.php?op=verificar', {
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
        console.log(data);
        if (data !== false) {
            window.location.href = '../app/vista/registros.php';
        } else {
            console.log('Datos incorrectos');
            alert('Usuario y/o Password incorrectos');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
    });
});

function CerrarSesion() {
    window.location.href = '../controlador/controlador_acceso.php?op=salir';
}


