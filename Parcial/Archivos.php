<?php

Class Archivos
{
    static function MoverArchivo( $origen, $destino )
    {
        move_uploaded_file($origen,$destino);
    }

    static function LeerArrayJson($path)
    {
        return file_get_contents($path);
    }

    static function AddArrayJson( $DatoaGuardar, $path)
    {
        $dato = Archivos::LeerArrayJson($path);
        $array = json_decode($dato);
        array_push($array,$DatoaGuardar);
        file_put_contents($path,json_encode($array));

    }
    static function guardarJson($name, $obj)
    {
        $array = Archivos::obtenerJson($name);

        if(isset($array)){
            $file = fopen("./".$name, "w");
            array_push($array, $obj);
            fwrite($file, json_encode($array));
            fclose($file);
        }else{
            $array2 = array();
            $file = fopen("./".$name, "w");
            array_push($array2, $obj);
            
            fwrite($file, json_encode($array2));
            fclose($file);
        }
    }
    
    //Traer archivo tipo JSON
    static function obtenerJson($ruta){
        if(file_exists($ruta)){
            $ar = fopen($ruta, "r");
            $lista = json_decode(fgets($ar));
            fclose($ar);
            return $lista;
        }else {
            var_dump("el archivo no existe");
        }
    }

    //METODOS PARA EL GUARDADO DE IMAGENES

    public static function GuardarImagen(){
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name= $_FILES['foto']['name'];
    
        $nombre = explode('.',$name)[0].'--'.time().'--.'.explode('.',$name)[1];
    
        $carpeta = './imagenes/'.$nombre;
        move_uploaded_file($tmp_name, $carpeta );
        //ese move devuelve 1 si quiero saber si se guarod la imagen
    }

    public static function GuardarImagenConNombre($nombre){
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name= $_FILES['foto']['name'];

        //'../Images/'.
        $carpeta = './imagenes/'.$nombre.'.'.explode('.',$name)[1];
        var_dump( $_FILES['foto']);
        move_uploaded_file($tmp_name, $carpeta );
        //ese move devuelve 1 si quiero saber si se guarod la imagen
    }

    static function Serializar($ruta,$objeto)
    {
        $ar=fopen("./".$ruta,"a");
        fwrite($ar,serialize($objeto).PHP_EOL);//serializa un objeto
        fclose($ar);
    }

    static function Deserealizar($ruta)
    {
        $lista = array();
        $ar = fopen("./".$ruta,"r");
        while (!feof($ar)) 
        {
            $objeto = unserialize(fgets($ar));//desserealiza una lista de objetos
            if($objeto != null)
            {
                array_push($lista,$objeto);
            }
            fclose($ar);
            return $lista;
        }
    }

}