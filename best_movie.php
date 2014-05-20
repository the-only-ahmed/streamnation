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

function print_movie($id, $user_name, $movie)
{
	foreach ($movie->covers as $i => $cover)
	{
		if ($cover->type === "medium")
			break;
	}

	$descp = substr($movie->description, 0, 300);
	if (strlen($movie->description) > 300)
		$descp = $descp."...";
	echo '<div id="block">';
echo '<div class="row placeholders">';
	echo '<div class="col-xs-4">';
	echo '<img src="'.$movie->covers[$i]->uri.'">';
	echo '</div>';
	echo '<div class="col-xs-8">';
		echo '<h2>'.$movie->title.'</h2>';
		echo '<hr>';
		echo '<span class="text-muted">'.$descp.'</span>';
		echo '<br>';
		echo '<div id="salut">';
		echo '<span class="alert alert-info">Owner is '.$user_name.'</span>';
		if ($id >= 0)
			echo '<button type="button" class="btn btn-success"><a href="add_friend/?id='.$id.'&auth='.$_SESSION['auth'].'">add friend</a></button>';
		else if ($id === -1)
			echo '<button type="button" class="btn btn-danger">already friend</button>';
		echo '</div>';
	echo '</div>';
echo '</div>';
echo '</div>';
}

$id = $_GET['id'];

$resultat = mysqli_query($con, "SELECT auth, id_stream FROM User WHERE id_stream='$id'");
if (mysqli_num_rows($resultat) == 0)
	echo "Il n'y a aucun utilisateur pour le moment.";
else
{
	$donne = mysqli_fetch_assoc($resultat);
	$data = http_build_query(
			array(
					'auth_token' => $donne['auth']
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
			$movie_js = file_get_contents('https://api.streamnation.com/api/v1/movies', false, $context);
			$mov = json_decode($movie_js);


					$data = http_build_query(
		array(
				'auth_token' => $_SESSION['auth'],
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
		$friend_js = file_get_contents('https://api.streamnation.com/api/v1/friends', false, $context);
		$friend = json_decode($friend_js);
		foreach ($friend->friends as $frd)
			$friends[] = $frd->id;


			foreach ($mov->movies as $movie)
			{
				foreach ($movie->contents as $content)
				{
					if ($content->user->id == $donne['id_stream'])
					{
						if ($id == $_SESSION['id'])
							$id = -2;
						else if (array_search($id, $friends))
							$id = -1;
						print_movie($id, $user->user->first_name, $movie);
					}
				}
			}
	mysqli_free_result($resultat);
}
mysqli_close($con);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p style="color:white">&copy; 2014 ael-kadh, mle-roy & jyim. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
