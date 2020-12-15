<?php

class Asignacion
{
    public $idMateria;
    public $legajoProfesor;
    public $turno;

    public function __toString()
    {
        return "legajoProfesor: " . $this->legajoProfesor . "<br>id: " . $this->idMateria . "<br>turno: " . $this->turno . "<br><br>";
    }
}