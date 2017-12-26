<?php
#####################
#Database Connection
	$username = 'cs4400_Group_67';
    $password = '1tbVjAxq';
	$database = 'cs4400_Group_67';
	$host = 'academic-mysql.cc.gatech.edu';

	$conn = new mysqli($host, $username, $password, $database);
###Ensure Correct Connection of Database
	if ($conn->connect_error){
		die("Connection failed" . $conn->connect_error);
	}
	else{
		//echo "success\n";
	}
###################################################
	#Database query functions
	function queryRet($query){
		$conn = $GLOBALS['conn'];
		$queryResult = $conn->query($query);
		return $queryResult->num_rows > 0 ? $queryResult->fetch_all(MYSQLI_ASSOC) : NULL;
	}

	function queryInsert($query){
		$conn = $GLOBALS['conn'];
		return $conn->query($query);
	}

	function query($query) {
        $conn = $GLOBALS['conn'];
        if ($conn->query($query) === TRUE) {
                echo '<script type="text/javascript">alert("The insert is successful");</script>';
        } else {
				echo '<script type="text/javascript">alert("The insert is failed");</script>';
        }
    }

###################################################
	#Database sentence

	function Get_Station_name($stopID){
		$querystring = "SELECT Name FROM Station "
			. "WHERE "
			. "StopID='" . $stopID . "'";
		return $querystring;
		return queryRet($querystring);
	}

	function Get_Station_fare($stopID){
		$querystring = "SELECT EnterFare FROM Station "
			. "WHERE "
			. "StopID='" . $stopID . "'";
		return queryRet($querystring);
	}

	function Get_current_breezecard(){
		$querystring = "SELECT * FROM Breezecard";
		return queryRet($querystring);
	}




	function Get_Trip_info(){
		$querystring = "SELECT * FROM Trip";
		return queryRet($querystring);
	}



////////////////////////////////////////////////////////////////////
/////Sign in
	function sign_in($usrname, $pwd){
		$querystring = "SELECT * FROM User "
				."WHERE "
				."Username='". $usrname . "' "
				."AND "
				."Password='" . $pwd . "'";
		return queryRet($querystring);
	}

/////////////////////////////////////////////////////////////////////
/////Register
/////////////////////////////////////////////////////////////////////
	function Get_Passenger_info(){
		$querystring = "SELECT * FROM Passenger";
		return queryRet($querystring);
	}
	function InsertNewUser($username, $password){
		$querystring = "INSERT INTO User ("
			. "Username, "
			. "Password, "
			. "IsAdmin) "
			. "VALUES("
			. "'" . $username . "', "
			. "'" . $password . "', "
			. "'" . '0' . "');";

		return queryInsert($querystring);
	}

	function InsertPassenger($username, $Email){
		$querystring = "INSERT INTO Passenger ("
			. "Username, "
			. "Email) "
			. "VALUES("
			. "'" . $username . "', "
			. "'" . $Email . "');";
		return queryInsert($querystring);
	}

	function InsertBreezeCard($username, $BreezecardNum){
		$querystring = "INSERT INTO Breezecard ("
			. "BreezecardNum, "
			. "Value, "
			. "BelongsTo) "
			. "VALUES("
			. "'" . $BreezecardNum . "', "
			. "'" . '0' . "', "
			. "'" . $username . "');";

		return queryInsert($querystring);
	}

	function checkvalidexisted($cardnum){
		$querystring = "SELECT BreezecardNum FROM Breezecard "
			. "WHERE BreezecardNum='" . $cardnum . "'";
		//return $querystring;
		return queryRet($querystring);
	}

	function findConfliction($cardnum, $username){
		$querystring = "SELECT BreezecardNum, BelongsTo FROM Breezecard "
			. "WHERE BelongsTo !='" . $username . "' "
			. "AND BreezecardNum ='" . $cardnum . "';";
		//return $querystring;
		return queryRet($querystring);
	}


	function InsertConflict($username, $BreezecardNum){
		$querystring = "INSERT INTO Conflict ("
			. "Username, "
			. "BreezecardNum, "
			. "DateTime)"
			. "VALUES("
			. "'" . $username . "', "
			. "'" . $BreezecardNum . "', "
			. "NOW()" . ");";

		return queryInsert($querystring);
	}
