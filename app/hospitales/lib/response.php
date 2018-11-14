<?php

namespace App\Hospitales\Lib;

//success = 1
//info = 2
//warning = 3
//danger = 4

class Response{
    public $Estado = 4;
    public $Tipo = "danger";
    public $Mensaje = "Error de WS: Ocurrió un error";

	public $result = null;
	public $response = false;
	public $message = 'Ocurrió un error inesperado';
	public $href = null;
	public $function = null;
	public $tabla=null;

	public $filter = null;

	public function SetResponse($response, $m = ''){
		$this->response=$response;
		$this->message = $m;

		if(!$response && $m ='') $this->response = 'Ocurrió un error inesperado';
	}
}