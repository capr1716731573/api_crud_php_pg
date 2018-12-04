<?php

use App\Hospitales\Model\hospital;

$app->group('/hospitales/hospital/', function () {

    $this->get('getAll', function ($req, $res, $args) {
        $um = new hospital();
        $resultado=$um->getAll();        
        return $res->withHeader("Content-Type", "application/json")
            ->write($resultado);
    });

    $this->get('getByID/{id}', function ($req, $res, $args) {
        $um = new hospital();
        return $res->withHeader("Content-Type", "application/json")
                   ->write($um->getByID($args["id"]));
    });

    $this->post('insert', function ($req, $res) {
        $um = new hospital();
        $data = json_encode($req->getParsedBody());
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->insert($data));
    });

    $this->post('update', function ($req, $res) {
        $um = new hospital();
        $data = json_encode($req->getParsedBody());
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->update($data));
    });

    $this->get('delete/{id}', function ($req, $res, $args) {
        $um = new hospital();
        return $res->withHeader("Content-Type", "application/json")
            ->write($um->delete($args["id"]));
    });

});