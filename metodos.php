<?php
session_start();
require_once "database/Connection.php";
require_once "twitter/TwitterAPIExchange.php";
require_once "entities/Usuario.php";

$conexion = Connection::make();
date_default_timezone_set("Europe/Madrid");

//Cerrar sesión

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
}

//Cambio de idioma

$ruta = "lengC.php";
if (isset($_POST['lengua'])) {
    if ($_POST['lengua'] != "castellano") {
        $ruta = "lengI.php";
    }
    header('Location: ' . $_POST['page']);
} else if (isset($_SESSION['idioma'])) {
    if ($_SESSION['idioma'] != "lengC.php") {
        $ruta = "lengI.php";
    }
}

//Mantener usuario si ha iniciado sesión

if (isset($_SESSION['email'])) {
    $usuario = getUsuario($conexion, $_SESSION['email']);

    $mensajes_nl = comprobarMsgs($conexion, $usuario->getEmail());

    $cp = $usuario->getCp();
    $stmt_tiempo = $conexion->prepare("SELECT AVG(P.lat) as latitud, AVG(P.lon) as longitud FROM poblacion P, codigopostal C WHERE P.poblacionid = C.poblacionid AND C.codigopostalid = :cp");
    $parameters_tiempo = [':cp' => $cp];
    $stmt_tiempo->execute($parameters_tiempo);
    $poblacion = $stmt_tiempo->fetch(PDO::FETCH_ASSOC);
    $tiempo = cargaTiempo($poblacion['latitud'], $poblacion['longitud']);
}

// Get usuario (objeto)

function getUsuario($conexion, $email)
{
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email' => $email];
    $stmt->execute($parameters);
    $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Usuario");
    $usuario = $stmt->fetch();
    return $usuario;
}

// Get escena

function getEscena($conexion, $id)
{
    $stmt = $conexion->prepare("SELECT * FROM escena WHERE id = :id");
    $parameters = [':id' => $id];
    $stmt->execute($parameters);
    $escena = $stmt->fetch(PDO::FETCH_ASSOC);
    return $escena;
}

// Get pswd_rec

function getPsswRec($conexion, $email)
{
    $stmt = $conexion->prepare("SELECT * FROM pswd_rec WHERE usuario_email = :email");
    $parameters = [':email' => $email];
    $stmt->execute($parameters);
    $reco = $stmt->fetch(PDO::FETCH_ASSOC);
    return $reco;
}

//Comprobar mensajes

function comprobarMsgs($conexion, $email)
{
    $stmt = $conexion->prepare("SELECT * FROM mensaje where para = :usuario AND leido = 0");
    $parameters = [':usuario' => $email];
    $stmt->execute($parameters);
    $mensajes_nl = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $mensajes_nl;
}

// Buscar usuario

function buscarUsuario($conexion, $email)
{
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email' => $email];
    $stmt->execute($parameters);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    return $usuario;
}

// Buscar dispositivo

function buscarDispositivo($conexion, $id)
{
    $stmt = $conexion->prepare("SELECT * FROM dispositivo WHERE id = :id");
    $parameters = [':id' => $id];
    $stmt->execute($parameters);
    $dispositivo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $dispositivo;
}

// Añadir dispositivo

function addDispositivo($conexion, $nombre, $habitacion, $usuario_email, $pin, $tipo) {
    $stmt_disp = $conexion->prepare("INSERT INTO dispositivo (nombre, habitacion, encendido, num_encendidos, tiempo_encendido, temperatura, usuario_email, pin, tipo) VALUES (:nombre, :habitacion, 0, 0, 0, :temperatura, :usuario, :pin, :tipo)");
    if ($tipo == 2) {
        $parameters_disp = [':nombre' => $nombre, ':habitacion' => $habitacion, ':temperatura' => 0, ':usuario' => $usuario_email, ':pin' => $pin, ':tipo' => $tipo];
    } else {
        $parameters_disp = [':nombre' => $nombre, ':habitacion' => $habitacion, ':temperatura' => null, ':usuario' => $usuario_email, ':pin' => $pin, ':tipo' => $tipo];
    }
    $stmt_disp->execute($parameters_disp);
}

// Añadir programa

function getPrgrms($conexion, $id) {
    $stmt = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id = :id");
    $parameters = [':id'=>$id];
    $stmt->execute($parameters);
    $programas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $programas;
}

function addPrgrm($conexion, $disp, $d_ini, $h_ini, $d_fin, $h_fin, $t_ini, $t_fin, $temp, $repeats, $semanal) {
    $stmt = $conexion->prepare("INSERT INTO programa(dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temp_inicio, temp_fin, temperatura, repetir_dias, repetir_sem) VALUES (:disp, :inicio_d, :inicio_h, :fin_d, :fin_h, :temp_ini, :temp_fin, :temp, :dias, :sem)");
    $parameters = [':disp' => $disp, ':inicio_d' => $d_ini, ':inicio_h' => $h_ini, ':fin_d' => $d_fin, ':fin_h' => $h_fin, ':temp_ini' => $t_ini, ':temp_fin' => $t_fin, ':temp' => $temp, ':dias'=>$repeats, ':sem'=>$semanal];
    $stmt->execute($parameters);
}

function deletePswdRec($conexion, $email) {
    $stmt = $conexion->prepare("DELETE FROM pswd_rec WHERE usuario_email = :email");
    $parameters = [':email'=>$email];
    $stmt->execute($parameters);
}


//Carga de API OpenWeather

function cargaTiempo($latitud, $longitud) {
    $clave = "http://api.openweathermap.org/data/2.5/forecast?lat=" . $latitud . "&lon=" . $longitud . "&APPID=d0cb0d8b429769a6e1105782251c99aa&units=metric&lang=es";
    $data = file_get_contents($clave);
    $data_parsed = json_decode($data, true);
    return $data_parsed;
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

function publicarTweet($settings, $texto)
{
    $url = 'https://api.twitter.com/1.1/statuses/update.json';
    $postfields = array('status' => $texto);
    $requestMethod = 'POST';
    $twitter = new TwitterAPIExchange($settings);
    $twitter->setPostfields($postfields)->buildOauth($url, $requestMethod)->performRequest();
}

function obtenerTweets($settings, $usuario, $cantidad)
{
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getField = '?screen_name=' . $usuario . '&count=' . $cantidad;
    $twitter = new TwitterAPIExchange($settings);
    $informacion = json_decode($twitter->setGetfield($getField)->buildOauth($url, $requestMethod)->performRequest(), $assoc = TRUE);
    if (array_key_exists("errors", $informacion)) {
        echo "<h3>Lo sentimos, ha habido un problema</h3><p>Twitter ha devuelto el siguiente mensaje de error: </p><p><em>" . $informacion['errors'][0]['mensaje'] . "</em></p>";
        exit();
    }
    foreach ($informacion as $items) {
        echo "Fecha y hora del Tweet: " . $items['created_at'] . '<br>';
        echo "Tweet: " . $items['text'] . '<br>';
        echo "Usuario: " . $items['user']['name'] . '<br>';
        echo "Nombre: " . $items['user']['screen_name'] . '<br>';
        echo "Followers: " . $items['user']['follower_count'];
    }
}
?>