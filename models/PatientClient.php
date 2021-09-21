<?php

require "models/Patient.php";
require "models/Sensor.php";

class PatientClient {

    private $patient;

    public function __construct() { 
        print("Freq. respiratória, Freq. Cardíada, Pressão Arterial, Temperatura e Oxigenação.\n");
        $this->patient = new Patient(new Sensor(random_int(360, 400)/10,random_int(9, 29) ,random_int(51, 130), random_int(40, 99), random_int(71, 120) ),  random_int(1111, 9999));
    }

    public function run() {
        $service_port = 40000;
        $address = '127.0.0.1';

        $in = '0';
        while(true)
        {   
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            echo "Socket criado!.\n";
            echo "Tentando conectar em '$address' on port '$service_port'...";
            $result = socket_connect($socket, $address, $service_port);
            if ($result === false) {
                echo "Falha na conexão, erro: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
            } else {
                echo "Conectado!.\n";
            }
            $json = 'nada foi enviado.';
            
            $random_number = random_int(0, 2);
            if($random_number == 0){
                $this->patient->generateNormalValuesSensor();
                $json = PatientClient::sendValues($this->patient, $socket);
            }
            else if($random_number = 1){
                $this->patient->generateMediumValuesSensor();
                $json = PatientClient::sendValues($this->patient, $socket);
            }
            else{
                $this->patient->generateLargeValuesSensor();
                $json = PatientClient::sendValues($this->patient, $socket);
            }
            echo "\nValores atuais...\n " . $json;         
            socket_close($socket);    
            sleep(3); 
        }       
    }

    public function sendValues($patient, $socket){
        $json = json_encode($this->patient);
        socket_write($socket, $json);
        echo "\nPaciente " . $this->patient->id . " enviado.\n";
        echo "Lendo resposta:\n";
        $out = socket_read($socket, 2048);
        echo $out;
        return $json;
    }
}

$patient = new PatientClient();
$patient->run();