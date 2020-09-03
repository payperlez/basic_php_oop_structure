<?php
    if (Session::issert('user')) {
       
    } else {
       echo "<script>window.location.replace('index')</script>";
    }