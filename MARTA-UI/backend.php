<?php
	session_start();
     ob_start();
?>

<?php
#############################################################
########
##Manage Suspended Cards
//assign new 
	if (isset($_GET['assign_new'])){
		include "db_info.php";
		include 'redirect.php';
		$card_selected = $_GET['assign_new'];
		$new_owner = $_GET['new_owner'];
		$pre_owner = $_GET['pre_owner'];
		$new_owner1 = substr($new_owner, 1);

		//Assign Conflict Card to new owner
		UpdateCardBelongs($new_owner1, $card_selected);

		//Check Other new owner whether has a card
		$new_owner_no_card = findnewownerHaveCards_assignnew($new_owner, $card_selected);

		if (isset($new_owner_no_card)){
			for ($i = 0; $i < count($new_owner_no_card); $i++){
				$new_card_num = generateCardnum();
				InsertBreezecard($new_owner_no_card[$i]['Username'], $new_card_num);
			}
		}

		//Check privious owner about the card
		$pre_owner_no_card = findcardofpreviousowner($pre_owner);
		echo $pre_owner_no_card[0];
		if (!isset($pre_owner_no_card)){
			$new_card_num = generateCardnum();
			InsertBreezecard($pre_owner, $new_card_num);
		}

		//Clear Current Conflictions
		ClearConflictions($card_selected);
		//echo $card_selected;
		// echo $pre_owner;
		//echo $new_owner;


		Redirect('suspended_cards.php');

	 }
	 //assign old
	if (isset($_GET['assign_old'])){
		include 'db_info.php';
		include 'redirect.php';
		$card_selected = $_GET['assign_old'];
		$pre_owner = $_GET['pre_owner'];

		//Check pre owner whether have cards
		$new_owner_no_card = findnewownerHaveCards_assignnew($pre_owner, $card_selected);

		if (isset($new_owner_no_card)){
			for ($i = 0; $i < count($new_owner_no_card); $i++){
				$new_card_num = generateCardnum();
				InsertBreezecard($new_owner_no_card[$i]['Username'], $new_card_num);
			}
		}

		//Clear Confliction
		ClearConflictions($card_selected);

		Redirect('suspended_cards.php');

	}
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////
//////Breezecard Management

