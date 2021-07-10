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
		echo '<details open="open">';
		echo '<summary>Account Options</summary>';
		echo '<button onclick="window.location.assign(\'account/changepass.php\');">Change Password</button></details>';
	} else {
		echo "You are not logged in.";
	}
	include('../public/footer.php'); ?>