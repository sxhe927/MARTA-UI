<!DOCTYPE html>
<html>
<head>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

<table style="width:80%" id="station" class="table">
  <caption>Suspended Cards</caption>
  <tr>
    <th onclick="sortTable(0)" style="color: blue">Card #</th>
    <th>New Owner</th>
    <th onclick="sortTable(2)" style="color: blue">Date Suspended</th>
    <th>Previous Owner</th>
  </tr>

  <?php
    include 'db_info.php';
    $suspended_data = getSuspendedCards();

    //echo var_dump($sdata->fetch());
    for ($i = 0; $i < count($suspended_data); $i++){
      $row = $suspended_data[$i];
      echo "<tr><td>" . $row['BreezecardNum'] . "</td><td> " . $row['Username'] . "</td><td>" . $row['DateTime'] . "</td><td>" . $row['BelongsTo'] . "</td></tr>";
    }
    ?>
    
</table>

<div>
  <button name="new" type="submit" onclick="pass_data()">Assion Selected Card to New Owner</button>
  <button name="pre" type="submit" onclick="pass_data2()">Assign Selected Card to Previous Owner</button>
  <button name="back" type="submit" onclick="return_back()">Back</button>
</div>

<p id="demo"></p>

<script type="text/javascript">
  var cardNum;
  var new_owner;
  var pre_owner;
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
    cardNum = $(this).find('td:nth-child(1)').html(); 
    new_owner = $(this).find('td:nth-child(2)').html();
    //new_owner = decodeURIComponent(new_owner);    
    pre_owner = $(this).find('td:nth-child(4)').html();

  });

  function pass_data(){
   if (typeof(cardNum) != "undefined"){
      var url = "backend.php?assign_new=" + cardNum  + "&pre_owner=" + pre_owner + "&new_owner=" + new_owner;
      //document.getElementById("demo").innerHTML = url;
      window.location.href = url;
    }
  }

  function pass_data2(){
    if (typeof(cardNum) != "undefined"){
      //alert(cardNum);
      window.location.href = "backend.php?assign_old=" + cardNum + "&pre_owner=" + pre_owner;
      //window.location.href = 'Station_Detail.php'
    //});
    }

  }

  function return_back(){
    window.location.href = 'admin.php';
  }

</script>
</body>
</html>



