<?php
$base = __DIR__ . '/../hospitales/';

$folders = [
    'lib',
    'model',
    'route'
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