<?php session_start();
    ob_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Cards</title>
<style>
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}
.selected {
    background-color: brown;
    color: #FFF;
}
</style>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
var value;
$(".table tr").click(function(){
 $(this).addClass('selected').siblings().removeClass('selected');
 value = $(this).find('td:first').html();

});

function pass_data(){
  if (typeof(value) != "undefined"){
    window.location.href = 'add_card.php?var_value=' + value;
  }
}
function pass_data2(){
  if (typeof(value) != "undefined"){
    window.location.href = 'delete_card.php?var_value=' + value;
  }
}

</script>
</head>


<body>
<center>

<p>Manage Cards</p>
<br /><br />

<table class="table" style="width: 70%" id="station">
	<caption>Breeze Cards</caption>
  <thead>
	<tr>
		<th onclick="sortTable(0)" style="color: blue">Card Number</th>
		<th onclick="sortValue()" style="color: blue">Value</th>
		<th>Remove</th>
	</tr>
  </thead>

 <?php

    if (isset($_SESSION['user'])){
        include "db_info.php";
        $usr = $_SESSION['user']["name"];
        $card_num_usr = Get_user_cardinfo($usr);

        for ($i = 0; $i < count($card_num_usr); $i++){
            $temp = $card_num_usr[$i];
            echo "<tr><td>" . $temp['BreezecardNum'] . "</td><td>" . $temp['Value'] . "</td><td>" . "<input type='submit' value='remove' onclick='deleteCard()'>". "</td></tr>";
        }
    }


 ?>
</table>


Please Enter a Breezecard:
<input type="text" id="card_number"><br>
<button type="submit" name="add_card"  onclick="addCard()">Add</button>

 <p>Add Value to Selected Card</p>

Credit Card #: <input type="text" id="creditcard_number"> <br /><br />
Value: <input type="text" id="credit_value"> <br /><br />
<button type="submit" name="usr" onclick="addValue()">Add Value</button>




<script>
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

var dir_value = "asc";
function sortValue(){

    var tbl = document.getElementById("station").tBodies[0];
    var store = [];
   

    for(var i=0, len=tbl.rows.length; i<len; i++){
        var row = tbl.rows[i];
        var sortnr = parseFloat(row.cells[1].textContent || row.cells[1].innerText);
        if(!isNaN(sortnr)) store.push([sortnr, row]);
    }
    //alert(store);
    if (dir_value == "asc"){
      store.sort(function(x,y){
        return x[0] - y[0];
      });
      for(var i=0, len=store.length; i<len; i++){
        tbl.appendChild(store[i][1]);
      }
      store = null;
      dir_value = "desc";
    }
    else if (dir_value == "desc"){
      store.sort(function(x,y){
        return y[0] - x[0];
      });
      for(var i=0, len=store.length; i<len; i++){
        tbl.appendChild(store[i][1]);
      }
      store = null;
      dir_value = "asc";
    }

}

var selected_card;
var cur_value;
$(".table tr").click(function(){
 $(this).addClass('selected').siblings().removeClass('selected');
 selected_card = $(this).find('td:nth-child(1)').html();
 cur_value = $(this).find('td:nth-child(2)').html();
});

function addCard(){
  var new_card = document.getElementById('card_number').value;
  //alert("Incorrect card length");
  if (new_card.length != 16){
    alert("Incorrect card length");
  }
  else{
    window.location.href = "backend.php?new_card=" + new_card;
  }
}

function addValue(){
  var extra_value = document.getElementById('credit_value').value;
  var credit_card = document.getElementById('creditcard_number').value;
  if (typeof(selected_card) != "undefined" && extra_value != "" && credit_card != "" && credit_card.length == 16){
    window.location.href = "backend.php?selected_cardmc=" + selected_card + "&cur_value=" + cur_value + "&extra_value=" + extra_value;
  }else if (typeof(selected_card) == "undefined"){
    alert("Please Select Card");
  }else if (credit_card == "" || credit_card.length != 16){
    alert("Please input valid credit card");
  }else if (extra_value == ""){
    alert("Please input correct value");
  }
}

function pass_data2(){
  if (typeof(selected_card) != "undefined"){
    window.location.href = 'delete_card.php?var_value=' + value;
  }
}

function deleteCard(){
  if (typeof(selected_card) != "undefined"){
    window.location.href = 'backend.php?selected_delete=' + selected_card;
  }
}
</script>

<div>
  <input type="submit" name="back" value="back" onclick="return_back()">
</div>

<script type="text/javascript">
  function return_back(){
    window.location.href = 'Welcome_to_Passengers.php';
  }
</script>


</body>
</html>
