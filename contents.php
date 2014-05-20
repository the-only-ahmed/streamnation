<?php include("header.php"); ?>
<?php
session_start();

$best_movie = array();
$best_pic = array();
$best_show = array();

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
			'size' => 1000
		)
	);

$opts = array(
		'http'=>array(
		'method'=>'GET',
		'header'=>"Content-Type: application/x-www-form-urlencoded",
		'content' => $data
	)
);

	$nb_ser = 0;
	$nb_mv = 0;
	$nb_pic = 0;
/*------------------------------------------------------------------------------*/
	$context = stream_context_create($opts);
	$show_js = file_get_contents('https://api.streamnation.com/api/v1/shows', false, $context);
	$show = json_decode($show_js);
	foreach ($show->shows as $serie)
	{
		foreach ($serie->seasons as $saison)
		{
			foreach ($saison->episodes as $ep)
			{
				foreach ($ep->contents as $content)
				{
					if ($content->user->id == $donne['id_stream'])
						$nb_ser++;
				}
			}
		}
	}

	$best_show[$donne['id_stream']] = $nb_ser;
/*------------------------------------------------------------------------------*/

		$mov_js = file_get_contents('https://api.streamnation.com/api/v1/movies', false, $context);
		$mov = json_decode($mov_js);
		foreach ($mov->movies as $movie)
		{
			foreach ($movie->contents as $content)
			{
				if ($content->user->id == $donne['id_stream'])
					$nb_mv++;
			}
		}
		$best_movie[$donne['id_stream']] = $nb_mv;

	}

	mysqli_free_result($resultat);
}

/*------------------------------------------------------------------------------*/

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

/*------------------------------------------------------------------------------*/

		$pic_js = file_get_contents('https://api.streamnation.com/api/v1/photo_video', false, $context);
		$pic = json_decode($pic_js);
		foreach ($pic->results as $result)
		{
			foreach ($result->contents as $content)
			{
				if ($content->user->id == $donne['id_stream'])
					$nb_pic++;
			}
		}

		$best_pic[$donne['id_stream']] = $nb_pic;

/*------------------------------------------------------------------------------*/
$win = -1;

foreach ($best_show as $i => $show)
{
	if ($show > $win)
	{
		$win = $show;
		$id = $i;
	}
}

$resultat = mysqli_query($con, "SELECT auth FROM User WHERE id_stream='$id'");
$donnee = mysqli_fetch_assoc($resultat);

$data = http_build_query(
	array(
			'auth_token' => $donnee['auth']
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
$show_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$show = json_decode($show_js);

echo "The best Show streamer is ".$show->user->first_name."</br>";

/*-------------------------------------------------------------------------------*/

$win = -1;

foreach ($best_movie as $i => $movie)
{
	if ($movie > $win)
	{
		$win = $movie;
		$id = $i;
	}
}

$resultat = mysqli_query($con, "SELECT auth FROM User WHERE id_stream='$id'");
$donnee = mysqli_fetch_assoc($resultat);

$data = http_build_query(
	array(
			'auth_token' => $donnee['auth']
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
$show_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$show = json_decode($show_js);

echo "The best Movie streamer is ".$show->user->first_name."</br>";

/*-------------------------------------------------------------------------------*/

$win = -1;

foreach ($best_pic as $i => $pic)
{
	if ($pic > $win)
	{
		$win = $pic;
		$id = $i;
	}
}

$resultat = mysqli_query($con, "SELECT auth FROM User WHERE id_stream='$id'");
$donnee = mysqli_fetch_assoc($resultat);

$data = http_build_query(
	array(
			'auth_token' => $donnee['auth']
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
$show_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$show = json_decode($show_js);

echo "The best Videos/Photos streamer is ".$show->user->first_name."with ".$win."</br>";

mysqli_close($con);
?>
