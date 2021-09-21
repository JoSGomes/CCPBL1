<?php 
 require_once "../controllers/HomeController.php";
 require_once "../models/Patient.php";
 require_once "../models/Sensor.php";

session_start();

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>Monitor de pacientes</title>
</head>

<body>
  <center>
    <h1>MONITOR DE PACIENTES</h1>
  </center>
  <!-- <center>
    <div class="topping">
      
        <p>
        PACIENTES
        </p>
        <p>
        MEDIÇÕES
        </p>
        <p>
        PRORIZAR
        </p>
      
    </div>
  </center> -->
    <center>
      <div class="container middle">
      <table class="table borderless-table">
          <thead>
            <tr>
              <th colspan="1" scope="colgroup">PACIENTES</th>
              <th class="medicoes" colspan="5" scope="colgroup ">MEDIÇÕES</th>
              <th colspan="1" scope="colgroup">SITUAÇÃO</th>
              <!-- <th colspan="1" scope="colgroup">PRORIZAR</th> -->
            </tr>
          </thead>
          <tbody id="table">
          </tbody>
      </table><!-- end table -->
      </div> <!-- end container -->
      
    </center>
    
    <script>
      function insertTbody(json){
        var temp = "";
        for(let i=0; i < json.length; i++){
          temp += `<tr> <td>Paciente #${json[i].id}</td> <td>${json[i].sensor.respiratoryRate}m/m</td> <td>${json[i].sensor.heartRate}b/m</td> <td>${json[i].sensor.bloodOxygenation}%</td> <td>${json[i].sensor.arterialPressure}mmHg </td> <td>${json[i].sensor.temperature}°</td> <td>${json[i].state}</td></tr>`
        }
        $("#table").html(temp)
      }
      setInterval(function(){ 
        var xhttp = new XMLHttpRequest();
        xhttp.open('GET', 'http://localhost:8000/Home/getPatients', false)
        xhttp.send()
        var response = xhttp.responseText
        var json = JSON.parse(response)
        insertTbody(json)
      }, 2000);
  </script> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </body>
</html>