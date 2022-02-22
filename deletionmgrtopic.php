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
		if (!isset($_GET['topic'])) {
			die("Cannot find topic: No topic GET parameter specified.</p><p>You cannot access the Deletion Manager because you did not specify a topic.</p>");
		}
		if (!is_dir('data/messages/' . cleanFilename($_GET['topic']))) {
			die("Cannot find topic: Invalid topic name.</p><p>You cannot access the Deletion Manager because you did not provide a valid topic name.</p>");
		}
		$config = json_decode(file_get_contents('data/messages/' . cleanFilename($_GET['topic']) . '/config.json'));
		$msg = json_decode(file_get_contents('data/messages/' . cleanFilename($_GET['topic']) . '/msg.json'), true);
	$author = $config->author;
	$auth = (($author === getname()) && count($msg) === 0) || verifyAdmin();
	if (!$auth) {
		die("Not authorized! Please verify that either you are the owner of the topic and 0 comments have been posted to the topic or that you are an administrator.</p>");
	}
		
		?>
		You have been authorized.</p>
	  <?php 
		if (isset($_POST['go4topic'])) {
			if (file_exists('data/messages/' . cleanFilename($_GET['topic']) . '/del.json')) {
				unlink('data/messages/' . cleanFilename($_GET['topic']) . '/del.json');
			} else {
				$del = new stdClass;
				$del->time = time();
				$del->user = getname();
				$del->reason = $_POST['reason'];
				$del->extendedReason = $_POST['extendedReason'];
				fwrite(fopen('data/messages/' . cleanFilename($_GET['topic']) . '/del.json', 'w+'), json_encode($del));
			}
		}
		?>
	  <h3>Current deletion status</h3>
	  <p><b><?php echo htmlspecialchars($config->title); ?></b> is currently <?php echo file_exists('data/messages/' . cleanFilename($_GET['topic']) . '/del.json') ? "" : "not "; ?>deleted.</p>
	  <h3><?php echo file_exists('data/messages/' . cleanFilename($_GET['topic']) . '/del.json') ? "Und" : "D"; ?>elete this topic</h3>
	  <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
<?php 
if (!file_exists('data/messages/' . cleanFilename($_GET['topic']) . '/del.json')) {
	?><label>Select deletion reason: <select name="reason"><?php deletionReasons(); ?></select></label><br />
	<label>Type more details: <input type="text" name="extendedReason" /></label>	  
<br /><?php
}
?>
		  <input name="go4topic" type="submit" value="Confirm and <?php echo file_exists('data/messages/' . cleanFilename($_GET['topic']) . '/del.json') ? "Und" : "D"; ?>elete this topic" />
	  </form>
	  <p>Return to <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($_GET['topic'])); ?>">topic</a>.</p>
  </body>