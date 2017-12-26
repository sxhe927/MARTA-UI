<?php session_start();
    ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Passenger Flow Report</title>
<style type="text/css">
  td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}
  .selected {
    background-color: brown;
    color: #FFF;
}
</style>
</head>
<body>


<form action="#" method="post">
<br>
  Start time:
  <input type="datetime-local" name="start_time" step=1>

 </b>

<br>
  End Time:
  <input type="datetime-local" name="end_time" step=1>
</b>

<br>
  <input type="submit" name="update" value="update">
  <input type="submit" name="reset" value="reset">
  <input type="submit" name="back" value="Back">
</form>

<table class="table" style="width:80%" id="station">
  <caption>Passenger Flow Report</caption>
  <tr>
    <th onclick="sortTable(0)" style="color: red">Station Name</th>
    <th># Passenger In</th>
    <th># Passenger Out</th>
    <th>Flow</th>
    <th>Revenue</th>
  </tr>
<?php
    include "redirect.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
      include "db_info.php";
      $start_time = substr_replace($_POST['start_time'],' ',10,1);
      $end_time = substr_replace($_POST['end_time'],' ',10,1);


      if (validateInputs($start_time, $end_time) === TRUE){
        $row = passenger_flow_report($start_time, $end_time);
        // echo $row[0]['StartTime'];
        for ($j = 0; $j < count($row); $j++){
          echo "<tr><td>" . $row[$j]['Name'] . "</td><td> " . $row[$j]['passenger_in'] . "</td><td>" . $row[$j]['passenger_out'] . "</td><td>" . $row[$j]['flow'] ."</td><td>" . $row[$j]['revenue'] .  "</td></tr>";
        }
      }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['back'])){
      Redirect("admin.php");
    }

    function validateInputs($start_time, $end_time){
      if ($start_time === ' ' OR $end_time === ' '){
        return TRUE;
      }
      if ($start_time > $end_time){
        echo "<script type='text/javascript'>alert('End time should be later than start time!');</script>";
        return FALSE;
      }
      return TRUE;

    }

?>
</table>

<script type="text/javascript">
  function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("station");
  switching = true;
  dir = "asc";
  while (switching) {

    switching = false;
    rows = table.getElementsByTagName("TR");

    for (i = 1; i < (rows.length - 1); i++) {

      shouldSwitch = false;

      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];

      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {

          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {

      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;

      switchcount ++;
    } else {

      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
  $(".table tr").click(function(){
   $(this).addClass('selected').siblings().removeClass('selected');
   value = $(this).find('td:first').html();

  });
</script>

</body>
</html>


