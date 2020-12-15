<?php
use App\models\AutentificadorJWT;

include_once "./Usuario.php";
include_once "./Materia.php";
include_once "./Profesor.php";
include_once "./Asignacion.php";
include_once "./Archivos.php";

include_once "./AutentificadorJWT.php";

$path = $_SERVER["PATH_INFO"];
$metodo = $_SERVER["REQUEST_METHOD"];

if($metodo == "POST")
{
    switch($path)
    {
        case"/usuario":
            {                
                if(!(Usuario::ValidarUser($_POST["email"],"./Usuarios.json")))
                {
                    $U1 = new Usuario();
                if(isset($_POST["email"]))
                {
                    $U1->email = $_POST["email"];
                }
                if(isset($_POST["Password"]))
                {
                    $U1->password = $_POST["Password"];
                }
                $foto = $_FILES['foto']['name'] ?? ""; 
                if(!empty($foto))
                {
                    $extencion = explode('.',$_FILES["foto"]["name"]);                
                    Archivos::GuardarImagen();
                    $nombre = '--'.time().'--.'.$extencion[1];
                    $U1->foto = "./imagenes/".$nombre;
                    Archivos::AddArrayJson($U1,"./Usuarios.json");
                    Archivos::guardarJson("Usuarios.xxx",$U1);
                    var_dump($U1);
                }else{
                    $U1->foto = "no se cargo foto";
                    Archivos::AddArrayJson($U1,"./Usuarios.json");
                    Archivos::guardarJson("Usuarios.xxx",$U1);
                    var_dump($U1);
                }
                
                }else
                {
                    echo "el usuario ya se encuntra registrado";
                }
                
            }
        break;
        case"/login":
            {
                if((Usuario::ValidarUser($_POST["email"],"./Usuarios.json")))
                {
                    $Usuario = Usuario::GetUser($_POST["email"],"./Usuarios.json");
                    if($Usuario->password == $_POST["Password"])
                    {
                        $stren = AutentificadorJWT::CrearToken($Usuario);
                    }else
                    {
                        var_dump("la clave no coincide con el usuario");
                    }
                }else{
                    var_dump("el usuario no existe");
                }
                
                var_dump($stren);
            }
        break;
        case"/materia":
            {
                $flag = false;                
                $materia = new Materia();
                $headers = getallheaders();
                $token = $headers['token'] ?? "";
                if(AutentificadorJWT::VerificarToken($token))
                {
                    if(isset($_POST["nombre"]))
                    {
                        $materia->nombre = $_POST["nombre"];
                        $flag = true;
                    }
                    if(isset($_POST["cuatrimestre"]))
                    {
                        $materia->cuatrimestre = $_POST["cuatrimestre"];
                        $flag = true;
                    }
                    if($flag == true)
                    {
                        $materia->id = rand(1,99999);
                        Archivos::guardarJson("Materias.xxx",$materia);
                    }else
                    {
                        var_dump("no estan los campos cargados");
                    }
                }
               
            }
        break;
        case"/profesor":
            {

                $ruta =  "Profesores.xxx";
                $flag = false;
        
                $nombre = $_POST['nombre'] ?? "Error";
                $legajo = $_POST['legajo'] ?? "Error";
                $headers = getallheaders();
                $token = $headers['token'] ?? "";
                if(AutentificadorJWT::VerificarToken($token))
                {
                    $list = Archivos::obtenerJson($ruta);
                    if (isset($list)) {
                        //var_dump($list);
                        foreach ($list as $a) {
                            if ($a->legajo == $legajo) {
                                echo "Legajo cargado previamente.";
                                $flag = true;
                            }
                        }
                    }
                    if($flag == false){
                        $profesor = new Profesor();
                        $profesor->nombre = $nombre;
                        $profesor->legajo = $legajo;
                        echo $profesor;
                        Archivos::guardarJson($ruta, $profesor);
                    }
                }        
                
            }
        break;
        case"/asignacion":
            {

                $ruta =  "Profesores.xxx";
                $flag = false;        
                $idMateria = $_POST['id'] ?? null;
                $legajoProfesor = $_POST['legajo'] ?? null;
                $turno = $_POST['turno'] ?? null;   
                $headers = getallheaders();
                $token = $headers['token'] ?? "";     
                $list = Archivos::obtenerJson($ruta);

                if(AutentificadorJWT::VerificarToken($token))
                {
                    if (($legajoProfesor == null || $idMateria == null) && ($turno != "manana" && $turno != "noche")){
                        echo "Error al ingresar el datos";
                        return;
                    }else{
                        if (isset($list)) {
                            //var_dump($list);
                            foreach ($list as $a) {
                                if ($a->legajo == $legajoProfesor) {
                                    //echo "Legajo cargado previamente.";
                                    $flag = true;
                                }
                            }
                            if($flag == true)
                            {
                                $lista = Archivos::obtenerJson("materias-profesores.xxx");
                                if (isset($lista)) {
                                //var_dump($lista);
                                    foreach ($lista as $a) {
                                        if ($a->legajoProfesor == $legajoProfesor) {
                                            if($a->turno != $turno && $a->idMateria== $idMateria || $a->turno == $turno && $a->idMateria!= $idMateria)
                                            {
                                                $asignacion = new Asignacion();
                                                $asignacion->idMateria = $idMateria;
                                                $asignacion->legajoProfesor = $legajoProfesor;
                                                $asignacion->turno = $turno;
                                                Archivos::guardarJson("materias-profesores.xxx", $asignacion);
                                                echo $asignacion;
                                            }else {
                                                var_dump("el profesor ya tiene una asignacion igual");
                                            }
                                        }
                                    }
                                }else{
                                    $asignacion = new Asignacion();
                                    $asignacion->idMateria = $idMateria;
                                    $asignacion->legajoProfesor = $legajoProfesor;
                                    $asignacion->turno = $turno;
                                    Archivos::guardarJson("materias-profesores.xxx", $asignacion);
                                    echo $asignacion;
                                }
                            }
    
                        }
                    } 
                }
                
                
            }
        break;
    }
}else if($metodo == "GET")
{
    switch($path)
    {
        case"/materia":
            { 
                $headers = getallheaders();
                $token = $headers['token'] ?? "";
                if(AutentificadorJWT::VerificarToken($token))
                {
                    $list = Archivos::obtenerJson("Materias.xxx");
                    echo "Materias: <br><br>";
                    foreach($list as $a)
                    {
                        echo "Nombre :".$a->nombre . "<br>" ."cuatrimestre :". $a->cuatrimestre. "<br>";
                        // jump();
                    }
                }             
                           
            }
        break;
        case"/profesor":
            {   
                $headers = getallheaders();
                $token = $headers['token'] ?? "";
                if(AutentificadorJWT::VerificarToken($token))
                {
                    $list = Archivos::obtenerJson("Profesores.xxx");
                    echo "Profesores: <br><br>";
                    foreach($list as $a)
                    {
                        echo "profesor " .$a->nombre . "legajo :" . $a->legajo. "<br>";
                    }
                }             
                   
                
            }
        break;
        case"/asignacion":
            {    
                $headers = getallheaders();
                $token = $headers['token'] ?? "";
                if(AutentificadorJWT::VerificarToken($token))
                {
                    $list = Archivos::obtenerJson("materias-profesores.xxx");
                    echo "Asignaciones: <br><br>";
                    foreach($list as $a)
                    {
                        //var_dump("paso");
                        echo "idMateria :" .$a->idMateria. "legajo :" . $a->legajoProfesor. "<br>". "turno :" . $a->turno. "<br>";
                    } 
                }           
                 
                
            }
        break;
    }
}else 
{
    var_dump("metodo no permitido");
}


// var_dump($_SERVER);
