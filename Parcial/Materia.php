<?php

class Materia
{
    public $id=0;
    public $nombre;
    public $cuatrimestre;
    
    
    public function __toString()
    {
        return "id: " . $this->id . "Nombre: " . $this->nombre . "<br>cuatrimestre: " . $this->cuatrimestre;
    }


    // static function GuardarMateria($nombre , $cuatrimestre)
    // {
    //     $materia = new Materia();
    //     $materia->id =   1;
    //     $materia->nombre = $nombre;
    //     $materia->cuatrimestre = $cuatrimestre;
    //     Archivos::guardarJson("materias.xxx",$materia);
    // }
}