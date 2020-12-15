<?php

class Profesor
{

    public $nombre;
    public $legajo;

    public function __toString()
    {
        return "nombre: " . $this->nombre . "<br>Legajo: " . $this->legajo . "<br><br>";
    }

}