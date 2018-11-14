<?php

use App\Hospitales\Model\provincia;

$app->group('/hospitales/provincia/', function () {

    $this->get('getAll', function ($req, $res, $args) {
        $um = new provincia();
        $resultado=$um->getAll();        
        return $res->withHeader("Content-Type", "application/json")
            ->write($resultado);
    });

    $this->get('getByID/{id}', function ($req, $res, $args) {
        $um = new provincia();
        return $res->withHeader("Content-Type", "application/json")
                   ->write($um->getByID($args["id"]));
    });


});