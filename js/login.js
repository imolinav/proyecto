if(document.getElementsByTagName('button')[0].id!=="logout") {
    document.getElementsByTagName("button")[0].onclick = login;
}
document.body.onkeyup = accionesTeclado;

function accionesTeclado(event) {
    if(event.key==="Enter") {
        if(document.getElementById('li_aceptar')) {
            comprobarForm();
        }
    }
}

function login() {
    let divisor = document.createElement('form');
    divisor.setAttribute('class', 'formulario');
    divisor.setAttribute('id', 'form_li');
    divisor.setAttribute('action', 'index.php');
    divisor.setAttribute('method', 'post');

    let difuminador = document.createElement('div');
    difuminador.setAttribute('id', 'difuminador');
    let parrafo = document.createElement('p');
    let texto = document.createTextNode("Iniciar Sesion");
    parrafo.appendChild(texto);

    let p1 = document.createElement('p');
    let text_p1 = document.createTextNode('E-mail');
    p1.appendChild(text_p1);
    let input1 = document.createElement('input');
    input1.setAttribute('type', 'text');
    input1.setAttribute('class', 'form-control');
    input1.setAttribute('name', 'li_email');

    let small1 = document.createElement('small');
    small1.setAttribute('id', 'emailHelp');
    small1.setAttribute('class', 'form-text text-muted');
    let small1_text = document.createTextNode("Introduce tu e-mail (xxxxx@yyyyy.qqq)");
    small1.appendChild(small1_text);

    let div_error1 = document.createElement('div');
    div_error1.setAttribute('class', 'invalid-feedback');
    let error_text1 = document.createTextNode('Es necesario completar este campo');
    div_error1.appendChild(error_text1);

    let p2 = document.createElement('p');
    let text_p2 = document.createTextNode('Contraseña');
    p2.appendChild(text_p2);
    let input2 = document.createElement('input');
    input2.setAttribute('type', 'password');
    input2.setAttribute('class', 'form-control');
    input2.setAttribute('name', 'li_pass');

    let small2 = document.createElement('small');
    small2.setAttribute('id', 'passwHelp');
    small2.setAttribute('class', 'form-text text-muted');
    let small_a = document.createElement('a');
    small_a.setAttribute('href', 'recuperar.php');
    let small2_text = document.createTextNode("¿Has olvidado tu contraseña?");
    small_a.appendChild(small2_text);
    small2.appendChild(small_a);

    let div_error2 = document.createElement('div');
    div_error2.setAttribute('class', 'invalid-feedback');
    let error_text2 = document.createTextNode('Es necesario completar este campo');
    div_error2.appendChild(error_text2);

    let boton1 = document.createElement('button');
    boton1.setAttribute('type', 'button');
    boton1.setAttribute('class', 'btn btn-success mt-4 mb-4 mr-2');
    boton1.setAttribute('id', 'li_aceptar');
    let texto_b1 = document.createTextNode("Iniciar sesion");
    boton1.appendChild(texto_b1);

    let boton2 = document.createElement('button');
    boton2.setAttribute('type', 'button');
    boton2.setAttribute('class', 'btn btn-secondary mt-4 mb-4 ml-2');
    boton2.setAttribute('id', 'li_cancelar');
    let texto_b2 = document.createTextNode("Cancelar");
    boton2.appendChild(texto_b2);

    //let salto = document.createElement('br');
    //divisor.appendChild(parrafo);
    divisor.appendChild(p1);
    divisor.appendChild(input1);
    divisor.appendChild(div_error1);
    divisor.appendChild(small1);
    divisor.appendChild(p2);
    divisor.appendChild(input2);
    divisor.appendChild(div_error2);
    divisor.appendChild(small2);
    //divisor.appendChild(salto);
    divisor.appendChild(boton1);
    divisor.appendChild(boton2);
    document.body.appendChild(difuminador);
    document.body.appendChild(divisor);


    document.getElementById('difuminador').onclick = cerrarForm;
    document.getElementById('li_aceptar').onclick = comprobarForm;
    document.getElementById('li_cancelar').onclick = cerrarForm;
}

function cerrarForm() {
    document.body.removeChild(document.getElementById('form_li'));
    document.body.removeChild(document.getElementById('difuminador'));
}

function comprobarForm() {
    let p_error = document.createElement('p');
    p_error.setAttribute('id', 'texto_error');
    let texto_error;
    let regex_email = /^.+@.+\..+$/;
    document.getElementsByName('li_email')[0].classList.remove('is-invalid');
    document.getElementsByName('li_pass')[0].classList.remove('is-invalid');
    if(document.getElementById('texto_error')) {
        document.getElementById('texto_error').parentNode.removeChild(document.getElementById('texto_error'));
    }

    if(document.getElementsByName('li_email')[0].value==="" || document.getElementsByName('li_pass')[0].value==="" || regex_email.test(document.getElementsByName('li_email')[0].value)===false) {
        if(document.getElementsByName('li_email')[0].value==="" || regex_email.test(document.getElementsByName('li_email')[0].value)===false) {
            document.getElementsByName('li_email')[0].classList.add('is-invalid');
        }
        if(document.getElementsByName('li_pass')[0].value==="") {
            document.getElementsByName('li_pass')[0].classList.add('is-invalid');
        }
    } else {
        let httpRequest = obtainXMLHttpRequest();
        httpRequest.open('POST', 'comprobacion_login.php', true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState === 1) {
                cargando();
            }
            if (httpRequest.readyState === 4) {
                if (httpRequest.status === 200) {
                    quitarCargando();
                    if (httpRequest.responseText === "opcion1") {
                        texto_error = document.createTextNode("El usuario o la contraseña son incorrectos");
                        p_error.appendChild(texto_error);
                        document.getElementById('li_aceptar').parentNode.insertBefore(p_error, document.getElementById('li_aceptar'));
                    } else {
                        document.getElementById('li_aceptar').parentNode.submit();
                    }
                }
            }
        };
        let data = new Datos(document.getElementsByName('li_email')[0].value, document.getElementsByName('li_pass')[0].value);
        let json_data = JSON.stringify(data);
        httpRequest.send('datos=' + json_data);
    }

}

function Datos(email, pass) {
    this.email = email;
    this.pass = pass;
}