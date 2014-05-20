<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<?php
include("header.php");
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] === 0)
	header("Location: login2.php");
?>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">

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
<?php
include("navbar.php");

function print_pics($id, $user_name, $content)
{
	if ($content->user->id == $id)
	{
		foreach ($content->thumbnails as $thumb)
		{
			if ($thumb->type == "low")
			{
				$url = $thumb->uri;
				break ;
			}
		}
		echo '<div class="col-lg-4" style="margin-top: 10px">';
		echo '<img src="'.$url.'"></img>';
		echo "</div>";
	}
}

$friend_js = file_get_contents('https://api.streamnation.com/api/v1/friends', false, $context);
$friend = json_decode($friend_js);
foreach ($friend->friends as $frd)
	$friends[] = $frd->id;

$resultat = mysqli_query($con, "SELECT * FROM User");
if (mysqli_num_rows($resultat) == 0)
	echo "Il n'y a aucun utilisateur pour le moment.";
else
{
	while ($donne = mysqli_fetch_assoc($resultat))
	{
		$data = http_build_query(
		array(
				'auth_token' => $donne['auth'],
				'size' => 10000
			)
		);

		$opts = array(
				'http'=>array(
				'method'=>'GET',
				'header'=>"Content-Type: application/x-www-form-urlencoded",
				'content' => $data
			)
		);

		$context = stream_context_create($opts);
		$user_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
		$user = json_decode($user_js);
		$pic_js = file_get_contents('https://api.streamnation.com/api/v1/photo_video', false, $context);
		$pic = json_decode($pic_js);
		foreach ($pic->results as $result)
		{
			foreach ($result->contents as $content)
			{
				if ($content->user->id == $donne['id_stream'])
					print_pics($donne['id_stream'], $user->user->first_name, $content);;
			}
		}
	}
	mysqli_free_result($resultat);
}
mysqli_close($con);

?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
          <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p style="color:white">&copy; 2014 ael-kadh, mle-roy & jyim. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
      </body>
      </html>
