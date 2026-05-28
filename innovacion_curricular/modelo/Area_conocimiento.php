<?php

class Area_conocimiento {

  private string $id;
  private string $gran_area;
  private string $area;
  private string $disciplina;

  public function __construct($id = '', $gran_area = '', $area = '', $disciplina = '') {

    $this->id         = $id;
    $this->gran_area  = $gran_area;
    $this->area       = $area;
    $this->disciplina = $disciplina;
}

public function getId(): string         { return $this->id; }
public function getGran_area(): string  { return $this->gran_area; }
public function getArea(): string       { return $this->area; }
public function getDisciplina(): string { return $this->disciplina; }

public function setId(string $id): void                 { $this->id = $id; }
public function setGran_area(string $gran_area): void   { $this->gran_area = $gran_area; }
public function setArea(string $area): void             { $this->area = $area; }
public function setDisciplina(string $disciplina): void { $this->disciplina = $disciplina; }
}
?>