<!DOCTYPE html>
    <div class="navbar-wrapper">
      <div class="container">

        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Streaming Community</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Find<b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="all_movies.php">Find Movies</a></li>
                    <li><a href="all_series.php">Find TvShows</a></li>
                    <li><a href="all_media.php">Find Pics or videos</a></li>
                  </ul>
                </li>
<?php      if (isset($_SESSION['logged']) && $_SESSION['logged'] === 1)
                echo '<li><a color="green" href="logout.php">Log out </a></li>';
            else
                echo '<li><a color="green" href="login2.php">Log In</a></li>';
                ?>
                <li><a href="https://www.streamnation.com/">StreamNation</a></li>

          <form action="search.php" class="navbar-form navbar-right" method="POST">
               <select id="test" name="media">
                    <option value="1">Movies</option>
                    <option value="2">Shows</option>
                    <option value="3">Videos/Pictures</option>
             </select>
            <input type="text" class="form-control" placeholder="Search..." name="search" required>

          </form>


              </ul>
            </div>
          </div>
        </div>
