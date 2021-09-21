<?php 

require "models/Patient.php";
require "models/Sensor.php";

class Servidor {

    public $HOST;
    public $PORT;
    private $patients;

    public function __construct(){
        $this->HOST = 'localhost';
        $this->PORT = 40000;
        $this->patients = [];

    }


    public function start(){
        set_time_limit(0);
        ob_implicit_flush();
        $s = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if($s === false)
        {
            echo "Não foi possível criar o socket, erro: " . socket_strerror(socket_last_error()) . "\n";
        }

        if(!socket_bind($s, $this->HOST, $this->PORT))
        {
            echo "Não foi possível realizar o bind, erro: " . socket_strerror(socket_last_error($s)) . "\n";
        }
        if(!socket_listen($s, 20))
            { //dois clientes apenas podem se conectar
                echo "Não foi possível começar a escutar as tentativas de conexões ao servidor, erro: " . socket_strerror(socket_last_error($s)) . "\n";
            }      
        do{      
            echo "\nEsperando conexões...\n";
            $msgsock = socket_accept($s);
            if(false === $msgsock)
            { //$msgsock é a connection.
                echo "Não foi possível receber a conexão, erro: " . socket_strerror(socket_last_error($s)) . "\n";
                break;
            }
            $buf = socket_read($msgsock, 2048);
            if($buf == "pacientes"){//Quando é feita uma requisição dos pacientes. //GET
                $array_patients = json_encode($this->patients);
                socket_write($msgsock, $array_patients, strlen($array_patients));
                echo "GET 200";
            }
            else{ //Quando um paciente é enviado. //POST ou PUT
                $patient = json_decode($buf, true);
                $msg = "Recebido: " . $buf;
                socket_write($msgsock, $msg, strlen($msg));
                if(!$this->verifyPatientRegistry($patient)){//verifica se o paciente existe.
                    array_push($this->patients, $patient);//se não existir, então adiciona na lista, POST
                    echo "POST 200";
                }
                else{
                    $this->updatePatientRegistry($patient);//se existir, vai apenas atualizar suas informações, PUT
                    echo "PUT 200";
                }
                $msgback = "\nO Paciente {$patient["id"]} foi recebido pelo servidor.\n";
                echo $msgback;
                $this->patientsOrder();
                
                socket_write($msgsock, $msgback, strlen($msgback));
            }
            socket_close($msgsock);
        }while(true);

        socket_close($s);
    }

    public function patientsOrder(){
        $patients = $this->patients;
        $ordenedArrayGraves = [];
        $justNormals = [];
        $justGraves = [];
        $i = 0;
        $contPatients = count($patients);
        $minorIndex = 0;
        $minorOxy = 100;

        for($j = 0; $j < $contPatients; $j++)
        {
            foreach($patients as $patient)
            {
                
                if( (int) $patient["sensor"]["bloodOxygenation"] < $minorOxy && $patient["state"] == "Grave")
                {            
                    $minorOxy = (int) $patient["sensor"]["bloodOxygenation"];
                    $minorIndex = $i;
                    
                }
                if($patient["state"] == "Normal" && $j == 0)
                {
                     array_push($justNormals, $patient);
                }
                $i += 1;
            }
            array_push($ordenedArrayGraves, $patients[$minorIndex]);
            echo $minorIndex;
            array_splice($patients, $minorIndex, 1);
            $i = 0;
            $minorOxy = 100;
        }

        foreach($ordenedArrayGraves as $grave)
        {
            if($grave["state"] == "Grave")
            {
                array_push($justGraves, $grave);
            }
        }
        $this->patients = array_merge($justGraves, $justNormals);
    }
    
    public function verifyPatientRegistry($patientVerify){
        $exist = false;
        foreach($this->patients as $patient)
        {
            if($patient["id"] == $patientVerify["id"]){
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    public function updatePatientRegistry($patientUpdate){
        $i = 0;
        foreach($this->patients as $patient)
        {
            if($patient["id"] == $patientUpdate["id"])
            {
                $this->patients[$i]["sensor"]["temperature"] = $patientUpdate["sensor"]["temperature"];
                $this->patients[$i]["sensor"]["respiratoryRate"] = $patientUpdate["sensor"]["respiratoryRate"];
                $this->patients[$i]["sensor"]["heartRate"] = $patientUpdate["sensor"]["heartRate"];
                $this->patients[$i]["sensor"]["bloodOxygenation"] = $patientUpdate["sensor"]["bloodOxygenation"];
                $this->patients[$i]["sensor"]["arterialPressure"] = $patientUpdate["sensor"]["arterialPressure"];
                $this->patients[$i]["state"] = $patientUpdate["state"];
            }
            $i += 1;
        }
    }
}

$server = new Servidor();
$server->start();

?>