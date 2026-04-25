<?php

class Universidad {

  private string $id;
  private string $nombre;
  private string $tipo;
  private string $ciudad;

  public function __construct($id = '', $nombre = '', $tipo = '', $ciudad = '') {

    $this->id     = $id;
    $this->nombre = $nombre;
    $this->tipo   = $tipo;
    $this->ciudad = $ciudad;
}

public function getId(): string     { return $this->id; }
public function getNombre(): string { return $this->nombre; }
public function getTipo(): string   { return $this->tipo; }
public function getciudad(): string { return $this->ciudad; }

public function setId(string $id): void         { $this->id = $id; }
public function setNombre(string $nombre): void { $this->nombre = $nombre; }
public function setTipo(string $tipo): void     { $this->tipo = $tipo; }
public function setciudad(string $ciudad): void { $this->ciudad = $ciudad; }
}
?>