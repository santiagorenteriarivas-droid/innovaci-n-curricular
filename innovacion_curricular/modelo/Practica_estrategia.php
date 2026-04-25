<?php

class Practica_estrategia {

  private string $id;
  private string $tipo;
  private string $nombre;
  private string $descripcion;

  public function __construct($id = '', $tipo = '', $nombre = '', $descripcion = '') {

    $this->id          = $id;
    $this->tipo        = $tipo;
    $this->nombre      = $nombre;
    $this->descripcion = $descripcion;
}

public function getId(): string          { return $this->id; }
public function getTipo(): string        { return $this->tipo; }
public function getNombre(): string      { return $this->nombre; }
public function getDescripcion(): string { return $this->descripcion; }

public function setId(string $id): void                   { $this->id = $id; }
public function setTipo(string $tipo): void               { $this->tipo = $tipo; }
public function setNombre(string $nombre): void           { $this->nombre = $nombre; }
public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
}
?>