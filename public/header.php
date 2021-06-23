<?php
error_reporting(0);
echo '<table style="width:100%; position:sticky; top:0; background-color:blue;z-index:1;">';

echo "<tr><td><h1 style=\"display:inline;\"><a style=\"text-decoration:none !important;\" href=\"./\"><img alt=\"Forum Logo\" src=\"./img/logo.png\" />Forums <small><small>Beta</small></small></a></h1></td>";
$login = $_COOKIE['login'];
echo '<td><a href="explorer/">source code</a></td>';
if ($login == "") {
	echo '<td><a href="account/signup.php">sign up</a></td>';
	echo '<td><a href="account/login.php">log in</a></td>';
} else {
	echo '<td>Logged in as: '.$login.'</td>';
	echo '<td><a href="webchat.php">web chat</a> <a href="account/logout.php">(log out)</a></td>';
}
echo "</table>";
?>