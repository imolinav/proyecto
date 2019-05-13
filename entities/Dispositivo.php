<?php
/**
 * Created by PhpStorm.
 * User: ianmo
 * Date: 13/05/2019
 * Time: 10:19
 */

class Dispositivo {
    protected $id;
    protected $nombre;
    protected $habitacion;
    protected $encendido;
    protected $num_encendidos;
    protected $tiempo_encendido;
    protected $temperatura;
    protected $usuario_email;
    protected $pin;
    protected $tipo;

    public function __construct($id="", $nombre="", $habitacion="", $encendido="", $num_encendidos="", $tiempo_encendido="", $temperatura="", $usuario_email="", $pin="", $tipo="") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->habitacion = $habitacion;
        $this->encendido = $encendido;
        $this->num_encendidos = $num_encendidos;
        $this->tiempo_encendido = $tiempo_encendido;
        $this->temperatura = $temperatura;
        $this->usuario_email = $usuario_email;
        $this->pin = $pin;
        $this->tipo = $tipo;
    }

    /* - GETTERS Y SETTERS - */

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): Dispositivo {
        $this->id = $id;
        return $this;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): Dispositivo {
        $this->nombre = $nombre;
        return $this;
    }

    public function getHabitacion(): string {
        return $this->habitacion;
    }

    public function setHabitacion(string $habitacion): Dispositivo {
        $this->habitacion = $habitacion;
        return $this;
    }

    public function getEncendido(): string {
        return $this->encendido;
    }

    public function setEncendido(string $encendido): Dispositivo {
        $this->encendido = $encendido;
        return $this;
    }

    public function getNumEncendidos(): string {
        return $this->num_encendidos;
    }

    public function setNumEncendidos(string $num_encendidos): Dispositivo {
        $this->num_encendidos = $num_encendidos;
        return $this;
    }

    public function getTiempoEncendido(): string {
        return $this->tiempo_encendido;
    }

    public function setTiempoEncendido(string $tiempo_encendido): Dispositivo {
        $this->tiempo_encendido = $tiempo_encendido;
        return $this;
    }

    public function getTemperatura(): string {
        return $this->temperatura;
    }

    public function setTemperatura(string $temperatura): Dispositivo {
        $this->temperatura = $temperatura;
        return $this;
    }

    public function getUsuarioEmail(): string {
        return $this->usuario_email;
    }

    public function setUsuarioEmail(string $usuario_email): Dispositivo {
        $this->usuario_email = $usuario_email;
        return $this;
    }

    public function getPin(): string {
        return $this->pin;
    }

    public function setPin(string $pin): Dispositivo {
        $this->pin = $pin;
        return $this;
    }
}