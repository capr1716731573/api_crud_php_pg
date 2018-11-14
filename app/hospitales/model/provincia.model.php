<?php

namespace App\Hospitales\Model;

use App\Hospitales\Lib\Database;
use App\Hospitales\Lib\Response;
use App\Hospitales\Lib\Manejo_Respuesta_WS;

use PDO;



class provincia
{
    private $db;
    private $response;
    const TABLA ="Provincias";

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function getAll(){

        $query = "SELECT * FROM provincias ORDER BY nombre_provincia";
       
    try {
        // Preparar sentencia
        $comando = $this->db->prepare($query);
        // Ejecutar sentencia preparada
        $comando->execute();
        $result=$comando->fetchAll(PDO::FETCH_ASSOC);
        $comando->closeCursor();
        if($result){           
            return json_encode(Manejo_Respuesta_WS::retorno_datos('ok',200,$result));
        }else{
            if(sizeof($result)==0){
                return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'No existen datos / '.self::TABLA.' !!!'));
            }else{
                return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error al obtener datos '.self::TABLA));
            }
        }

    
    } catch (ErrorException $ee) {
        return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
    } catch( PDOException $e){
        return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
    }
    }


    public function getByID($id){
        
        $query = "SELECT * FROM provincias ORDER BY nombre_provincia 
                  WHERE provincias_pk=?";
        try {
            // Preparar sentencia
            $comando = $this->db->prepare($query);
            $comando->bindValue(1, $id);
            // Ejecutar sentencia preparada
            $comando->execute();
            $result=$comando->fetch(PDO::FETCH_ASSOC);
    
            if($result){
           
                return json_encode(Manejo_Respuesta_WS::retorno_datos('ok',200,$result));
            }else{
                if(sizeof($result)==0){
                    return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'No existen datos / '.self::TABLA.' !!!'));
                }else{
                    return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error al obtener datos '.self::TABLA));
                }
            }
    
        
        } catch (ErrorException $ee) {
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
        } catch( PDOException $e){
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
        }
    }

    

}// Fin Clase TipoPaciente