/////// set value
	 if (isset($_GET['set_value']) && isset($_GET['selected_card'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$new_money = $_GET['set_value'];
	 	$card = $_GET['selected_card'];
	 	echo $new_money;
	 	echo $card;
	 	if ($new_money > 1000){
	 		//echo '<script type="text/javascript">alert("Invalid Amount of Money");</script>';
	 		Redirect('Breezecard_Management.php');
	 	}
	 	else{
	 		updateCardValue($card, $new_money);
	 		Redirect('Breezecard_Management.php');
	 	}
	 }
///////Transfer card to new owner
	 if (isset($_GET['set_name']) && isset($_GET['selected_card']) && isset($_GET['cur_owner'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$new_owner = $_GET['set_name'];
	 	$card = $_GET['selected_card'];
	 	$cur_owner = $_GET['cur_owner'];

	 	if (findValidUser($new_owner) === null){
	 		echo '<script type="text/javascript">alert("Invalid user"); window.location.href="Breezecard_Management.php"</script>';
	 	}
	 	else{
	 		if (detectConflict($card) !== null){
	 			UpdateCardBelongs($new_owner, $card);

	 			$conflict_new = resolveConflictNewOwner($card);
	 			if (isset($conflict_new)){
	 				for ($i = 0; $i < count($conflict_new); $i++){
	 					$new_card_num = generateCardnum();
	 					InsertBreezecard($conflict_new[$i]['Username'], $new_card_num);
	 				}
	 			}

	 			if (checkPrivousOwner($cur_owner) === null){
	 				$new_card_num = generateCardnum();
	 				InsertBreezecard($cur_owner, $new_card_num);
	 			}

	 			ClearConflictions($card);
	 			Redirect('Breezecard_Management.php');

	 		}
	 		else{
	 			UpdateCardBelongs($new_owner, $card);
	 			if (checkPrivousOwner($cur_owner) === null){
	 				$new_card_num = generateCardnum();
	 				InsertBreezecard($cur_owner, $new_card_num);
	 				echo "11";
	 				Redirect('Breezecard_Management.php');
	 			}
	 			
	 		}
	 	}

	 	echo $new_owner;
	 	echo $card;
	 	echo $cur_owner;
	 	

	 }
////////Manage cards (each passenger)
/////////////////////////////////////
	 ///add new card
	 if (isset($_GET['new_card'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$usr = $_SESSION['user']["name"];
	 	$new_card = $_GET['new_card'];
	 	//echo $new_card;
	 	if (checknewCards($new_card) !== null){
	 		if (isnullowner($new_card,$usr) !== null){
	 			UpdateCardBelongs($usr, $new_card);
	 		}else{
	 			InsertConflict($usr, $new_card);
	 		}

	 	}else{
	 		InsertBreezeCard($usr, $new_card);
	 	}

	 	Redirect('Manage_Cards.php');
	 }
	 ///add value
	 if (isset($_GET['selected_cardmc'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$selected_card = $_GET['selected_cardmc'];
	 	$cur_value = $_GET['cur_value'];
	 	$extra_value = $_GET['extra_value'];
	 	$new_value_to_card = $cur_value + $extra_value;
	 	if ($new_value_to_card > 1000){
	 		echo '<script type="text/javascript">alert("Exceeds 1000"); window.location.href="Manage_Cards.php"</script>';
	 	}
	 	else if ($new_value_to_card < $cur_value){
	 		echo '<script type="text/javascript">alert("Please input positive value"); window.location.href="Manage_Cards.php"</script>';
	 	}
	 	else{
	 		updateCardvalue($selected_card, $new_value_to_card);
	 		Redirect('Manage_Cards.php');
	 	}
	 	

	 }
	 ///delete card
	 if (isset($_GET['selected_delete'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$usr= $_SESSION['user']["name"];
	 	$delete_card = $_GET['selected_delete'];
	 	RemoveCard($delete_card);

	 	echo $delete_card;
	 	
	 	if (checkPrivousOwner($usr)===null){
	 		$new_card_num = generateCardnum();
	 		InsertBreezecard($usr, $new_card_num);
	 	}
	 	Redirect('Manage_Cards.php');
	 }

	
////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////
#####Welcome to passenger page

	 ///start trip
	if (isset($_GET['selected_card_wcm'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$selected_card = $_GET['selected_card_wcm'];
	 	$stopID = $_GET['station_start'];

	 	$cur_money_qs = Get_user_money($selected_card);
	 	$cur_money = $cur_money_qs[0]['Value'];

	 	$station_fare_qs = Get_current_fare($stopID);
	 	$station_fare = $station_fare_qs[0]['EnterFare'];
	 	echo $station_fare;
	 	echo $cur_money;
	 	$new_value_money = $cur_money - $station_fare;
	 	echo $new_value_money;
	 	if ($new_value_money > 0){
	 		echo "11";
	 		updateCardValue($selected_card, $new_value_money);
	 		startTrip($selected_card, $station_fare, $stopID);
	 		Redirect("Welcome_to_Passengers.php");
	 	}
	 	else{
	 		echo "00";
	 		//sleep(5);
	 		echo '<script type="text/javascript">alert("No money"); window.location.href = "Welcome_to_Passengers.php"</script>';
	 		
	 		//echo '<script type="text/javascript">window.location.href="Welcome_to_Passengers.php"<script type="text/javascript">';
	 	}
	 	
	 }

	 ///end trip
	 if (isset($_GET['selected_card_end'])){
	 	include 'db_info.php';
	 	include 'redirect.php';
	 	$usr = $_GET['selected_card_end'];
	 	$end_id = $_GET['end_station'];
	 	$ontrip_info = onTrip($usr);

	 	$start_time = $ontrip_info[0]['StartTime'];
	 	$card_num = $ontrip_info[0]['BreezecardNum'];
	 	endTrip($start_time, $end_id, $card_num);
	 	Redirect("Welcome_to_Passengers.php");
	 }

	function generateCardnum(){
		//include 'db_info.php';
        $new_card = mt_rand(10000000,99999999).mt_rand(10000000,99999999);
        if (checkvalidexisted($new_card) !== null){
            generateCardnum();

        }
        return $new_card;
     }


?>
