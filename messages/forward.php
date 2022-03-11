<?php
/*
    Forum Software
    Copyright (C) 2022 contributors

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
    <title>Forward Message</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	$userid = getname() ? getname() : null;
	$message = isset($_GET['id']) ? $_GET['id'] : null;
	$file = json_decode(file_get_contents('../data/accounts/' . cleanFilename($userid) . '/msg.json'));
if (!isset($file->$message)) {
	?><h2>Error:</h2><p>Your message could not be found.</p><?php
	exit(0);
}
	$msg = $file->$message;
?>
<h2>Forward Message</h2>
	  <p>Only forward this message if it contains non-sensitive information.</p>
<ul>
	<li>Click <kbd>Review Message</kbd> to go to the "Compose Message" form with the body and subject filled out.</li>
	<li>Click <kbd>Send Message Now</kbd> to skip the "Compose Message" form and send the message directly.</li>
</ul>
<form action="messages/new.php" method="post">
	<label>Enter recipients separated by newlines:<br />
		<textarea name="to" rows="3" cols="30"></textarea></label><br />
	<label hidden="hidden">Do not touch! <input name="subject" value="Fwd: <?php echo htmlspecialchars($msg->subject); ?>" /></label>
	<label hidden="hidden">Do not touch!<textarea name="body"><?php
echo '--- Forwarded Message ---';
echo "  \nFrom: " . htmlspecialchars($msg->from);
echo "  \nTo: " . htmlspecialchars(implode(', ', $msg->people));
echo "  \nDate: " . htmlspecialchars(date('l, F m, Y \a\t g:i:s A', $msg->time));
echo "  \nSubject: " . htmlspecialchars($msg->subject);
echo "\n\n";
echo htmlspecialchars($msg->body);
echo "  \n--- End Forwarded Message ---";
?></textarea></label>
	<input type="submit" value="Review message" />
	<input type="submit" name="send" value="Send message now" />
</form>