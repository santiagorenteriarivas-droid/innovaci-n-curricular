<?php

class Enfoque {

  private string $id;
  private string $nombre;
  private string $descripcion;

  public function __construct($id = '', $nombre = '', $descripcion = '') {

    $this->id          = $id;
    $this->nombre      = $nombre;
    $this->descripcion = $descripcion;
}

public function getId(): string          { return $this->id; }
public function getNombre(): string      { return $this->nombre; }
public function getDescripcion(): string { return $this->descripcion; }

public function setId(string $id): void                   { $this->id = $id; }
public function setNombre(string $nombre): void           { $this->nombre = $nombre; }
public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
}
?>