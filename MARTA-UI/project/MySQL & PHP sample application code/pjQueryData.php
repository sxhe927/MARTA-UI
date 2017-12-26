<?php
include 'dbinfo.php' ; 
?>  

<?php
//retrieve session data    
  session_start();  
//echo "Manager SSN is  ". $_SESSION['manager'] . "<br />";
 $manager = $_SESSION['manager'];  
?>

<?php

mysql_connect($host,$username,$password) or die( "Unable to connect");;
mysql_select_db($database) or die( "Unable to select database");

         //SQL Query
         $sql_query = "Select Distinct plocation From project,department             
             Where dnum = dnumber And mgrssn =  $manager";

         //Run our sql query
           $result = mysql_query ($sql_query)  or die(mysql_error());

           $num = mysql_numrows($result);

          //Close Database Connection
           mysql_close ();

//            echo "The Query returns $num rows <br>"  ;
?>




<html>
<head>
<title>Choose Data for Project Query    </title>
</head>


<html>
<body bgcolor="#000000">
<center>
<font color="#ffffff">
<p>CHOOSE DATA FOR PROJECT QUERY </p>
<br /><br />



<body>
<form action="pjQueryResult.php" method="post">

<b> Choose Project Cities : </b><br />

<?php
          $i=0;
          while ($i < $num) {
           $plocation = mysql_result($result,$i,"plocation");
 ?>

           <?php echo $plocation; ?>:<input type="checkbox" value= "<?php echo $plocation ?>" name="ploc[]"><br />


<?php
             $i++;
          }
 ?>    


<input type="submit" value="submit" name="submit">
</form>


<br /> 
<br /> 
<br /> 

<form action="menu.php" method="post">
  <input type="submit" value=" Return to Main Menu" name="submit">
</form>
