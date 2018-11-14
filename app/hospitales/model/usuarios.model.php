<?php

namespace App\Hospitales\Model;

use App\Hospitales\Lib\Database;
use App\Hospitales\Lib\Response;
use App\Hospitales\Lib\Manejo_Respuesta_WS;

use PDO;



class usuario
{
    private $db;
    private $response;
    const TABLA ="Usuarios";

    public function __CONSTRUCT()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function getAll(){

        $query = "SELECT
                u.pk_user,
                u.cedula_user,
                u.nombres_user,
                u.apellidos_user,
                u.fecnac_user,
                p.nombre_provincia,
                u.ciudad_user,
                u.usuario_user,
                u.contrasena_user
        
        FROM usuarios u INNER JOIN provincias p on u.fk_provincia = p.pk_provincia
        ORDER BY u.apellidos_user ASC, u.nombres_user";
       
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
                    u.pk_user,
                    u.cedula_user,
                    u.nombres_user,
                    u.apellidos_user,
                    u.fecnac_user,
                    p.nombre_provincia,
                    u.ciudad_user,
                    u.usuario_user,
                    u.contrasena_user

            FROM usuarios u INNER JOIN provincias p on u.fk_provincia = p.pk_provincia
            WHERE u.pk_user=?";
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
        $query="insert into usuarios (
                    cedula_user,
                    nombres_user,
                    apellidos_user,
                    fecnac_user,
                    fk_provincia,
                    ciudad_user,
                    usuario_user,
                    contrasena_user)
            values (?,?,?,?,?,?,?,?) RETURNING json_build_object (
                'status','ok','code',200,'data',json_build_object(
                    'pk_user',pk_user,
                    'cedula_user',cedula_user,
                    'nombres_user',nombres_user,
                    'apellidos_user',apellidos_user,
                    'fecnac_user',fecnac_user,
                    'fk_provincia',fk_provincia,
                    'ciudad_user',ciudad_user,
                    'usuario_user',usuario_user,
                    'contrasena_user',contrasena_user)
                );";
        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $data['cedula_user']);
            $sentencia->bindValue(2, $data['nombres_user']);
            $sentencia->bindValue(3, $data['apellidos_user']);
            $sentencia->bindValue(4, $data['fecnac_user']);
            $sentencia->bindValue(5, $data['fk_provincia']);
            $sentencia->bindValue(6, $data['ciudad_user']);
            $sentencia->bindValue(7, $data['usuario_user']);
            $sentencia->bindValue(8, $data['contrasena_user']);
            
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
        $query="UPDATE usuarios SET  
                    cedula_user=?,
                    nombres_user=?,
                    apellidos_user=?,
                    fecnac_user=?,
                    fk_provincia=?,
                    ciudad_user=?,
                    usuario_user=?,
                    contrasena_user=?
            WHERE pk_user=? RETURNING json_build_object (
            'status','ok','code',200,'data',json_build_object(
                'pk_user',pk_user,
                'cedula_user',cedula_user,
                'nombres_user',nombres_user,
                'apellidos_user',apellidos_user,
                'fecnac_user',fecnac_user,
                'fk_provincia',fk_provincia,
                'ciudad_user',ciudad_user,
                'usuario_user',usuario_user,
                'contrasena_user',contrasena_user)
            );";
        try {
            $sentencia = $this->db->prepare($query);
            $this->db->beginTransaction();
            $sentencia->bindValue(1, $data['cedula_user']);
            $sentencia->bindValue(2, $data['nombres_user']);
            $sentencia->bindValue(3, $data['apellidos_user']);
            $sentencia->bindValue(4, $data['fecnac_user']);
            $sentencia->bindValue(5, $data['fk_provincia']);
            $sentencia->bindValue(6, $data['ciudad_user']);
            $sentencia->bindValue(7, $data['usuario_user']);
            $sentencia->bindValue(8, $data['contrasena_user']);
            $sentencia->bindValue(9, $data['pk_user']);
            
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

    public function delete($id){
    
        $query="DELETE FROM usuarios WHERE pk_user= ?";

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