<?php session_start();
    ob_start();
?>
<?php
     include "redirect.php";
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])){
        include "db_info.php";
        $username = $_POST['username'];
        $email = $_POST['inputEmail'];
        $pwd = $_POST['inputPassword'];

        $cfmpwd = $_POST['ConfirmPassword'];
        $breezecard_choice = $_POST['BreezeCard'];
        $existed_num = $_POST['CardNumber'];
        //echo $email;
        //First Create New Passenger
        if (validateInputs($username, $pwd, $cfmpwd, $email, $breezecard_choice, $existed_num) === TRUE){
            $pwd = hash('md5', $pwd);
          if(InsertNewUser($username, $pwd) === TRUE && InsertPassenger($username, $email) === TRUE){

            $_SESSION['user'] = array('name' => $username);
            $_SESSION['usertype'] = 0;

            if ($breezecard_choice === "Old"){
                $conflict_num = findConfliction($existed_num, $username);
                if (isset($conflict_num)){
                    // $pre_user = $conflict_num[0]['BelongsTo'];
                    $conflict_num = $conflict_num[0]['BreezecardNum'];
                    // InsertBreezecard($username, $existed_num);
                    // InsertConflict($pre_user, $conflict_num);
                    // UpdateCardBelonging($username, $conflict_num);
                    $BreezeCard = generateCardnum();
                    InsertBreezecard($username, $BreezeCard);
                    InsertConflict($username, $conflict_num);

                }
                else{
                    UpdateCardBelongs($username, $existed_num);
                }
                //$BreezeCard = $BreezeCard === "" ? $existed_num : $BreezeCard;
            }
            else if ($breezecard_choice === "New"){
                $BreezeCard = generateCardnum();
                InsertBreezecard($username, $BreezeCard);
            }

            Redirect("sign_in.php");
          }
        }
     }

     function generateCardnum(){
        $new_card = mt_rand(10000000,99999999).mt_rand(10000000,99999999);
        if (checkvalidexisted($new_card) !== null){
            generateCardnum();
        }
        return $new_card;
     }

    function validateInputs($username, $password, $confirmPassword, $email, $breezecard_choice, $existed_num) {
        $existed = Get_Passenger_info();

        for ($i = 0; $i < count($existed); $i++){
            if ($email === $existed[$i]['Email']){
                echo "<script type='text/javascript'>alert('This E-mail address has been used');</script>";
                return FALSE;
            }
            else if($username === $existed[$i]['Username']){
                echo "<script type='text/javascript'>alert('This Username has been used');</script>";
                return FALSE;
            }
        }

        if (strpos($email, ".") === false){
            echo "<script type='text/javascript'>alert('E_mail address without . ');</script>";
            return FALSE;
        }
        else if (strpos($email, ".") - strpos($email,"@") < 0){
            echo "<script type='text/javascript'>alert(' . should be placed after @');</script>";
            return FALSE;
        }
        else if(strpos($email, ".") - strpos($email,"@") === 1){
            echo "<script type='text/javascript'>alert('characters should exist between @ and . ');</script>";
            return FALSE;
        }

        if ($username === "" || $password === "" || $email === "" || ($breezecard_choice === "Old" && strlen($existed_num) !== 16) || ($breezecard_choice != "Old" && $breezecard_choice != "New")){
            echo "<script type='text/javascript'>alert('Invalid Register Information');</script>";
            return FALSE;
        }
        if ($password !== $confirmPassword || strlen($password) < 8){
            echo "<script type='text/javascript'>alert('Confirm Password does not match');</script>";
            return FALSE;
        }
        if (checkvalidexisted($existed_num) === null && $breezecard_choice === "Old" && $existed_num != ""){
            echo "<script type='text/javascript'>alert('Card Number Already Existed');</script>";
            return FALSE;
        }
        return TRUE;
    }

?>



<!DOCTYPE html>
<!-- saved from url=(0050)https://v4-alpha.getbootstrap.com/examples/signin/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="./sign_in_files/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./sign_in_files/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="#" method="post">
        <h2 class="form-signin-heading">Please Register</h2>

        <label for="username" class="sr-only">User Name</label>
        <input type="text" name="username" class="form-control" placeholder="UserName" required="" autofocus="">

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="inputPassword" class="form-control" placeholder="Password" required="">

        <label for="ConfirmPassword" class="sr-only">Comfirm Password</label>
        <input type="password" name="ConfirmPassword" class="form-control" placeholder="ConfirmPassword" required="">

        <input type="radio" name="BreezeCard" value="Old">Use my existing BreezeCard<br>

        <label for="CardNumber" class="sr-only">Card Number</label>
        <input type="text" name="CardNumber" class="form-control" placeholder="CardNumber">

        <input type="radio" name="BreezeCard" value="New">Get a new BreezeCard<br>

        <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Create Account</button>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="login">Have an Account</button>
      </form>

    </div>



    <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./sign_in_files/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
        login.addEventListener("click",function(){
            window.location.href = 'sign_in.php';
        });
    </script>


</body></html>