<?php

class Car_innovacion {

  private string $id;
  private string $nombre;
  private string $descripcion;
  private string $tipo;

  public function __construct($id = '', $descripcion = '', $nombre = '', $tipo = '') {

    $this->id          = $id;
    $this->nombre      = $nombre;
    $this->descripcion = $descripcion;
    $this->tipo        = $tipo;
}

public function getId(): string          { return $this->id; }
public function getNombre(): string      { return $this->nombre; }
public function getDescripcion(): string { return $this->descripcion; }
public function getTipo(): string        { return $this->tipo; }

public function setId(string $id): void                   { $this->id = $id; }
public function setNombre(string $nombre): void           { $this->nombre = $nombre; }
public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
public function setTipo(string $tipo): void               { $this->tipo = $tipo; }
}
?>