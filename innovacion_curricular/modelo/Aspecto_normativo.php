<?php

class Aspecto_normativo {

  private string $id;
  private string $tipo;
  private string $descripcion;
  private string $fuente;

  public function __construct($id = '', $tipo = '', $descripcion = '', $fuente = '') {

    $this->id          = $id;
    $this->tipo        = $tipo;
    $this->descripcion = $descripcion;
    $this->fuente      = $fuente;
}

public function getId(): string          { return $this->id; }
public function getTipo(): string        { return $this->tipo; }
public function getDescripcion(): string { return $this->descripcion; }
public function getFuente(): string      { return $this->fuente; }

public function setId(string $id): void                   { $this->id = $id; }
public function setTipo(string $tipo): void               { $this->tipo = $tipo; }
public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }
public function setFuente(string $fuente): void           { $this->fuente = $fuente; }
}
?>