<?php
session_start();
require_once "database/Connection.php";
require_once "twitter/TwitterAPIExchange.php";
require_once "entities/Usuario.php";

$conexion = Connection::make();

//Mantener usuario si ha iniciado sesión

if (isset($_SESSION['email'])) {
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email'=>$_SESSION['email']];
    $stmt->execute($parameters);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Usuario");
    $usuario = $stmt->fetch();

    $mensajes_nl = comprobarMsgs($conexion, $usuario->getEmail());


} else {
    if($_SERVER['PHP_SELF']!='/proyecto/index.php') {
        header('Location: index.php');
    }
}

if(!isset($_SESSION['email']) && $_SERVER['PHP_SELF']!='/proyecto/index.php') {
    header('Location: index.php');
}

//Comprobar mensajes

function comprobarMsgs($conexion, $email) {
    $stmt = $conexion->prepare("SELECT * FROM mensaje where para = :usuario AND leido = 0");
    $parameters = [':usuario'=>$email];
    $stmt->execute($parameters);
    $mensajes_nl = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $mensajes_nl;
}

//Cerrar sesión

if(isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
}

//Carga de API OpenWeather

function cargaTiempo($latitud, $longitud) {
    $clave = "http://api.openweathermap.org/data/2.5/forecast?lat=".$latitud."&lon=".$longitud."&APPID=d0cb0d8b429769a6e1105782251c99aa&units=metric&lang=es";
    $data = file_get_contents($clave);
    $data_parsed = json_decode($data, true);
    return $data_parsed;
}

//Carga comunidades, provincias y municipios

$stmt_com = $conexion->prepare("SELECT * FROM comunidades");
$stmt_com->execute();
$comunidades = $stmt_com->fetchAll(PDO::FETCH_ASSOC);

function cargaProvincias($conexion, $comunidad) {
    $stmt_prov = $conexion->prepare("SELECT * FROM provincias WHERE comunidad_id = $comunidad");
    $stmt_prov->execute();
    $provincias = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);
    return $provincias;
}

function cargaMunicipios($conexion, $provincia) {
    $stmt_prov = $conexion->prepare("SELECT * FROM municipios WHERE provincia_id = $provincia");
    $stmt_prov->execute();
    $municipios = $stmt_prov->fetchAll(PDO::FETCH_ASSOC);
    return $municipios;
}

//Cambio de idioma

$ruta = "lengC.php";
if (isset($_POST['lengua'])) {
    if ($_POST['lengua'] != "castellano") {
        $ruta = "lengI.php";
    }
    header('Location: '.$_POST['page']);
} else if (isset($_SESSION['idioma'])) {
    if ($_SESSION['idioma'] != "lengC.php") {
        $ruta = "lengI.php";
    }
}

//Cerrar sesión con idioma mantenido (NOT USED)

if (isset($_POST['cerrar'])) {
    $idioma = $_SESSION['idioma'];
    session_destroy();
    session_start();
    $_SESSION['idioma'] = $idioma;
    header('Location: index.php');
}
$_SESSION['idioma'] = $ruta;
include $_SESSION['idioma'];



//Metodos Twitter

function publicarTweet($settings, $texto) {
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $postfields = array('status'=>$texto);
    $requestMethod = 'POST';
    $twitter = new TwitterAPIExchange($settings);
    $twitter->setPostfields($postfields)->buildOauth($url, $requestMethod)->performRequest();
}

function obtenerTweets($settings, $usuario, $cantidad) {
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getField = '?screen_name='.$usuario.'&count='.$cantidad;
    $twitter = new TwitterAPIExchange($settings);
    $informacion = json_decode($twitter->setGetfield($getField)->buildOauth($url, $requestMethod)->performRequest(), $assoc=TRUE);
    if(array_key_exists("errors", $informacion)) {
        echo "<h3>Lo sentimos, ha habido un problema</h3><p>Twitter ha devuelto el siguiente mensaje de error: </p><p><em>".$informacion['errors'][0]['mensaje']."</em></p>";
        exit();
    }
    foreach($informacion as $items) {
        echo "Fecha y hora del Tweet: ".$items['created_at'].'<br>';
        echo "Tweet: ".$items['text'].'<br>';
        echo "Usuario: ".$items['user']['name'].'<br>';
        echo "Nombre: ".$items['user']['screen_name'].'<br>';
        echo "Followers: ".$items['user']['follower_count'];
    }
}

?>