///////////////////////////////////////////////////////////////////////////
//////Welcome Page
///////////////////////////////////////////////////////////////////////////


	function Get_user_cardnum($username){
		$querystring = "SELECT T.BreezecardNum FROM (SELECT * FROM Breezecard WHERE BelongsTo = '" . $username ."' AND BreezecardNum NOT IN (SELECT BreezecardNum FROM Conflict))T";
		//return $querystring;
		return queryRet($querystring);
	}

	function Get_user_money($cardnum){
		$querystring = "SELECT Value FROM Breezecard "
			. "WHERE BreezecardNum='" . $cardnum . "'";
		//return $querystring;
		return queryRet($querystring);
	}

	function Get_station(){
		$querystring = "SELECT Name, EnterFare, StopID FROM Station WHERE ClosedStatus='0'";
		//return $querystring;
		return queryRet($querystring);
	}

	function Get_current_fare($stopID){
		$querystring = "SELECT EnterFare, StopID FROM Station "
			. "WHERE StopID='" . $stopID . "';";
		//return $querystring;
		return queryRet($querystring);
	}

	function onTrip($usrname){
		$querystring = "SELECT StartsAt, Tripfare, BreezecardNum, EndsAt, StartTime FROM Trip WHERE BreezecardNum IN (SELECT T.BreezecardNum FROM (SELECT * FROM Breezecard WHERE BelongsTo = '" . $usrname . "')T) AND EndsAt IS NULL;";
		//return $querystring;
		return queryRet($querystring);
	}

	function Get_ongoing_station($stopID){
		$querystring = "SELECT Name, StopID FROM Station "
			. "WHERE StopID='" . $stopID . "'";
		//return $querystring
		return queryRet($querystring);
	}


	function startTrip($BreezecardNum, $fare, $start){
		$querystring = "INSERT INTO Trip ("
			. "Tripfare, "
			. "StartTime, "
			. "BreezecardNum, "
			. "StartsAt, "
			. "EndsAt)"
			. "VALUES("
			. "'" . $fare . "', "
			. "NOW()". ", "
			. "'" . $BreezecardNum . "', "
			. "'" . $start . "', "
			. "NULL" . ");";

		return queryInsert($querystring);
	}

	function getValidend($stopID){
		$querystring = "SELECT Name, StopID, IsTrain FROM Station WHERE IsTrain IN (SELECT IsTrain FROM (SELECT StopID, IsTrain FROM Station WHERE StopID ='". $stopID . "')T) AND ClosedStatus=0";
		//return $querystring;
		return queryRet($querystring);
	}

	function endTrip($time, $end, $BreezecardNum){
		$querystring = "UPDATE Trip "
			. "SET EndsAt='" . $end ."' "
			. "WHERE BreezecardNum='" . $BreezecardNum ."' "
			. "AND StartTime='" . $time . "';";
		return queryRet($querystring);
	}

//////////////////////////////////////////////////////////////////////////
///////Station Management
/////////////////////////////////////////////////////////////////////////

	function Grab_Station_info(){
		$querystring = "SELECT Name, StopID, EnterFare, ClosedStatus FROM Station";
		return queryRet($querystring);
	}
