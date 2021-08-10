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
?><html>
  <head>
    <base href=".." />
    <title>Forums &mdash; My Account</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	if ($_COOKIE['login']) {
		echo "<h2>".htmlspecialchars(getname()).'</h2>';
	?>
	<details open="open">
		<summary>Account Options</summary>
		<button onclick="window.location.assign('account/changepass.php');">Change Password</button><br />
		You can edit your user profile:
		<form action="account/edituser.php" method="get"><input type="submit" value="Edit" /></form>You can view your user profile:
		<form action="account/viewuser.php" method="get"><input type="hidden" name="user" value="<?php echo htmlspecialchars(getname()); ?>" /><input type="submit" value="View profile" /></form></details>
	<?php
	} else {
		echo "You are not logged in.";
	}
	include('../public/footer.php'); ?>