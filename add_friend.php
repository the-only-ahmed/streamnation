<!DOCTYPE html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<?php
session_start();
include("header.php");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] === 0)
	header("Location: login2.php");

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Stream Community</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body id="color">
  <div>
<?php
include("navbar.php");

$auth = $_GET['auth'];
$id = $_GET['id'];

	$data = http_build_query(
		array(
				'auth_token' => $auth,
				'friend_id' => $id
			)
		);

	$opts = array(
			'http'=>array(
			'method'=>'POST',
			'header'=>"Content-Type: application/x-www-form-urlencoded",
			'content' => $data
		)
	);
	$context = stream_context_create($opts);
	$user_js = file_get_contents('https://api.streamnation.com/api/v1/friends/request', false, $context);
	$user = json_decode($user_js);

	echo "<H2 style='color:white'>Friend request has been send</H2>";
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    </div>
          <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 ael-kadh, mle-roy & jyim. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
</body>
</html>
