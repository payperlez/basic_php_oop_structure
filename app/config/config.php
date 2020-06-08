<?php
 define('SECRET_KEY', 'enter your secret hash key for password hashing');

 function autolaod($data){
     require_once('app/auth/'.$data.'.php');
 }
?>