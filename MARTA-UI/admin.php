<!DOCTYPE html>
<!-- saved from url=(0057)https://v4-alpha.getbootstrap.com/examples/justified-nav/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Justified Nav Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="./sign_in_files/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

  </head>

  <body>

    <div class="container">

      <div class="masthead">
        <h3 class="text-muted">CS4400 Group67</h3>


      </div>

      <!-- Jumbotron -->
      <div class="jumbotron">
        <h1>Administrator</h1>
      </div>

      <!-- Example row of columns -->
      <form action="admin.php" method="post">
      <div class="row">
        <div class="col-lg-4">
          <h2>Station Management</h2>
          <p><a class="btn btn-primary" href = "Station_Management.php" role="button" name="station_manage_ment">Station Management</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Suspended Cards</h2>
          <p><a class="btn btn-primary" role="button" href="suspended_cards.php" name="suspend_cards">Suspended Cards</a></p>
       </div>
      </div>
      </form>


      <!-- Example row of columns -->
      <form action="admin.php" method="post">
      <div class="row">
        <div class="col-lg-4">
          <h2>Breeze Card Management</h2>
          <p><a class="btn btn-primary" href="Breezecard_Management.php" role="button" name="breeze_card_management">Breeze Card Management</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Passenger Flow Report</h2>
          <p><a class="btn btn-primary"  href="Passenger_Flow_Report.php" role="button" name="passenger_flow_report">Passenger Flow Report</a></p>
       </div>
      </div>
      </form>

      <!-- Site footer -->
      <footer class="footer">
        <p>© Company 2017</p>
      </footer>

    </div> <!-- /container -->

    <form action="logout.php" method="post">
    <input type="submit" value="Log out" name="Log out">
    </form>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


</body></html>