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
	<base href="../" />
    <title>Installation</title>
	<?php
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
		if (!isset($_POST['title'])) die("Start from installation page first");
		if (file_exists('../config.json')) {
			header('Location: ..');
		}
		$title = $_POST['title'];
		class Installation {
			public $forumtitle = "default";
			public $tags = array();
			public $creationTime = null;
		}
		$install = new Installation;
		$install->forumtitle = $title;
		$install->tags = explode(" ", $_POST['tags']);
		$install->creationTime = time();
		$json = json_encode($install);
		$handle = fopen("../config.json", 'w+');
		$status = fwrite($handle, $json);
		fclose($handle);
		if ($status) {
			echo "> Installed!";
		}
	?>
	<div>Tags:
	<?php 
		$tags = explode(" ", $_POST['tags']);
		foreach ($tags as $key => $value) {
			?><span class="tag"><?php echo htmlspecialchars($value); ?></span> <?php
		}
	?>
	</div>
	<h1>Congratulations!</h1>
	<p>If you see <code>> Installed!</code> above, you have successfully set up your discussion board!</p>
	<p>You can now:
	<form action="javascript:void(window.location.assign('app/'+document.getElementById('which').value));">
	<select id="which">
		<option value="../account/signup.php">Create the first user account</option>
		<option value="../webchat.php">Eavesdrop in the chat room</option>
		<option value="uninstall.php">Uninstall</option>
		<option value="../">Go to home page</option>
	</select>
	<input type="submit" value="Go!" />
	</form>
	<h2>Invite users!</h2>
	An active community is critical to your forum's success.<br /><br />
	You can <a href="mailto:?subject=This might interest you&body=You may want to chat here: http://<?php echo $_SERVER['HTTP_HOST'];?>">invite users by e-mail.</a>