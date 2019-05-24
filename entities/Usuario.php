<?php
/**
 * Created by PhpStorm.
 * User: Ian
 * Date: 21/03/2019
 * Time: 19:15
 */

class Usuario {
    protected $email;
    protected $nombre;
    protected $foto;
    protected $pass;
    protected $activo;
    protected $puerto;
    protected $admin;
    protected $rb_ip;
    protected $cp;

    public function __construct($email="", $nombre="", $foto="", $pass="", $activo="", $puerto="", $admin="", $rb_ip="", $cp="") {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->pass = $pass;
        $this->activo = $activo;
        $this->puerto = $puerto;
        $this->admin = $admin;
        $this->rb_ip = $rb_ip;
        $this->cp = $cp;
    }

    public function updatePassFT($conexion, $pass) {
        $stmt = $conexion->prepare("UPDATE usuario SET pass = :pass, activo = 1 WHERE email = :email");
        $parameters = [':pass'=>password_hash($pass, PASSWORD_DEFAULT, ['cost'=>10]), ':email'=>$this->email];
        $stmt->execute($parameters);
    }

    public function updateNombre($conexion, $nombre) {
        $stmt = $conexion->prepare("UPDATE usuario SET nombre = :nombre WHERE email = :email");
        $parameters = [':nombre'=>$nombre, ':email'=>$this->email];
        $stmt->execute($parameters);
        $this->setNombre($nombre);
    }

    public function updateEmail($conexion, $new_email) {
        $stmt = $conexion->prepare("UPDATE usuario SET email = :new_email WHERE email = :email");
        $parameters = [':new_email'=>$new_email, ':email'=>$this->email];
        $stmt->execute($parameters);
        $this->setEmail($new_email);
    }

    public function updateFoto($conexion, $foto) {
        $stmt = $conexion->prepare("UPDATE usuario SET foto = :foto WHERE email = :email");
        $parameters = [':foto'=>$foto, ':email'=>$this->email];
        $stmt->execute($parameters);
        $this->setFoto($foto);
    }

    public function getDispositivos($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = :email");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dispositivos;
    }

    public function getHabitaciones($conexion) {
        $stmt = $conexion->prepare("SELECT habitacion FROM dispositivo WHERE usuario_email = :email GROUP BY habitacion");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $habitaciones;
    }

    public function getCamaras($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM camara WHERE usuario_email = :email");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $camaras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $camaras;
    }

    public function getEscenas($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM escena WHERE usuario_email = :email");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $escenas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $escenas;
    }

    public function getProgramas($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id IN (SELECT id FROM dispositivo WHERE usuario_email = :email)");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $programas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $programas;
    }

    public function deleteProgramas($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id IN (SELECT id FROM dispositivo WHERE usuario_email = :email) AND ((dia_inicio < CURRENT_DATE AND dia_fin IS NULL) OR (dia_fin<CURRENT_DATE))");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
    }

    public function getLogs($conexion, $cant) {
        $stmt = $conexion->prepare("SELECT * FROM log WHERE usuario_email = :email ORDER BY fecha DESC, hora DESC LIMIT $cant");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $logs;
    }

    public function addLog($conexion, $dia, $info, $hora, $habitacion) {
        $stmt = $conexion->prepare("INSERT INTO log (fecha, info, usuario_email, hora, habitacion) VALUES (:dia, :info, :usuario_email, :hora, :habitacion)");
        $parameters = [':dia' => $dia, ':info' => $info, ':usuario_email' => $this->email, ':hora' => $hora, ':habitacion'=>$habitacion];
        $stmt->execute($parameters);
    }

    public function getHistorial($conexion) {
        $stmt = $conexion->prepare("SELECT * FROM disp_mensual WHERE usuario_email = :email");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $historial;
    }

    public function getHistMeses($conexion) {
        $stmt = $conexion->prepare("SELECT mes, anyo FROM disp_mensual WHERE usuario_email = :email GROUP BY mes");
        $parameters = [':email'=>$this->email];
        $stmt->execute($parameters);
        $meses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $meses;
    }


    /* -- GETTERS & SETTERS -- */


    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): Usuario {
        $this->email = $email;
        return $this;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): Usuario {
        $this->nombre = $nombre;
        return $this;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function setFoto(string $foto): Usuario {
        $this->foto = $foto;
        return $this;
    }

    public function getPass(): string {
        return $this->pass;
    }

    public function setPass(string $pass): Usuario {
        $this->pass = $pass;
        return $this;
    }

    public function getActivo(): string {
        return $this->activo;
    }

    public function setActivo(string $activo): Usuario {
        $this->activo = $activo;
        return $this;
    }

    public function getPuerto(): string {
        return $this->puerto;
    }

    public function setPuerto(string $puerto): Usuario {
        $this->puerto = $puerto;
        return $this;
    }

    public function getAdmin(): string {
        return $this->admin;
    }

    public function setAdmin(string $admin): Usuario {
        $this->admin = $admin;
        return $this;
    }

    public function getRbIp(): string {
        return $this->rb_ip;
    }

    public function setRbIp(string $rb_ip): Usuario
    {
        $this->rb_ip = $rb_ip;
        return $this;
    }

    public function getCp(): string {
        return $this->cp;
    }

    public function setCp(string $cp): Usuario {
        $this->cp = $cp;
        return $this;
    }
}