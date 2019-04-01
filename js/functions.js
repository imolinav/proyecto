document.getElementsByTagName("button")[0].onclick = logout;

/* CERRAR SESION */

function logout(event) {
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