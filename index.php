<?php
require_once __DIR__.'/public/inc/Loader.php';
require_once __DIR__.'/libs/bootstrap.php';
// Render our view
$message = 'hello world!';
echo $view->render('home/index.twig', ['message' => $message] );