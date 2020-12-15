<?php
class Usuario
{
    public $email;
    //public $T_usuario;
    public $password;
    public $foto;

    // function __construct($email,$password,$foto){
    //     $this->email = $email;
    //     $this->password = $password;
    //     $this->foto = $foto;
    // }
    // public function __get($name)
    // {
    //     return $this->$name; 
    // }

    // public function __set($name, $value)
    // {
    //     $this->$name = $value;
    // }
    
    public function __toString()
    {
        return "Email: " . $this->email . "<br>Tipo: " . $this->password . "<br>Foto: " . $this->foto . "<br><br>";
    }

    static function ValidarUser($user,$archivo)
    {
        $flag = false;
        $dato = Archivos::LeerArrayJson($archivo);
        $array = json_decode($dato);
        for ($i=0; $i < count($array); $i++) { 
            //var_dump($array[$i]);
            if($array[$i]->email == $user)
            {
                //var_dump($array[$i]);
                $flag = true;
            }
        }
        return $flag;

    }

    static function GetUser($user,$archivo)
    {
        $usuario = new Usuario();
        $dato = Archivos::LeerArrayJson($archivo);
        $array = json_decode($dato);
        for ($i=0; $i < count($array); $i++) { 
            //var_dump($array[$i]);
            if($array[$i]->email == $user)
            {
                $usuario->email = $array[$i]->email;
                $usuario->password = $array[$i]->password;
                $usuario->foto = $array[$i]->foto;

            }
        }
        return $usuario;

    }

}