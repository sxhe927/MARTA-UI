<?php session_start();
    ob_start();
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


<form action="#" method="post">

  <?php $station = isset($_GET['var_value']) ? $_GET['var_value'] : "aaaa";
  // $station = 'Sandy Springs';
  echo $station;?> Stop:
  <?php
    include 'db_info.php';

    // $sdata = Get_Station_info();
    // $currentid;
    // for ($i = 0; $i < count($sdata); $i++){
    //   if ($station === $sdata[$i]['Name']){
    //     $currentid = $sdata[$i]['StopID'];
    //     echo $sdata[$i]['StopID'];
    //   }
    // }
    $currentid = $_GET['stopID'];
    echo $currentid;

    ?><br>

  Fare($) <input type="text" name="fare" value="<?php $cur_fare = $_GET['current_fare']; echo $cur_fare;?>">
  <button type="submit" name="updatefare" >Update Fare</button>
  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatefare'])){
      $new_fare = $_POST['fare'];
      if ($new_fare >= 0 && $new_fare <= 50){
        update_fare($new_fare, $currentid);
        echo '<script type="text/javascript">window.location.href = "Station_Management.php"</script>';
      }
      else{
        echo '<script type="text/javascript">alert("Please Input valid fare");</script>';
      }
    }

  ?>
  <br>
  Nearest Intersection:

    <?php
    $idata = findIntersection($currentid);
    $flag = false;
    echo "<p>";
    if (isset($idata)){

      for ($i = 0; $i < count($idata); $i++){
        // echo $currentid === $idata[$i]['id'] ? $idata[$i]['StopID'] : $idata[$i]['id'];
        echo $idata[$i]['Intersection'];
        echo "<br>";
      }
    }
    else{
      echo "no";
    }
    // for ($i = 0; $i < count($idata); $i++){
    //   if ($currentid === $idata[$i]['StopID']){
    //     $flag = true;
    //     echo $idata[$i]['StopID'];
    //   }
    // }
  ?>
  <?php

    $S = get_station_status($currentid);
    $flag =  $S[0]['ClosedStatus'] === '0' ? true : false;
  ?>
  <br>
  <input type="checkbox" name="open_station" <?php if ($flag === true) echo 'checked="checked"';?>>Open Station <br>

  <?php
    if (isset($_POST['openstation'])){
      include 'redirect.php';
      $open_station = $_POST['open_station'];
      $new_status;
      if ($open_station === on){
        $new_status = 0;
      }
      else{
        $new_status = 1;
      }
      station_open_status($new_status,$currentid);
      Redirect("Station_Management.php");
    }

  ?>

 <br> When checked, passengers can enter at this station.
 <button type="submit" name="openstation">confirm change</button>
</form>

<!-- <br> <button onclick="returntolast()">return</button>
</body>
<script type="text/javascript">
  function returntolast() {
    window.location.href = 'Station_Management.php';
  }
</script -->
</html>


