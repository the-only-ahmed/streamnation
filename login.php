<?php include("header.php"); ?>
<?php

session_start();

$login = $_POST['login'];
$pass = $_POST['passwd'];

$data = http_build_query(
        array(
              'identity' => $login,
              'password' => $pass
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
$user_js = file_get_contents('https://api.streamnation.com/api/v1/auth', false, $context);
$user = json_decode($user_js);

if (!$user)
{
	echo "Wrong User/Password";
	header("Location: login2.php");
}
else
{
	$_SESSION['logged'] = 1;
	$_SESSION['auth'] = $user->auth_token;
	$_SESSION['id'] = $user->user->id;
/*-----------------------------------------------------------------------------*/

$x = $user->user->id;

$resultat = mysqli_query($con, "SELECT * FROM User WHERE id_stream='$x'");

	if (mysqli_num_rows($resultat) === 0)
	{
		$req_pre = mysqli_prepare($con, 'INSERT INTO User (id_stream, auth, email) VALUES (?, ?, ?)');
			mysqli_stmt_bind_param($req_pre, 'iss', $user->user->id, $user->auth_token, $user->user->email);
			mysqli_stmt_execute($req_pre);
	}
	header("Location: index.php");
}
mysqli_close($con);
?>
