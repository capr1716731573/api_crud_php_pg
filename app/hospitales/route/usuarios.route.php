<?php

use App\Hospitales\Model\usuario;

$app->group('/hospitales/usuarios/', function () {

    $this->get('getAll', function ($req, $res, $args) {
        $um = new usuario();
        $resultado=$um->getAll();        
        return $res->withHeader("Content-Type", "application/json")
            ->write($resultado);
    });

    $this->get('getByID/{id}', function ($req, $res, $args) {
        $um = new usuario();
        return $res->withHeader("Content-Type", "application/json")
                   ->write($um->getByID($args["id"]));
    });

    $this->post('getByUserPass', function ($req, $res) {
        $um = new usuario();
        $data = json_encode($req->getParsedBody());
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->getByUserPass($data));
    });

    $this->post('insert', function ($req, $res) {
        $um = new usuario();
        $data = json_encode($req->getParsedBody());
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->insert($data));
    });

    $this->post('update', function ($req, $res) {
        $um = new usuario();
        $data = json_encode($req->getParsedBody());
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->update($data));
    });

    $this->get('delete/{id}', function ($req, $res, $args) {
        $um = new usuario();
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->delete($args["id"]));
    });

});