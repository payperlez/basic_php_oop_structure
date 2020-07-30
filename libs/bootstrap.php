<?php

// Load our autoloader
require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

// Specify our Twig templates location
$loader = new \Twig\Loader\FilesystemLoader($_SERVER["DOCUMENT_ROOT"].'/public/views/');

 // Instantiate our Twig
//  $view = new \Twig\Environment($loader, [
//     'cache' => $_SERVER["DOCUMENT_ROOT"].'/public/cache',
// ]);

$view = new \Twig\Environment($loader, [
]);
?>