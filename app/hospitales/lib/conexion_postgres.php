<?php

namespace App\Hospitales\Lib;

use PDO;

class Database{
    
    private $hostname;
    private $puerto;
    private $basedatos;
    private $usuario;
    private $clave;

    public function __CONSTRUCT(){
        $this->hostname = 'localhost';
        $this->puerto = '5432';
        $this->basedatos = 'hospitales_db';
        $this->usuario = 'carpullre';
        $this->clave = '1716731573';
    }

    public static function StartUp(){
        try {
            //$pdo = new PDO('pgsql:host='. $hostname .';port='. $puerto .';dbname='. $basedatos, $usuario , $clave);
            $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=hospitales_db','carpullre','1716731573');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (Exception $e) {
            echo json_encode(array("Estado"=>4,"Mensaje:"=> "Error SQL:". $e->getMessage()));
        }
    }
}