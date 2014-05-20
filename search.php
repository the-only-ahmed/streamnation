<!DOCTYPE html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<?php include("header.php"); ?>
<?php
session_start();

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
<?php
include("navbar.php");
?>
<div>
<?php

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
			echo '<button type="button" class="btn btn-success"><a href="add_friend?id='.$id.'&auth='.$_SESSION['auth'].'">add friend</a></button>';
		else if ($id === -1)
			echo '<button type="button" class="btn btn-danger">already friend</button>';
		echo '</div>';
	echo '</div>';
echo '</div>';
echo '</div>';
}

function go_movies($me, $id, $name, $auth, $friends)
{

$data = http_build_query(
	array(
			'auth_token' => $auth,
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
	static $i;
	$context = stream_context_create($opts);
	$mov_js = file_get_contents('https://api.streamnation.com/api/v1/movies', false, $context);
	$mov = json_decode($mov_js);
	foreach ($mov->movies as $movie)
	{
		if (stristr($movie->title, $name) && $movie->contents[0]->user->id == $id)
		{
			$user_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
			$user = json_decode($user_js);
			if ($id == $me)
				$id = -2;
			else if (array_search($id, $friends))
				$id = -1;
			print_movie($id, $user->user->first_name, $movie);
			$bol = true;
		}
	}
	return $bol;
}

/*------------------------------------------------------------------------------*/

function print_shows($id, $user_name, $serie)
{
	foreach ($serie->covers as $i => $cover)
	{
		if ($cover->type === "medium")
			break;
	}
	$descp = substr($serie->description, 0, 300);
	if (strlen($serie->description) > 300)
		$descp = $descp."...";
		echo '<div id="block">';
echo '<div class="row placeholders">';
	echo '<div class="col-xs-4">';
	echo '<img src="'.$serie->covers[$i]->uri.'">';
	echo '</div>';
	echo '<div class="col-xs-8">';
		echo '<h2>'.$serie->title.'</h2>';

		foreach ($serie->seasons as $season)
		{
			foreach ($season->episodes as $episode)
			{
				echo '<h4>Saison: '.$episode->season_number.'</h4>';
				echo '<h4>Episode: '.$episode->episode_number.'</h4>';
				echo '<h4>Title: '.$episode->title.'</h4>';
			}
		}

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

function go_shows($me, $id, $name, $auth, $friends)
{

$data = http_build_query(
	array(
			'auth_token' => $auth,
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
	$show_js = file_get_contents('https://api.streamnation.com/api/v1/shows', false, $context);
	$shows = json_decode($show_js);
	foreach ($shows->shows as $serie)
	{
		if (stristr($serie->title, $name) && $serie->seasons[0]->episodes[0]->contents[0]->user->id == $id)
		{
			$user_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
			$user = json_decode($user_js);
			if ($id == $me)
				$id = -2;
			else if (array_search($id, $friends))
				$id = -1;
			print_shows($id, $user->user->first_name, $serie);
			$bol = true;
		}
	}
	return $bol;
}

/*------------------------------------------------------------------------------*/

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

function go_pics($me, $id, $name, $auth, $friends)
{

$data = http_build_query(
	array(
			'auth_token' => $auth,
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
	$pics_js = file_get_contents('https://api.streamnation.com/api/v1/photo_video', false, $context);
	$pics = json_decode($pics_js);
	foreach ($pics->results as $result)
	{
		foreach ($result->contents as $content)
		{
			$usr = $content->user->first_name;
			$place = $content->metadata->poi;
			$model = $content->metadata->model;
			$tag = $content->metadata->tags->tag;
			$date = $content->metadata->creation_date;
			$title = $content->title;
			$people = $content->metadata->tags->people;
			if ((stristr($place, $name) || stristr($model, $name) || stristr($tag, $name) || stristr($date, $name) ||
					stristr($title, $name) || stristr($people, $name) || stristr($usr, $name)) && $result->contents[0]->user->id == $id)
			{
				if ($id == $me)
					$id = -2;
				else if (array_search($id, $friends))
					$id = -1;
				print_pics($id, $user->user->first_name, $content);
				$bol = true;
			}
		}
	}
	return $bol;
}

/*------------------------------------------------------------------------------*/

$media = $_POST['media'];
$key = $_POST['search'];

$data = http_build_query(
	array(
			'auth_token' => $_SESSION['auth']
		)
	);

$opts = array(
		'http'=>array(
		'method'=>'GET',
		'header'=>"Content-Type: application/x-www-form-urlencoded",
		'content' => $data
	)
);

$friends = array();

	$context = stream_context_create($opts);
	$friend_js = file_get_contents('https://api.streamnation.com/api/v1/friends', false, $context);
	$friend = json_decode($friend_js);
	foreach ($friend->friends as $frd)
		$friends[] = $frd->id;

$resultat = mysqli_query($con, "SELECT id_stream, auth FROM User");
while ($donne = mysqli_fetch_assoc($resultat))
{
	if ($media == 1)
		$bol = go_movies($_SESSION['id'], $donne['id_stream'], $key, $donne['auth'], $friends);
	else if ($media == 2)
		$bol = go_shows($_SESSION['id'], $donne['id_stream'], $key, $donne['auth'], $friends);
	else if ($media == 3)
		$bol = go_pics($_SESSION['id'], $donne['id_stream'], $key, $donne['auth'], $friends);
}

	if (!isset($bol))
	{
		echo '<div id="block">';
			echo '<div class="row placeholders">';
				echo '<div class="col-xs-8">';
					echo '<center><h2>Result not found !</h2></center>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

mysqli_free_result($resultat);
mysqli_close($con);
?>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
          <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p style="color:white">&copy; 2014 ael-kadh, mle-roy & jyim. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
  </body>
</html>
