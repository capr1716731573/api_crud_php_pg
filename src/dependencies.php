<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


//Este error aparece cuando se equivoca en el metodo por ejemplo en vez de ejecutar GET ejecuta POST
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode(array(
                "result"=>null,
                "response"=>false,
                "type"=>"danger",
                "message"=>"Error de WS: El método debería ser: " . implode(', ', $methods)
            )));
    };
};


//Este error aparece cuando quiere consumir una ruta que no existe
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(array(
                "result"=>null,
                "response"=>false,
                "type"=>"danger",
                "message"=>"Error de WS: Página y/o ruta no encontrada"
            )));
    };
};


