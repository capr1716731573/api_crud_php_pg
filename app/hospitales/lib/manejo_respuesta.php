<?php
namespace App\Hospitales\Lib;

class Manejo_Respuesta_WS{
    public function __construct()
    {
    }

    public static function respuestas($status,$code,$message){
        return array(
            "status" => $status,
            "code" => $code,
            "message"=> $message
        );
    }

    public static function retorno_datos($status,$code,$datos){
        return array(
            "status" => $status,
            "code" => $code,
            "data"=> $datos
        );
    }
}