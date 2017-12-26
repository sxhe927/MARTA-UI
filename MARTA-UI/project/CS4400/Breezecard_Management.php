<?php
session_start();
     ob_start();
?>
<?php

	 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Filter'])){
         include "db_info.php";
         $owner = $_POST['passenger_name'];
         $card_number = $_POST['card_number'];
         $low_value = $_POST['low_value'];
         $high_value = $_POST['high_value'];
         $suspended_cards = $_POST['suspended_cards'];

         $sdata = manage_cards($suspended_cards, $owner, $card_number, $low_value, $high_value);
    }
    // if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Reset'])){
    //     resetForm($form_filter);
    // }


    // function resetForm($form) {
    //     $form.find('input:text').val('');
    //     $form.find('input:checkbox').removeAttr('checked');
    // }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Back'])){
        include "redirect.php";
        Redirect("admin.php");
    }



?>

</!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Breeze Card Management</title>
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
<form method="post" name="form_filter">
	<h1>Breeze Cards</h1>
	<h2>Search/Filter</h2>
	Owner: <input type="text" name="passenger_name" id='passenger_name' value="<?php print isset($_POST['card_number'])? $_POST['passenger_name']:'';?>"><br>
	Card Number: <input type="text" name="card_number" id='card_number' value="<?php print isset($_POST['card_number'])? $_POST['card_number']:'';?>"><br>
	Value between <input type="text" name="low_value" id='low_value' value="<?php print isset($_POST['low_value'])? $_POST['low_value']:'';?>"> and <input type="text" name="high_value" id='high_value' value="<?php print isset($_POST['high_value'])? $_POST['high_value']:'';?>"><br>
	<input type="checkbox" name="suspended_cards" id='suspended_cards' value="show" <?php if(isset($_POST['suspended_cards'])) print "checked='checked'"; ?>> Show Suspended Cards<br>
	<button type="submit" name="Filter">Update Filter</button>
	<button type="submit" name="Reset" onclick="clearForm()">Reset</button><br>
  <input type= "submit" name="Back" value="Back">
    <script type="text/javascript">
        function clearForm(){

            document.getElementById("passenger_name").value= "";
            document.getElementById("card_number").value = "";
            document.getElementById("low_value").value = "";
            document.getElementById("high_value").value = "";
            document.getElementById("suspended_cards").checked=false;

        }

    </script>

</form>
<div>
    <input type="text" id="set_value" style="width: 80px"> <button id="set_value_btn" type="submit" onclick="transfer_value()">Set value of Selected card</button>
</div>


<div>
    <input type="text" id="new_name" style="width: 80px"> <button id="transfer" type="submit" onclick="change_owner()">Transfer Selected Card</button>
</div>
<div style="text-align: center;">
<table class="table" style="width:80%" id="BreezeCards">
  <tr>
    <th>Card #</th>
    <th>Value</th>
    <th>Owner</th>
  </tr>
  <?php
    for ($i = 0; $i < count($sdata); $i++){
      $row = $sdata[$i];
      $checkresult = check_suspended($row['BreezecardNum']);
      if($checkresult[0]['count(1)'] === '0'){
        echo "<tr><td>" . $row['BreezecardNum'] . "</td><td>" . $row['Value'] . "</td><td>" . $row['BelongsTo'] . "</td><td>";
      }
      else{
        echo "<tr><td>" . $row['BreezecardNum'] . "</td><td>" . $row['Value'] . "</td><td>" . 'Suspended' . "</td><td>";
      }
    }
    ?>
</table>
</div>




<script type="text/javascript">
  var selected_card;
  var cur_owner;
  $(".table tr").click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
    selected_card = $(this).find('td:nth-child(1)').html();
    cur_owner = $(this).find('td:nth-child(3)').html();
  });

  function transfer_value(){
    var new_money = document.getElementById('set_value').value;

    if (typeof(selected_card) != "undefined" && new_money !== ""){
        if (new_money > 1000 || new_money < 0){
            alert("Please input amount of money correctly");
        }
        window.location.href = "backend.php?set_value=" + new_money + "&selected_card=" + selected_card;
    }
    else {
        alert("please select correctly");
    }
  }

  function change_owner(){
    var new_owner = document.getElementById('new_name').value;
    if (typeof(selected_card) != "undefined" && new_owner !== ""){
         window.location.href = "backend.php?set_name=" + new_owner + "&selected_card=" + selected_card + "&cur_owner=" + cur_owner;
    }
    else{
        alert("Please Select a Breezecard");
    }

  }
</script>




</body>
</html>

