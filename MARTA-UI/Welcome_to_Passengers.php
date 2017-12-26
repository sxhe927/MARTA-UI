<?php session_start();
    ob_start();
?>
<!DocTYPE html>
<html>
<head>
<title>Welcome to Passengers</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>


<body bgcolor="#ffffff">
<center>
<font color="#000000">

<br /><br />

<!--<form action="edQueryResult.php" method="post"> -->


<!-- display the card_number of all the cards owned by this user-->
<div id="test">
<form method="post" action=""  name="form1" id="form1" >
<p>Welcome to MARTA <input type="text" id="User" name="User" disabled="true" value="<?php $usr=$_SESSION['user']["name"]; echo $usr; ?>"></p>
Breeze Card 
<select name="selectnum" id="selectnum">
<option value=""></option>
<?php
    if (isset($_SESSION['user'])){
        include "db_info.php";
        $usr = $_SESSION['user']["name"];
 

        $usr_cardnum = Get_user_cardnum($usr);
    
        for ($i = 0; $i < count($usr_cardnum); $i++){
            $temp = $usr_cardnum[$i];
            if (!in_array($temp, $suspended_cardnum)){
                echo "<option value=\"".$temp['BreezecardNum']."\">" . $temp['BreezecardNum'] . "</option>";
            }        
        }       

    }
?>
</select>
<a href="Manage_Cards.php">Manage Cards</a>
<input type="submit" name="select_card" id="select_card" value="Show money" onclick="reload()">
</div>
</form>

<script>
function reload() {
    localStorage.setItem('selectedVal',$('#selectnum').val());
    location.reload(true);
}
$( document ).ready(function() {
    var selectedVal = localStorage.getItem('selectedVal');
    if (selectedVal){
       $('#selectnum').val(selectedVal)
    }
   
});
</script>
<p>
</p>
<input type="text" disabled="true" style="width: 200px" name="select_card" value="<?php
    //echo "11";
    if (isset($_POST['select_card'])){
        $usr_value = Get_user_cardnum($usr);
        $choice = $_POST['selectnum'];
        $money_data = Get_user_money($choice);
        $money = $money_data[0]['Value'];
        echo $money;
    }
    else{
        //echo "11";
    }
    
?>" >

<p>
</p>





<br>

Start at <select name="station_start1" id="station_start">;
    <?php
        $station_info = Get_station();

        $isOntrip = onTrip($usr);


        if (!isset($isOntrip)){
            
            for ($i = 0; $i < count($station_info); $i++){
                $current_station = $station_info[$i];
                $currentfare = $current_station['EnterFare'];

                echo "<option value=\"".$current_station['StopID']."\">" . $current_station['Name'] . "-" . $currentfare . "</option>";
            }
            echo "<input id='button1' type='submit' name='start_btn' onclick='fun1()'' value='start trip' >";

        }
        else{
            //echo "Start at " .'<select name="station_start" id="station_start" disabled="true">';
            $on_goingID = $isOntrip[0]['StartsAt'];
            $on_goingFare = $isOntrip[0];
            $on_going_nameqs = Get_ongoing_station($on_goingID);
            $on_going_name = $on_going_nameqs[0]['Name'];

            echo "<option value=\"".$$on_goingID."\" selected='selected'>" . $on_going_name . "</option>";
            echo "<input id='button1' type='submit' name='start_btn' onclick='fun1()'' value='In progress' disabled='true'>";
        }
        


    ?>
</select>
<!-- <input id="button1" type="submit" name="start_btn" onclick="fun1()" value="start trip" > -->

<p>
    
</p> 
<?php
    if (isset($isOntrip)){
        $cur_stationID = $isOntrip[0]['StartsAt'];
        //echo $cur_stationID;
        $validend = getValidend($cur_stationID);
        echo "Ends at " .'<select name="station_end" id="station_end">';
        for ($i = 0; $i < count($validend); $i++){
            $cur_end = $validend[$i];
            echo "<option value=\"". $cur_end['StopID'] ."\">" . $cur_end['Name'] . "</option>";
        }
        echo "</select>";
        echo "<input id='button1' type='submit' name='start_btn' onclick='fun2()' value='ends at' >";
    }
    else{

        echo "Ends at " .'<select name="station_end" id="station_end">';
        for ($i = 0; $i < count($station_info); $i++){
                $current_station = $station_info[$i];
                $currentfare = $current_station['EnterFare'][0];

                echo "<option value=\"".$current_station['StopID']."\">" . $current_station['Name'] . "-" . $currentfare . "</option>";
            }
            echo "<input id='button1' type='submit' name='start_btn' onclick='fun1()'' value='ends at' disabled='true'>";
    }

?>
<!-- <select name="station_end_at">
<option value="station4">northsouth</option>
<option value="station5">southnorth</option>
<option value="station6">westeast</option></select>
<button id="button2" onclick="fun2()" disabled>end trip</button>
-->

<p>
    
</p>
<br> 
<input type="submit" value="View Trip History"  name="trip_history" onclick="jumpto_trip()">
<script type="text/javascript">
    function jumpto_trip(){
        window.location.href = 'View_Trip_History.php'
    }
</script>

</form>


<form action="logout.php" method="post">
<input type="submit" value="Log out" name="Log out">
</form>



<br /> 
<br /> 
<br /> 
<!-- <?php
    // if ($_POST['form1']){
    //     $current_num = $_POST['selectnum'];
    //     echo "<p>" . $current_num . "</p>";
    // }  
    
?> -->

<script>
var flag = true;

function fun1() {
    if(flag) {
        var select_card = document.getElementById("selectnum").value;
        var start_station = document.getElementById("station_start");
        var start_stationValue = start_station.value;

        window.location.href = "backend.php?selected_card_wcm=" + select_card + "&station_start=" + start_stationValue;
    }
}

function fun2() {
    if(flag) {
        var name = document.getElementById("User").value;
        var end_station = document.getElementById("station_end").value;
        //alert(end_station);
        window.location.href = "backend.php?selected_card_end=" + name + "&end_station=" + end_station;
        flag = true;
    }
}
</script>
</body>
</html>