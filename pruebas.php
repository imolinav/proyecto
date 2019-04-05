<?php

    print_r($_POST['prueba']);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="pruebas.php" method="post">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="checkbox" value="si" name="prueba[]">
    <input type="button" value="enviar" id="enviar">
</form>
<script>
    document.getElementById('enviar').onclick = enviar;

    function enviar(event) {
        for(let i = 0; i<document.getElementsByName('prueba[]').length; i++) {
            if(document.getElementsByName('prueba[]')[i].checked===false) {
                document.getElementsByName('prueba[]')[i].checked = true;
                document.getElementsByName('prueba[]')[i].value = 'no';
            }
        }
        event.target.parentNode.submit();
    }
</script>
</body>
</html>