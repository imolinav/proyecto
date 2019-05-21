/* CERRAR SESION */

document.getElementsByTagName("button")[0].onclick = logout;
function logout(event) {
    event.target.parentNode.submit();
}

/* SESSIONSTORAGE */

document.body.onload = avisarCookies;
function avisarCookies() {
    let aceptadas = sessionStorage.getItem('aceptadas');
    if(aceptadas == null) {
        let div = document.createElement('div');
        div.setAttribute('class', 'aviso_cookies');
        let texto1 = document.createTextNode('Esta página no utiliza cookies, pero si que guarda algunas de tus decisiones en el navegador para hacer tu navegación más comoda.');
        let texto2 = document.createTextNode('Si aun asi prefiere que no las guardemos, haga click en "No guardar".');
        let p1 = document.createElement('p');
        let p2 = document.createElement('p');
        p1.appendChild(texto1);
        p2.appendChild(texto2);
        let boton1 = document.createElement('input');
        boton1.setAttribute('type', 'button');
        boton1.setAttribute('class', 'btn btn-primary mr-4 mt-3');
        boton1.setAttribute('name', 'aceptar_cookies');
        boton1.setAttribute('value', 'Aceptar');
        let boton2 = document.createElement('input');
        boton2.setAttribute('type', 'button');
        boton2.setAttribute('class', 'btn btn-danger ml-4 mt-3');
        boton2.setAttribute('name', 'cancelar_cookies');
        boton2.setAttribute('value', 'No guardar');
        div.appendChild(p1);
        div.appendChild(p2);
        div.appendChild(boton1);
        div.appendChild(boton2);
        document.body.appendChild(div);
        document.getElementsByName('aceptar_cookies')[0].onclick = function() {
            sessionStorage.setItem('aceptadas', 'si');
            document.body.removeChild(document.getElementsByClassName('aviso_cookies')[0]);
        };
        document.getElementsByName('cancelar_cookies')[0].onclick = function() {
            sessionStorage.setItem('aceptadas', 'no');
            document.body.removeChild(document.getElementsByClassName('aviso_cookies')[0]);
        };
    }
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

$.fn.select2.defaults.set( "theme", "bootstrap" );