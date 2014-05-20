<?php
session_start();
if (isset($_SESSION['logged']))
{
	if ($_SESSION['logged'] === 1)
	{
		foreach ($_SESSION as $i => $elem)
			$_SESSION[$i] = NULL;
	}
}
header ("Location: index.php");
?>