//////////////////////////////////////////////////////////////////////////////////////////
//////
/////	#Create Station
//////////////////////////////////////////////////////////////////////////////////////////
	function InsertStation($stopID, $stationname, $entryfare, $openstation, $stationtype){
		$querystring = "INSERT INTO Station ("
			. "StopID, "
			. "Name, "
			. "EnterFare, "
			. "ClosedStatus, "
			. "IsTrain)"
			. "VALUES("
			. "'" . $stopID . "', "
			. "'" . $stationname . "', "
			. (String)$entryfare . ", "
			. (String)$openstation . ", "
			. (String)$stationtype . ");";

		return queryInsert($querystring);
	}


	function Get_Station_info(){
		$querystring = "SELECT Name, StopID, IsTrain FROM Station";
		return queryRet($querystring);
	}

	function InsertBus($stopID, $nearestintersection){
		$querystring = "INSERT INTO BusStationIntersection ("
			. "StopID, "
			. "Intersection)"
			. "VALUES("
			. "'" . $stopID . "', "
			. "'" . $nearestintersection . "');";

		return queryInsert($querystring);
	}


//////////////////////////////////////////////////////////////////////////
////Station Details
//////////////////////////////////////////////////////////////////////////
	function findIntersection($stopID){
		$querystring = "SELECT Intersection FROM BusStationIntersection WHERE StopID = "
			. "'" . $stopID . "'";
			// echo $querystring;
		return queryRet($querystring);

	}

	function get_station_status($stopID){
		$querystring = "SELECT ClosedStatus FROM Station WHERE "
			. "StopID='" . $stopID . "'";
		//return $querystring;
		return queryRet($querystring);
	}
	function station_open_status($choice, $stopID){
		$querystring = "UPDATE Station "
			. "SET ClosedStatus="
			. "'" . $choice . "' "
			. "WHERE StopID='" . $stopID . "';";
		query($querystring);
	}

	function update_fare($cost, $stopID){
		$querystring = "UPDATE Station "
			. "SET EnterFare="
			. "'" . $cost . "' "
			. "WHERE StopID='" . $stopID . "';";
		query($querystring);
	}


