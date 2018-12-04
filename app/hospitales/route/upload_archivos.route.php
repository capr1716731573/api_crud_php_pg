<?php

use App\Hospitales\Lib\Manejo_Respuesta_WS;
use App\Hospitales\Lib\PiramideUploader;

use App\Hospitales\Model\hospital;

$app->group('/hospitales/upload/', function () {


    //tabla hospitales
    $this->post('subir-archivo/{tabla}/{id}', function ($req, $res, $args){
        try {
            
            if(isset($args["tabla"]) && isset($args["id"])){
                //Aqui realizo operaciones si la tabla es hospital
                if($args["tabla"] == "hospital"){
                    $hospital = new hospital();
                    $resultado=$hospital->getByID($args["id"]);
                    $resultado=json_decode($resultado,true);
                   
                    //verifico si existe registro
                   if($resultado["code"] != 200){
                        $result=Manejo_Respuesta_WS::respuestas('error',400,'Error, verifique que el hospital a actualizar exista !!');
                        exit (json_encode($result));
                        
                    }
                }
            }
            else{
                $result=Manejo_Respuesta_WS::respuestas('error',400,'Error, verifique que envie el valor del parametro tabla y id !!');
                exit (json_encode($result));
            }
            

            $result=Manejo_Respuesta_WS::respuestas('error',400,'El archivo no ha podido subirse!!!');
            //verifico si se envio el archivo             
            if(isset($_FILES['archivos_enviados'])){
                
                $libreria_piramide=new PiramideUploader();
                //metodo para subir file
                
                $upload=$libreria_piramide->upload($args["id"].'-img','archivos_enviados','../app/hospitales/archivos_cargados/'.$args["tabla"],array('image/jpeg','image/png','image/gif'));
                $file=$libreria_piramide->getInfoFile();
                
                $file_name=$file["complete_name"];
                if(isset($upload) && $upload["uploaded"]==false){
                    $result=Manejo_Respuesta_WS::respuestas('error',400,'Error al subir el archivo, verifique formato permitido, o si adjunto correctamente el mismo!!!');
                }else{
                    if($args["tabla"] == "hospital"){
                        $resultado=json_decode($hospital->updateImage($file_name,$args["id"]),true);

                        //verifico si existe registro
                        if($resultado["code"] != '200'){
                            $result=Manejo_Respuesta_WS::respuestas('error',400,'Error, La imagen se ha subido pero no se ha actualizado el registro !!');
                            exit (json_encode($result));                    
                        }

                        $result =array(
                            "status" => "success",
                            "code" => 200,
                            "message"=> "El archivo ha sido subido con exito",
                            "filename"=>$file_name
                        );
                    }

                    
                }
                
                
            }
                echo json_encode($result);
        
            
            } catch (ErrorException $ee) {
                echo json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error en el Web Service con SLIM FRAMEWORK'));
            } catch( PDOException $e){
                echo json_encode(Manejo_Respuesta_WS::respuestas('error',400,'Error con el motor de base de datos postgresSQL'));
            }
    }); 

});