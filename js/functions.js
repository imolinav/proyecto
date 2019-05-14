/* CERRAR SESION */

document.getElementsByTagName("button")[0].onclick = logout;
function logout(event) {
    event.target.parentNode.submit();
}

/* CARGANDO */

function cargando() {
    let span = document.createElement('span');
    span.setAttribute('class', 'loading');
    let difuminador = document.createElement('div');
    difuminador.setAttribute('id', 'difuminador');
    document.body.appendChild(difuminador);
    document.body.appendChild(span);
}

function quitarCargando() {
    if(document.getElementsByClassName('loading')[0]) {
        document.body.removeChild(document.getElementsByClassName('loading')[0]);
        document.body.removeChild(document.getElementById('difuminador'));
    }
}

/* CAMBIAR IDIOMA */

$('#castellano').click(cambiarIdioma);
$('#ingles').click(cambiarIdioma);

function cambiarIdioma(event) {
    $('#idioma_selected').val(event.target.id);
    event.target.parentNode.submit();
}

/* OBTENER XMLHTTP */

function obtainXMLHttpRequest() {
    let httpRequest;
    if(window.XMLHttpRequest) {
        httpRequest = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        try {
            httpRequest = new ActiveXObject('MSXML2.XMLHTTP');
        } catch(e) {
            try {
                httpRequest = new ActiveXObject('Microsoft.XMLHTTP');
            } catch(e) {}
        }
    }
    if (!httpRequest) {
        return false;
    } else {
        return httpRequest;
    }
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

$(function () {
    $('[data-toggle="popover"]').popover()
});
