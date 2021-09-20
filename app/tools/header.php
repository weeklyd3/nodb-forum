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
	<base href="../../" />
    <title>Forums &mdash; Tools</title>
	<?php
	include_once('../../styles/inject.php');
	include_once(__DIR__ . '/../../libraries/lib.php');
	if (isset($_COOKIE['login'])) {
		if (!verifyAdmin()) {
			echo 'You need to have admin permission. Try mailing one of these admins:<ul>';
			$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'));
			foreach ($config->admins as $a) {
				?><li><?php echo htmlspecialchars($a); ?></li><?php
			}
			echo '</ul>';
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
	<small><small><em>Only admins may view this page.</em></small></small>
	<nav style="display:flex; flex-wrap:nowrap;overflow-x:scroll;justify-content:center;">
	<div style="margin:2px;"><a href="app/tools">Home</a></div>
	<div style="margin:2px;"><a href="app/tools/admins.php">Admins</a></div>
	<div style="margin:2px;"><a href="app/tools/users.php">Users</a></div>
	<div style="margin:2px;"><a href="app/tools/topics.php">Topics</a></div>
	<div style="margin:2px;"><a href="app/tools/files.php">Files</a></div>
	<div style="margin:2px;"><a href="app/tools/edit_tos.php">Edit TOS</a></div>
	<div style="margin:2px;"><a href="app/tools/banner.php">Banners</a></div>
	<div style="margin:2px;"><a href="app/tools/flags.php">Flags</a></div>
	<?php 
		if (file_exists(__DIR__ . '/../../extensions/nodb-forum-ban-appeal/index.php')) {
			echo '<div style="margin:2px;"><a href="app/tools/appeals.php">Appeals</a></div>';
		}
	?>
	<div style="margin:2px;"><a href="."><strong>Log Out</strong></a></div>
	</nav>