/////////////////////////////////////////////////////////////////////////
/////Breezecard Management
/////////////////////////////////////////////////////////////////////////
	function check_suspended($Breezecard){
		$querystring = "SELECT count(1) from Conflict WHERE BreezecardNum = ";
		$querystring .=  "$Breezecard" . ";";
			// echo $querystring;
		return queryRet($querystring);
	}

	function manage_cards($suspended, $owner, $cardnum, $minValue, $maxValue){
		$querystring = "SELECT BreezecardNum, Value, BelongsTo FROM Breezecard "
			. "WHERE ";
			if ($suspended !== "show"){
				$querystring .= "BreezecardNum NOT IN (SELECT BreezecardNum FROM Conflict) AND ";
			}
			if  ($owner !== ""){
				$querystring .= "BelongsTo='". $owner . "' AND ";
			}
			if ($cardnum !== ""){
				$querystring .= "BreezecardNum='" . $cardnum . "' AND ";
			}
			if ($minValue !== ""){
				$querystring .= "Value >='" . $minValue . "' AND ";
			}
			if ($maxValue !== ""){
				$querystring .= "Value <='" . $maxValue . "' AND ";
			}
		$querystring = preg_replace('/\W\w+\s*(\W*)$/', '$1', $querystring);
		//return $querystring;
		return queryRet($querystring);
	}

	function updateCardValue($cardnum, $value){
		$querystring = "UPDATE Breezecard "
			. "SET Value="
			. "'" . $value . "'"
			. "WHERE BreezecardNum='" . $cardnum . "'";
		//return $querystring;
		return queryRet($querystring);
	}

	function findValidUser($usrname){
		$querystring = "SELECT Username FROM Passenger "
			. "WHERE Username='" . $usrname . "'";
		return $querystring;
		return queryRet($querystring);
	}

	function detectConflict($cardnum){
		$querystring = "SELECT DISTINCT BreezecardNum FROM Conflict "
			. "WHERE BreezecardNum='" . $cardnum ."'";
		//return $querystring;
		return queryRet($querystring);
	}

	function resolveConflictNewOwner($cardnum){
		$querystring = "SELECT T.Username FROM (SELECT Username, BreezecardNum FROM Conflict WHERE BreezecardNum = '". $cardnum ."' AND Username NOT IN (SELECT BelongsTo FROM Breezecard WHERE BelongsTo IN (SELECT Username FROM Conflict WHERE BreezecardNum =  '". $cardnum ."')))T";
		//return $querystring;
		return queryRet($querystring);
	}

	function checkPrivousOwner($usrname){
		$querystring = "SELECT BreezecardNum FROM Breezecard "
			. "WHERE BelongsTo='" . $usrname . "'";
		return queryRet($querystring);
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////Suspended cards.php
	function getSuspendedCards(){
		$querystring = 'SELECT T.BreezecardNum, T.BelongsTo, T.DateTime, T.Username FROM(SELECT * FROM Conflict LEFT JOIN (SELECT BreezecardNum as s, BelongsTo FROM Breezecard) B ON Conflict.BreezecardNum = B.s)T;';
		return queryRet($querystring);
	}

	function UpdateCardBelongs($usrname, $cardnum){
		$querystring = "UPDATE Breezecard "
			. "SET BelongsTo="
			. "'" . $usrname . "' "
			. "WHERE BreezecardNum='" . $cardnum . "'";
		//return $querystring;
		return queryRet($querystring);
	}

	function ClearConflictions($cardnum){
		$querystring = "DELETE FROM Conflict "
			. "WHERE BreezecardNum='" . $cardnum . "'";
		//return $querystring;
		return queryRet($querystring);
	}

	function findnewownerHaveCards_assignnew($usrname, $cardnum){
		$querystring = "SELECT T.Username as Username FROM (SELECT Username FROM Conflict WHERE BreezecardNum='" . $cardnum ."'"
			."AND Username != '".  $usrname . "')T WHERE Username NOT IN (SELECT BelongsTo FROM Breezecard)";
		//return $querystring;
		return queryRet($querystring);
	}

	function findcardofpreviousowner($usrname){
		$querystring = "SELECT DISTINCT BelongsTo FROM Breezecard "
			. "WHERE BelongsTo='" . $usrname . "'";
		//return $querystring;
		return queryRet($querystring);
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////#Manage Cards
	function Get_user_cardinfo($username){
		$querystring = "SELECT T.BreezecardNum , T.Value FROM (SELECT * FROM Breezecard WHERE BelongsTo = '" . $username ."' AND BreezecardNum NOT IN (SELECT BreezecardNum FROM Conflict))T";
		//return $querystring;
		return queryRet($querystring);
	}

	function checknewCards($cardnum){
		$querystring = "SELECT BreezecardNum FROM Breezecard "
			. "WHERE BreezecardNum ='" . $cardnum . "'";
		return queryRet($querystring);
	}

	function isnullowner($cardnum, $usr){
		$querystring = "SELECT BreezecardNum FROM Breezecard "
			. "WHERE BreezecardNum ='" . $cardnum . "' AND "
			. "(BelongsTo IS NULL OR BelongsTo='" . $usr ."')";
		//return $querystring;	
		return queryRet($querystring);
	}

	function RemoveCard($BreezecardNum){
		$querystring = "UPDATE Breezecard SET BelongsTo = NULL WHERE BreezecardNum='" . $BreezecardNum . "';";
		return query($querystring);
	}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
#View Trip History
	function GetTripHistory($usr, $Start_Time, $End_Time){
		$querystring = "SELECT Distinct Trip.StartTime,A.Name AS Name1,B.Name AS Name2,TripFare,Trip.BreezecardNum FROM Trip, Breezecard, Station AS A, Station AS B WHERE ";
		$querystring .= "Trip.BreezecardNum IN (SELECT BreezecardNum FROM Breezecard WHERE BelongsTo = '" . $usr ."') AND ";
		if($Start_Time !== ' '){
			$querystring .= "Trip.StartTime >= '" . $Start_Time ."' AND ";
		}
		if($End_Time !== ' '){
			$querystring .= "Trip.StartTime <= '" . $End_Time ."' AND ";
		}
		$querystring .= "A.StopID = Trip.StartsAt AND ";
		$querystring .= "B.StopID = Trip.EndsAt AND ";
		$querystring .= "Trip.BreezecardNum NOT IN (SELECT BreezecardNum FROM Conflict) ";
		$querystring .= "UNION ";
		$querystring .= "SELECT Trip.StartTime, A.Name AS Name1, EndsAt,TripFare,Trip.BreezecardNum FROM Trip, Breezecard, Station AS A, Station AS B WHERE ";
		$querystring .= "Trip.BreezecardNum IN (SELECT BreezecardNum FROM Breezecard WHERE BelongsTo = '" . $usr ."') AND ";
		if($Start_Time !== ' '){
			$querystring .= "Trip.StartTime >= '" . $Start_Time ."' AND ";
		}
		if($End_Time !== ' '){
			$querystring .= "Trip.StartTime <= '" . $End_Time ."' AND ";
		}
		$querystring .= "Trip.EndsAt is NULL AND ";
		$querystring .= "A.StopID = Trip.StartsAt AND ";
		$querystring .= "Trip.BreezecardNum NOT IN (SELECT BreezecardNum FROM Conflict);";

		// echo $querystring;
		return queryRet($querystring);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
#passenger_flow_report
	function passenger_flow_report($start_time, $end_time){
	  $querystring = "CREATE OR REPLACE VIEW InitialTrip AS SELECT Tripfare, StartTime, BreezecardNum, StartsAt, EndsAt From Trip WHERE ";
	  if($start_time !== ' '){
	    $querystring .= "StartTime >='" . $start_time ."' AND ";
	   }
	   if($end_time !== ' '){
	    $querystring .= "StartTime <='" . $end_time . "' AND ";
	   }
	   $querystring .= "1 ";
	   // echo $querystring;
	  queryRet($querystring);


	  $querystring = "CREATE OR REPLACE VIEW passengerIn AS SELECT StartsAt AS station, SUM(Tripfare) AS sumfare, COUNT(BreezecardNum) AS p_in, StartTime From InitialTrip Group by station; ";
	  queryRet($querystring);

	  $querystring = "CREATE OR REPLACE VIEW passengerOut AS SELECT EndsAt AS station, COUNT(BreezecardNum) AS p_out, StartTime FROM InitialTrip GROUP BY station; ";
	  queryRet($querystring);

	  $querystring = "CREATE OR REPLACE VIEW passengerInJoinOut AS SELECT station, p_in, p_out, sumfare, StartTime FROM passengerIn NATURAL LEFT JOIN passengerOut; ";
	  queryRet($querystring);

	  $querystring = "CREATE OR REPLACE VIEW passengerOutJoinIn AS SELECT station, p_in, p_out, sumfare, StartTime FROM passengerIn NATURAL RIGHT JOIN passengerOut; ";
	  queryRet($querystring);

	  $querystring = "CREATE OR REPLACE VIEW passengerInAndOut AS SELECT * FROM passengerInJoinOut UNION SELECT * FROM passengerOutJoinIn; ";
	  queryRet($querystring);

	  $querystring = "CREATE OR REPLACE VIEW passenger_flow_report AS SELECT Station.Name AS Name, IFNULL(sumfare, 0) AS revenue, IFNULL(p_in, 0) AS passenger_in, IFNULL(p_out, 0) AS passenger_out, IFNULL(IFNULL(p_in, 0)-IFNULL(p_out, 0), 0) AS flow,  station FROM passengerInAndOut INNER JOIN Station on passengerInAndOut.station = Station.stopID; ";
	  queryRet($querystring);

	   $querystring = "SELECT Name, SUM(passenger_in) AS passenger_in, SUM(passenger_out) AS passenger_out, SUM(flow) AS flow, SUM(revenue) AS revenue FROM passenger_flow_report";
	   $querystring .= " GROUP BY station;";

	   // echo $querystring;
	  return queryRet($querystring);

 }

?>