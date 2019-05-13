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
        $stmt = $conexion->prepare("UPDATE usuario SET nombre = '$nombre' WHERE email = '$this->email'");
        $stmt->execute();
        $this->setNombre($nombre);
    }

    public function updateFoto($conexion, $foto) {
        $stmt = $conexion->prepare("UPDATE usuario SET foto = '$foto' WHERE email = '$this->email'");
        $stmt->execute();
        $this->setFoto($foto);
    }

    public function getDispositivos($conexion) {
        $stmt_disp = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = '$this->email'");
        $stmt_disp->execute();
        $dispositivos = $stmt_disp->fetchAll(PDO::FETCH_ASSOC);
        return $dispositivos;
    }

    public function getHabitaciones($conexion) {
        $stmt_hab = $conexion->prepare("SELECT habitacion FROM dispositivo WHERE usuario_email = '$this->email' GROUP BY habitacion");
        $stmt_hab->execute();
        $habitaciones = $stmt_hab->fetchAll(PDO::FETCH_ASSOC);
        return $habitaciones;
    }

    public function getCamaras($conexion) {
        $stmt_cam = $conexion->prepare("SELECT * FROM camara WHERE usuario_email = '$this->email'");
        $stmt_cam->execute();
        $camaras = $stmt_cam->fetchAll(PDO::FETCH_ASSOC);
        return $camaras;
    }

    public function getEscenas($conexion) {
        $stmt_esc = $conexion->prepare("SELECT * FROM escena WHERE usuario_email = '$this->email'");
        $stmt_esc->execute();
        $escenas = $stmt_esc->fetchAll(PDO::FETCH_ASSOC);
        return $escenas;
    }

    public function getProgramas($conexion) {
        $stmt_prg = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id IN (SELECT id FROM dispositivo WHERE usuario_email = '$this->email')");
        $stmt_prg->execute();
        $programas = $stmt_prg->fetchAll(PDO::FETCH_ASSOC);
        return $programas;
    }

    public function getLog($conexion, $cant) {
        $stmt_log = $conexion->prepare("SELECT * FROM log WHERE usuario_email = '$this->email' ORDER BY hora DESC LIMIT $cant");
        $stmt_log->execute();
        $logs = $stmt_log->fetchAll(PDO::FETCH_ASSOC);
        return $logs;
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