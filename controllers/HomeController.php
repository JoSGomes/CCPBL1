<?php


class HomeController{

    public static $patients = [];

    public function index(){       
        header("location: /views/home.php");      
    }

    public function update($json_patients){
        $all_patients = [];
        foreach($json_patients as $json_patient){
            array_push($all_patients, json_decode($json_patient, true));
        }  
       echo json_encode($all_patients);
    }

    public function getPatients(){ 
        $address = '127.0.0.1';
        $service_port = 40000;
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $result = socket_connect($socket, $address, $service_port);
        socket_write($socket, "pacientes");
        $jsonPatients = socket_read($socket, 2048);
        $jsonPatients = json_decode($jsonPatients);
        $newJsonPatients = [];
        foreach($jsonPatients as $json_patient){
            array_push($newJsonPatients, json_encode($json_patient));
        }        
        socket_close($socket);
        HomeController::update($newJsonPatients);
    }
}

?>