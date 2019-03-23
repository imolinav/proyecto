<div class="container col-12 col-md-8">
    <div class="row">
        <div class="col-12 imagen_index">
            <?php if($hora>=00 && $hora<=05): ?>
                <img src="imgs/index1.jpg" class="img-fluid">
            <?php elseif($hora>=06 && $hora<=11): ?>
                <img src="imgs/index2.jpg" class="img-fluid img-index">
            <?php elseif($hora>=12 && $hora<=17): ?>
                <img src="imgs/index3.jpg" class="img-fluid img-index">
            <?php elseif($hora>=18 && $hora<=23): ?>
                <img src="imgs/index4.jpg" class="img-index">
            <?php endif; ?>
            <div id="index_title">
                <h1>SMART LIVING</h1>
                <hr>
                <h3>Donde tu casa cobra vida</h3>
                <a href="contacto.php" class="btn btn-light">Contacta con nosotros</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementsByTagName("button")[0].onclick = login;

    function login() {
        let divisor = document.createElement('form');
        divisor.setAttribute('class', 'formulario');
        divisor.setAttribute('action', 'index.php');
        divisor.setAttribute('method', 'post');

        let difuminador = document.createElement('div');
        difuminador.setAttribute('id', 'difuminador');
        let parrafo = document.createElement('p');
        let texto = document.createTextNode("Iniciar Sesion");
        parrafo.appendChild(texto);

        let p1 = document.createElement('p');
        let text_p1 = document.createTextNode('DNI');
        p1.appendChild(text_p1);
        let input1 = document.createElement('input');
        input1.setAttribute('type', 'text');
        input1.setAttribute('class', 'form-control');
        input1.setAttribute('name', 'li_dni');

        let small1 = document.createElement('small');
        small1.setAttribute('id', 'dniHelp');
        small1.setAttribute('class', 'form-text text-muted');
        let small1_text = document.createTextNode("Introduce tu DNI en formato 00000000-X");
        small1.appendChild(small1_text);

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

        let boton1 = document.createElement('button');
        boton1.setAttribute('type', 'button');
        boton1.setAttribute('class', 'btn btn-success mt-4 mb-4 mr-2');
        let texto_b1 = document.createTextNode("Iniciar sesion");
        boton1.appendChild(texto_b1);

        let boton2 = document.createElement('button');
        boton2.setAttribute('type', 'button');
        boton2.setAttribute('class', 'btn btn-danger mt-4 mb-4 ml-2');
        let texto_b2 = document.createTextNode("Cancelar");
        boton2.appendChild(texto_b2);

        //let salto = document.createElement('br');
        divisor.appendChild(parrafo);
        divisor.appendChild(p1);
        divisor.appendChild(input1);
        divisor.appendChild(small1);
        divisor.appendChild(p2);
        divisor.appendChild(input2);
        divisor.appendChild(small2);
        //divisor.appendChild(salto);
        divisor.appendChild(boton1);
        divisor.appendChild(boton2);
        document.body.appendChild(difuminador);
        document.body.appendChild(divisor);

        document.getElementById('difuminador').onclick = cerrarForm;
        document.getElementsByTagName('button')[1].onclick = comprobarForm;
        document.getElementsByTagName('button')[2].onclick = cerrarForm;
    }

    function cerrarForm() {
        document.body.removeChild(document.getElementsByTagName('form')[1]);
        document.body.removeChild(document.getElementById('difuminador'));
    }

    function comprobarForm(event) {
        let p_error = document.createElement('p');
        p_error.setAttribute('id', 'texto_error');
        let texto_error;


        let httpRequest = obtainXMLHttpRequest();
        httpRequest.open('POST', 'comprobacion_login.php', true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = function() {
            if(httpRequest.readyState === 4) {
                if(httpRequest.status === 200) {
                    if(httpRequest.responseText === "opcion1") {
                        texto_error = document.createTextNode("El usuario no existe");
                        p_error.appendChild(texto_error);
                        event.target.parentNode.insertBefore(p_error, event.target);
                    } else if(httpRequest.responseText === "opcion2") {
                        texto_error = document.createTextNode("La contraseña es incorrecta");
                        p_error.appendChild(texto_error);
                        event.target.parentNode.insertBefore(p_error, event.target);
                    } else {
                        event.target.parentNode.submit();
                    }
                }
            }
        }
        let data = new Datos(document.getElementsByName('li_dni')[0].value, document.getElementsByName('li_pass')[0].value);
        let json_data = JSON.stringify(data);
        httpRequest.send('datos='+json_data);

    }

    function Datos(dni, pass) {
        this.dni = dni;
        this.pass = pass;
    }

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

</script>



</body>
</html>