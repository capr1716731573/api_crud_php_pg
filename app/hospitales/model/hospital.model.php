<?php

namespace App\Hospitales\Model;

use App\Hospitales\Lib\Database;
use App\Hospitales\Lib\Response;
use App\Hospitales\Lib\Manejo_Respuesta_WS;

use PDO;



class hospital
{
    private $db;
    private $response;
    const TABLA ="Hospitales";

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function getAll(){

        $query = "SELECT 
                h.pk_hospital, 
                h.nombre_hospital, 
                p.pk_provincia,
                p.nombre_provincia, 
                h.ciudad_hospital, 
                h.foto_hospital
        FROM hospital h INNER JOIN provincias p on h.pk_provincia = p.pk_provincia ORDER BY h.nombre_hospital";
       
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
        
        $query = "SELECT 
                h.pk_hospital, 
                h.nombre_hospital, 
                p.pk_provincia,
                p.nombre_provincia, 
                h.ciudad_hospital, 
                h.foto_hospital
          FROM hospital h INNER JOIN provincias p on h.pk_provincia = p.pk_provincia 
          WHERE h.pk_hospital=?";
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

    

    public function insert($data){
        $data=json_decode($data, true);
        $query="insert into hospital (
                        nombre_hospital,
                        pk_provincia,
                        ciudad_hospital,
                        foto_hospital)
            values (?,?,?,?) RETURNING json_build_object (
                'status','ok','code',200,'data',json_build_object(
                    'pk_hospital',pk_hospital,
                    'nombre_hospital',nombre_hospital,
                    'pk_provincia',pk_provincia,
                    'ciudad_hospital',ciudad_hospital,
                    'foto_hospital',foto_hospital)
                );";
        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $data['nombre_hospital']);
            $sentencia->bindValue(2, $data['pk_provincia']);
            $sentencia->bindValue(3, $data['ciudad_hospital']);
            $sentencia->bindValue(4, $data['foto_hospital']);
           
            
            $sentencia->execute();
            $rows = $sentencia->fetch(PDO::FETCH_ASSOC);
            if($rows){
                $this->db->commit();
                $sentencia->closeCursor();
                return $rows['json_build_object'];
            }
            
            
        }catch (Exception $e) {
            $this->db->rollBack();
            return Manejo_Respuesta_WS::respuestas('error',400,self::TABLA.' - '.$e->getMessage());        
        } catch (ErrorException $ee) {
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
        } catch( PDOException $e){
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
        }
    
    }

    public function update($data){
        $data=json_decode($data, true);
        $query="UPDATE hospital SET  
                    nombre_hospital=?,
                    pk_provincia=?,
                    ciudad_hospital=?,
                    foto_hospital=?
            WHERE pk_hospital=? RETURNING json_build_object (
                'status','ok','code',200,'data',json_build_object(
                    'pk_hospital',pk_hospital,
                    'nombre_hospital',nombre_hospital,
                    'pk_provincia',pk_provincia,
                    'ciudad_hospital',ciudad_hospital,
                    'foto_hospital',foto_hospital)
                );";
        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $data['nombre_hospital']);
            $sentencia->bindValue(2, $data['pk_provincia']);
            $sentencia->bindValue(3, $data['ciudad_hospital']);
            $sentencia->bindValue(4, $data['foto_hospital']);
            $sentencia->bindValue(5, $data['pk_hospital']);
            
            $sentencia->execute();
            $rows = $sentencia->fetch(PDO::FETCH_ASSOC);
            if($rows){
                $this->db->commit();
                $sentencia->closeCursor();
                return $rows['json_build_object'];
            }
            
            
        }catch (Exception $e) {
            $this->db->rollBack();
            return Manejo_Respuesta_WS::respuestas('error',400,self::TABLA.' - '.$e->getMessage());        
        } catch (ErrorException $ee) {
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
        } catch( PDOException $e){
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
        }
    
    }//json_build_object

    public function updateImage($name,$id){
       
        $query="UPDATE hospital SET  
                       foto_hospital=?
            WHERE pk_hospital=? RETURNING json_build_object (
                'status','ok','code',200,'data',json_build_object(
                    'pk_hospital',pk_hospital,
                    'nombre_hospital',nombre_hospital,
                    'pk_provincia',pk_provincia,
                    'ciudad_hospital',ciudad_hospital,
                    'foto_hospital',foto_hospital)
                );";
        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $name);
            $sentencia->bindValue(2, $id);
            
            $sentencia->execute();
            $rows = $sentencia->fetch(PDO::FETCH_ASSOC);
            if($rows){
                $this->db->commit();
                $sentencia->closeCursor();
                return $rows['json_build_object'];
            }
            
            
        }catch (Exception $e) {
            $this->db->rollBack();
            return Manejo_Respuesta_WS::respuestas('error',400,self::TABLA.' - '.$e->getMessage());        
        } catch (ErrorException $ee) {
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
        } catch( PDOException $e){
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
        }
    
    }//jso

    public function delete($id){
    
        $query="DELETE FROM hospital WHERE pk_hospital= ?";

        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $id);
            $sentencia->execute();
            $rows = $sentencia->fetch(PDO::FETCH_ASSOC);
            $this->db->commit();
            $sentencia->closeCursor();
            return json_encode(Manejo_Respuesta_WS::retorno_datos('ok',200,'Registro eliminado - '.self::TABLA));
                   
            
        }catch (Exception $e) {
            $this->db->rollBack();
            return Manejo_Respuesta_WS::respuestas('error',400,self::TABLA.' - '.$e->getMessage());        
        } catch (ErrorException $ee) {
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK - '.self::TABLA));
        } catch( PDOException $e){
            $this->db->rollBack();
            return json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL - '.self::TABLA));
        }
    
    }

}// Fin Clase TipoPaciente