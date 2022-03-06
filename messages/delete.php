<<<<<<< HEAD
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
    <title>Delete Message</title>
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
if (isset($_POST['delete'])) {
	unset($file->$message);
	fwrite(fopen('../data/accounts/' . cleanFilename($userid) . '/msg.json', 'w+'), json_encode($file));
	?><h2>Message deleted</h2><p>Please <a href="messages/">return to your inbox</a> to continue using Private Messages.</p><?php
	exit(0);
}
?><h2>Confirm Delete</h2>
	  <p>You will permanently lose your copy of this message. Other people may still have a copy.</p>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<label>Confirm delete:
		<input type="submit" name="delete" /></label>
=======
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
    <title>Delete Message</title>
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
if (isset($_POST['delete'])) {
	unset($file->$message);
	fwrite(fopen('../data/accounts/' . cleanFilename($userid) . '/msg.json', 'w+'), json_encode($file));
	?><h2>Message deleted</h2><p>Please <a href="messages/">return to your inbox</a> to continue using Private Messages.</p><?php
	exit(0);
}
?><h2>Confirm Delete</h2>
	  <p>You will permanently lose your copy of this message. Other people may still have a copy.</p>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<label>Confirm delete:
		<input type="submit" name="delete" /></label>
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
</form>