<?php
  include "redirect.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createstationbtn'])){
      include "db_info.php";

      $stationname = $_POST['station_name'];
      $stopID = $_POST['stop_id'];
      $entryfare = $_POST['entry_fare'];
      $nearestintersection = $_POST['Nearest_Intersection'];

      if ($_POST['Station'] === 'bus')
      {
          $stationtype = 0;
          $station1 = 'bus';
      }else if($_POST['Station'] === 'train'){
          $stationtype = 1;
          $station1 = 'train';
      }
      if ($_POST['open_station'] === 'open')
      {
          $openstation = 0;
      }else{
          $openstation = 1;
      }

      if(validateInputs($stationname, $stopID, $entryfare, $nearestintersection, $stationtype, $openstation, $station1) === TRUE){
        if(InsertStation($stopID, $stationname, $entryfare, $openstation, $stationtype) === TRUE){
          if($station1 === 'bus'&& $nearestintersection !== ""){
            InsertBus($stopID, $nearestintersection);
          }
          Redirect("Station_Management.php");
        }
        // else{
        //   echo "<script type='text/javascript'>alert('Failed to Create Station, the Station information already existed.');</script>";
        // }
      }
    }

    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['back'])){
      Redirect("Station_Management.php");
    }


    function validateInputs($stationname, $stopID, $entryfare, $nearestintersection, $stationtype, $openstation, $station1) {
        if ($stationname === "" || $stopID === ""){
          echo "<script type='text/javascript'>alert('Please enter a valid Station Name or Stop ID');</script>";
          return FALSE;
        }
        else if((String)$entryfare === ""){
          echo "<script type='text/javascript'>alert('Please enter a valid Entry Fare');</script>";
          return FALSE;
        }
        else if($station1 !== 'bus' && $station1 !== 'train'){
          echo "<script type='text/javascript'>alert('Please select a station type(bus or station)');</script>";
          return FALSE;
        }
        else if($station1 === 'train' && $nearestintersection !== ""){
          echo "<script type='text/javascript'>alert('Train stations do not have Nearest Intersection');</script>";
          return FALSE;
        }
        else if($entryfare < 0.0 || $entryfare > 50.0){
          echo "<script type='text/javascript'>alert('The fare to enter at the station must be between $0.00 and $50.00, inclusive');</script>";
          return FALSE;
        }
        else{
          $existed_data = Get_Station_info();
          for ($i = 0; $i < count($existed_data); $i++){
            if ($stationname === $existed_data[$i]['Name'] && $stationtype === (int)$existed_data[$i]['IsTrain']){
              echo "<script type='text/javascript'>alert('Failed to Create Station, A station with same name and type is already exist.');</script>";
              return FALSE;
            }
            if ($stopID === $existed_data[$i]['StopID']){
              echo "<script type='text/javascript'>alert('Failed to Create Station, A station with same stop ID is already exist.');</script>";
              return FALSE;
            }
          }
        }
        return TRUE;
      }

?>



<!DOCTYPE html>
<html>
<head>
  <center>
<style>
table, th{
    border: 1px solid black;
    border-collapse: collapse;
}
th{
    padding: 5px;
    text-align: left;
}

td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}
.selected {
    background-color: brown;
    color: #FFF;
}
</style>
</head>
<body>



<form name="" action="#" method="post">
    Station Name: <input type="text" name="station_name">
    Stop ID: <input type="text" name="stop_id">
    Entry Fare($): <input type="text" name="entry_fare">
    <br>
    Station Type: <br>
    <input type="radio" name="Station" value="bus"> Bus Station<br>
    Nearest Intersection<br>
    <input type="text" name="Nearest_Intersection"><br>
    <input type="radio" name="Station" value="train"> Train Station<br>

    <br>
    <input type="checkbox" name="open_station" value="open"> Open Station<br>
    When checked, passengers can enter at this station.<br>

    <br>

    <input type="submit" name="createstationbtn" value="Create Station">
    <input type="submit" name="back" value="Cancel">
</form>


    <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./sign_in_files/ie10-viewport-bug-workaround.js"></script>

</body></html>

