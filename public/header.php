<?php
include($_SERVER["DOCUMENT_ROOT"] . '/libraries/lib.php');
error_reporting(0);
echo '<table style="width:100%; position:sticky; top:0; background-color:blue;z-index:1;">';
function getname() {
	$COOK = $_COOKIE['login'];
	$STATS = explode("\0", $COOK);
	$path = cleanFilename($STATS[0]);
	$path = $_SERVER['DOCUMENT_ROOT'].'/data/accounts/'.$path;
	$hash = file_get_contents($path . '/psw.txt');
	if (password_verify($STATS[1], $hash)) {
		$match = true;
	} else {
		header('Location: ./../invalidpass.php');
	}
	return $STATS[0];
}
echo "<tr><td><h1 style=\"display:inline;\"><span id=\"menubutton\" style=\"cursor:pointer;\">☰</span> <a style=\"text-decoration:none !important;\" href=\"./\"><img alt=\"Forum Logo\" src=\"./img/logo.png\" />Forums <small><small>Beta</small></small></a></h1></td>";
$login = $_COOKIE['login'];
echo '<td><a href="explorer/">source code</a></td>';
if ($login == "") {
	echo '<td><a href="account/signup.php">sign up</a></td>';
	echo '<td><a href="account/login.php">log in</a></td>';
} else {
	echo '<td>Logged in as: '.htmlspecialchars(getname()).'</td>';
	echo '<td><a href="webchat.php">web chat</a> (<a href="account/">Account Options</a> | <a href="account/logout.php">log out</a>)</td>';
}
echo "</table>";
?>
<div id="menu" style="max-width:100%; min-width:120px; width:400px; z-index:10; height:100%; background-color:#00dddd; display:none; position:fixed; top:0; left:0; overflow-y:scroll;">
<ul style="list-style:none; padding:7px;">
<p style="text-align:right;"><span style="text-align:left;">Menu</span> <span onclick="document.getElementById('menu').style.display='none';" style="cursor:pointer;">&times;</span></p>
<li><a href="./">Home</a></li>
<li><a href="./webchat.php">Chat room</a></li>
<br>
<li><a href="./account/">My Account</a></li>
<li><a href="./account/login.php">Log in to different account</a></li>
<li><a href="./account/signup.php">Sign up for account</a></li>
<br>
<li><a href="./data/messages/webchat.txt">Chat messages - raw</a></li>
<li><a href="./explorer">File Explorer</a></li>
<li><a href="https://github.com/weeklyd3/nodb-forum">GitHub repository</a></li>
</ul>
<center style="word-break:break-all;"><em><?php
$link = 'Location: ';
$link .= $_SERVER['HTTP_HOST'];
$link .= $_SERVER['REQUEST_URI'];
echo $link;
?></em></center>
</div>
<script>
document.getElementById('menubutton').addEventListener('click', function() {
	if (document.getElementById('menu').style.display=='none') {
		document.getElementById('menu').style.display = 'block';
	} else {
		document.getElementById('menu').style.display = 'none';
	}
});
window.addEventListener('click', function(e) {   
  if (!document.getElementById('menu').contains(e.target) && document.getElementById('menu').display == 'block') {
	  document.getElementById('menu').style.display = 'none';
  }
});
</script>