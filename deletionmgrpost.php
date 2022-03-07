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
		$topic = $_GET['topic'];
		$postName = $_GET['post'];
		$topicCleaned = cleanFilename($topic);
		$configaddr = __DIR__ . "/data/messages/$topicCleaned/config.json";
		if (!file_exists($configaddr)) die("Bad topic name.");
		$msgaddr = __DIR__ . "/data/messages/$topicCleaned/msg.json";
		$msgs = json_decode(file_get_contents($msgaddr));
		$config = json_decode(file_get_contents($configaddr));
		if (!isset($msgs->$postName)) die("Bad post name");
		$post = $msgs->$postName;
		if (!verifyAdmin() && $post->author !== getname()) die("Access denied");
		if (isset($_POST['go4post'])) {
			$delObj = new stdClass;
			$delObj->reason = $_POST['reason'] ?? "";
			$delObj->extendedReason = $_POST['extendedReason'] ?? "";
			$delObj->time = time();
			$delObj->user = getname();
			if (isset($post->del))  unset($post->del);
			else $post->del = $delObj;
			$msgs->$postName = $post;
			fwrite(fopen($msgaddr, 'w+'), json_encode($msgs));
		}
		?>
			  <h3>Current deletion status</h3>
	  <p>This post is currently <?php echo isset($post->del) ? "" : "not "; ?>deleted.</p>
	  <h3><?php echo isset($post->del) ? "Und" : "D"; ?>elete this post</h3>
	  <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
<?php 
if (!isset($post->del)) {
	?><label>Select deletion reason: <select name="reason"><?php deletionReasons(); ?></select></label><br />
	<label>Type more details: <input type="text" name="extendedReason" /></label>	  
<br /><?php
}
?>
		  <input name="go4post" type="submit" value="Confirm and <?php echo isset($post->del) ? "Und" : "D"; ?>elete this post" />
	  </form>
	  <p>Return to <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($_GET['topic'])); ?>#topic-message-<?php echo htmlspecialchars(urlencode($postName)); ?>">post</a>.</p>