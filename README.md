# API WEB SERVICE - HGSD	

El siguiente proyecto es una API, que forma parte de una Arquitectura de Software SOA, la cual va a manejar de la siguiente manera:

* Front End - Angular 6 - Angular JS
* Back End- PHP 7 --> Slim 3 (Modulo Admisiones y Laboratorio)
* DataBase - Postgres SQL 10

## Instrucciones de Intalacion

Para crear un proyecto con Slim Framework en PHP, se debe poner el siguiente comando

    php composer.phar create-project slim/slim-skeleton [my-app-name] --> en LINUX
	composer create-project slim/slim-skeleton [my-app-name] --> en WINDOWS

Este comando debe ejecutar primero instalando composer y dirigiendose a la carpeta del WEB SERVER en este caso htdocs.

Una ves instalado debe crear una carpeta llamado app/ y seguido la carpeta del modulo que realizaremos,en este caso es el de TTHH, o laboratorio ejm /app/sglab, afuera de esa carpeta debe estar un archivo llamado app_loader.php, este archivo es aquel que permite cargar todas la librerias de php dentro de estas carpeta ejm sglab, este archivo tiene el siguiente script:

	<?php
	$base = __DIR__ . '/../slab/';

	$folders = [
		'lib',---> CONEXION A LA BASE DE DATOS
		'model', --> FUNCIONES QUE SE CONECTAN A LA BASE
		'route' --> RUTAS GET POST QUE LLAMAN A FUNCIONES DE LA CARPETA MODEL
	];

	foreach($folders as $f)
	{
		// echo $f . "<br>";
		// print_r(glob($base . "$f/*.php" . "<br>"));
		foreach (glob($base . "$f/*.php") as $filename)
		{
			require $filename;
	//         echo $filename . "<br>";
		}
	}

Y asi mismo este archivo debe estar instanciado en el archivo principal /public/index.php
	//Register Laboratorio
	require __DIR__ . '/../app/sglab/app_loader.php';

## Configurar CORS 
1) Actualizar el archivo composer.json, aumentando esta linea en require 
	"require": {
    "palanik/corsslim": "*"
  }

  y si no funciona colocar 
  "palanik/corsslim": "dev-slim3"

2) Ir mediante el CMD a la carpeta del API del proyecto y ejecutar el comando composer update
3) Agregar en el index.html la siguiente lineas

	$corsOptions = array(
		"origin" => "*",
		"exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
		"allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
	);
	$cors = new \CorsSlim\CorsSlim($corsOptions);
 
	$app->add($cors);


