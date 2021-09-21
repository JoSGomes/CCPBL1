<?php 
    require_once "Router.php";
    require_once "controllers/HomeController.php";
    require_once "models/Patient.php";
    require_once "models/Sensor.php";

    $router = new Router();
    $router->start($_SERVER['REQUEST_URI']);