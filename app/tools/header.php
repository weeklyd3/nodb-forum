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
	<base href="../../" />
    <title>Forums &mdash; Tools</title>
	<?php
	include('../../styles/inject.php');
	?>
	<!--<?php include('../../public/header.php'); ?>-->
	<?php
	if ($_COOKIE['login']) {
		if (getname() != 'admin') {
			echo 'You need to be logged in as admin to continue.';
			exit(0);
		}
	} else {
		echo 'This feature is not available to anonymous users.';
		exit(0);
	}
	?>
  </head>
  <body>
	<h1>Moderation Tools</h1>
	<small><small><em>Only the account <code>admin</code> may view this page.</em></small></small>
	<nav style="display:flex; flex-wrap:nowrap;overflow-x:scroll;justify-content:center;">
	<div style="margin:2px;"><a href="app/tools">Home</a></div>
	<div style="margin:2px;"><a href="app/tools/users.php">Users</a></div>
	<div style="margin:2px;"><a href="app/tools/topics.php">Topics</a></div>
	<div style="margin:2px;"><a href="app/tools/files.php">Files</a></div>
	<div style="margin:2px;"><a href="app/tools/ban.php">User Bans</a></div>
	<div style="margin:2px;"><a href="app/tools/edit_tos.php">Edit TOS</a></div>
	<div style="margin:2px;"><a href="."><strong>Log Out of Moderation Tools</strong></a></div>
	</nav>