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
    <title><?php 
		if (file_exists(__DIR__ . '/config.json')) {
			$name = json_decode(file_get_contents(__DIR__ . '/config.json'));
			echo htmlspecialchars($name->forumtitle);
		}
	?></title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
    <h2><?php 
	$login = isset($_COOKIE['login']) ? $_COOKIE['login'] : "";
	if ($login != "") {
		echo "Welcome, ".htmlspecialchars(getname()).'!</h2> We&apos;re glad to have you. Choose a room to join. (<a href="create.php" class="fakebutton">new topic</a>)';
	} else {
		echo "You are not logged in.</h2> You will not be able to post any messages, upload files, or communicate with others, although you will be able to view public content.";
	}
	include_once('./libraries/listroom.php');
	include_once('./public/footer.php');
	?>
  </body>
</html>