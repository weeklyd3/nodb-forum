<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><html lang="en">
  <head>
    <title>Manage Deletion</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
	  <h2>Manage Deletion</h2>
	  <p>Checking permissions...</p>
	  <p><?php 
		if (!isset($_GET['topic'], $_GET['post'])) {
			?>Double-check that you specified both a topic and post.</p><?php
			exit(0);
		}
	$topicpath = __DIR__ . "/data/messages/" . cleanFilename($_GET['topic']) . '/msg.json'; 
if (!file_exists($topicpath)) {
	die("Bad title");
}
$msgs = json_decode(file_get_contents($topicpath));
$post = $_GET['post'];
if (!isset($msgs->$post)) {
	die("Bad post");
}
$msg = $msgs->$post;
$eligibleForSeeing = verifyAdmin() || (getname() === $msg->author);
if (!$eligibleForSeeing) {
	die("You have not been authorized.");
}
?>
<p>You are authorized.</p>
<h2>Status</h2>
<p>This post is currently <?php
	// Drum roll...
	echo isset($msg->del) ? "" : "not "
?>deleted.</p>
<h2>Change Deletion Status</h2>
	  <?php 
if (isset($_POST['go4post'])) {
	$del = new stdClass;
	$del->time = time();
	$del->user = getname();
	$del->reason = $_POST['reason'];
	$del->extendedReason = $_POST['extendedReason'];
	if (isset($msg->del)) unset($msg->del);
	else $msg->del = $del;

	$msgs->$post = $msg;
	fwrite(fopen($topicpath, 'w+'), json_encode($msgs));
}
?>
	  	<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">

<?php 
if (!isset($msg->del)) {
	?><label>Select deletion reason: <select name="reason"><?php deletionReasons(); ?></select></label><br />
	<label>Type more details: <input type="text" name="extendedReason" /></label>	  
<br /><?php
}
?>
		  <input name="go4post" type="submit" value="Confirm and <?php echo isset($msg->del) ? "Und" : "D"; ?>elete this post" />
	</form>
<a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($_GET['topic'])); ?>#topic-message-<?php echo htmlspecialchars(urlencode($post)); ?>">Return to post?</a>