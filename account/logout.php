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
    <title>Logging Out...</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<?php
	ob_start();
	echo '<em>Please wait...</em>';
	setcookie('login', false, time() + 72000, '/');
	setcookie('login', false, time() + 72000);
	unset($_COOKIE['login']);
	echo '<p>Logged out!</p>';
	echo '<script>location.href="/";</script>';
	?>
	</body>
	</html>