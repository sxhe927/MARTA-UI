<?php session_start();
    ob_start();
?>
<?php
    include "redirect.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])){
        include "db_info.php";
        $username = $_POST['username'];
        $password = hash(md5,$_POST['Password']);
        $UserRow = sign_in($username, $password);
        if ($UserRow !== NULL && $UserRow[0]["IsAdmin"] === "1"){
            $_SESSION['user'] = array('name' => $UserRow[0]["Username"]);
            $_SESSION['usertype'] = 1;
            Redirect("admin.php");
        }
        else if ($UserRow !== NULL && $UserRow[0]["IsAdmin"] === "0"){
            $_SESSION['user'] = array('name' => $UserRow[0]["Username"]);
            $_SESSION['usertype'] = 0;
            Redirect("Welcome_to_Passengers.php");
        }
        else{
            echo "<script type='text/javascript'>alert('Incorrect sign in informationc-u');</script>";
        }
    }

?>

<!DOCTYPE html>
<!-- saved from url=(0050)https://v4-alpha.getbootstrap.com/examples/signin/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">
    <title>Signin Template for Bootstrap</title>
    <link href="./sign_in_files/bootstrap.min.css" rel="stylesheet">
    <link href="./sign_in_files/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="#" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="username" class="sr-only">User Name</label>
        <input type="text" name="username" class="form-control" placeholder="UserName" required="">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="Password" class="form-control" placeholder="Password" required="">
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Sign in</button>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="signup">Register</button>

      </form>

    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./sign_in_files/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
        signup.addEventListener("click", function(){
            window.location.href = 'register.php';
        })
    </script>

</body></html>