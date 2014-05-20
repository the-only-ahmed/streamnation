<!DOCTYPE html>
<?php
session_start();

include("header.php");
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
  </head>
<!-- NAVBAR
================================================== -->
  <body>
<?php
include("navbar.php");
?>

      </div>
    </div>


    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active">
          <img data-src="holder.js/900x500/auto/#777:#7a7a7a/text:First slide" src="pub1.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Share contents with friends and family</h1>
            </div>
          </div>
        </div>
        <div class="item">
          <img data-src="holder.js/900x500/auto/#666:#6a6a6a/text:Second slide" src="pub2.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Find more friends with the sames interest</h1>
            </div>
          </div>
        </div>
        <div class="item">
          <img data-src="holder.js/900x500/auto/#555:#5a5a5a/text:Third slide" src="pub3.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Share with everyone !</h1>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
    <div class="container marketing">
    <div class="row">
<?php
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
    echo '<div class="container marketing">';
      echo '<div class="row">';
//echo "The best Show streamer is ".$show->user->first_name."</br>";
       echo '<div class="col-lg-4">';
      echo '<img class="img-circle" data-src="holder.js/140x140" src="show.jpg" alt="Generic placeholder image">';
      echo '<h2>Best Show Streamer</h2>';
      echo '<h1 style="color:blue">'.$show->user->first_name.'</h1>';
          echo '<p><a class="btn btn-default" href="best_show.php?id='.$show->user->id.'" role="button">View details &raquo;</a></p>';
        echo '</div><!-- /.col-lg-4 -->';

/************************************************************************************************/

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
$movie_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$movie = json_decode($movie_js);

/*echo "The best Movie streamer is ".$show->user->first_name."</br>";*/
        echo '<div class="col-lg-4">';
          echo '<img class="img-circle" data-src="holder.js/140x140" src="cine.jpg" alt="Generic placeholder image">';
          echo '<h2>Best Movie Streamer</h2>';
          echo '<h1 style="color:blue">'.$movie->user->first_name.'</h1>';
          echo '<p><a class="btn btn-default" href="best_movie.php?id='.$movie->user->id.'" role="button">View details &raquo;</a></p>';
        echo '</div><!-- /.col-lg-4 -->';

/****************************************************************************************************/

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
$pic_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$pic = json_decode($pic_js);
$user_js = file_get_contents('https://api.streamnation.com/api/v1/user/me', false, $context);
$user = json_decode($user_js);

/*echo "The best Videos/Photos streamer is ".$show->user->first_name."with ".$win."</br>";*/

mysqli_close($con);

        echo '<div class="col-lg-4">';
          echo '<img class="img-circle" data-src="holder.js/140x140" src="Camera.jpg">';
          echo '<h2>Best Photo/Video Streamer</h2>';
          echo '<h1 style="color:blue">'.$pic->user->first_name.'</h1>';
          $auth = $user->auth_token;
          echo '<p><a class="btn btn-default" href="best_pic.php?id='.$pic->user->id.'&auth='.$auth.'" role="button">View details &raquo;</a></p>';
        echo '</div><!-- /.col-lg-4 -->';
?>

      </div><!-- /.row -->
      </div>

<!-- ************************************************************************************************ -->

<!--
      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">First featurette heading. <span class="text-muted">It'll blow your mind.</span></h2>
          <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="Generic placeholder image">
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="Generic placeholder image">
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Oh yeah, it's that good. <span class="text-muted">See for yourself.</span></h2>
          <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">And lastly, this one. <span class="text-muted">Checkmate.</span></h2>
          <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
        </div>
        <div class="col-md-5">
          <img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="Generic placeholder image">
        </div>
      </div>

      <hr class="featurette-divider"> -->

      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 ael-kadh, mle-roy & jyim. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
  </body>
</html>
