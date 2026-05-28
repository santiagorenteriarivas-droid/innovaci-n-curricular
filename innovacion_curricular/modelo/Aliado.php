<?php

class Aliado {

  private string $nit;
  private string $razon_social;
  private string $nombre_contacto;
  private string $correo;
  private string $telefono;
  private string $ciudad;

  public function __construct($nit = '', $razon_social = '', $nombre_contacto = '', $correo = '', $telefono = '', $ciudad = '') {

    $this->nit             = $nit;
    $this->razon_social    = $razon_social;
    $this->nombre_contacto = $nombre_contacto;
    $this->correo          = $correo;
    $this->telefono        = $telefono;
    $this->ciudad          = $ciudad;
}

public function getNit(): string             { return $this->nit; }
public function getRazon_Social(): string    { return $this->razon_social; }
public function getNombre_Contacto(): string { return $this->nombre_contacto; }
public function getCorreo(): string          { return $this->correo; }
public function getTelefono(): string        { return $this->telefono; }
public function getCiudad(): string          { return $this->ciudad; }

 
public function setNit(string $nit): void                         { $this->nit = $nit; }
public function setRazon_Social(string $razon_social): void       { $this->razon_social = $razon_social; }
public function setNombre_Contacto(string $nombre_contacto): void { $this->nombre_contacto = $nombre_contacto; }
public function setCorreo(string $correo): void                   { $this->correo = $correo; }
public function setTelefono(string $telefono): void               { $this->telefono = $telefono; }
public function setCiudad(string $ciudad): void                   { $this->ciudad = $ciudad; }
}
?>