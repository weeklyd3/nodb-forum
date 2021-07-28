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
    <title>Forums &mdash; Banned</title>
	<?php
	echo '<!--';
	include('./public/header.php');
	echo '-->';
	include('./styles/inject.php');
	if (!(file_exists('./data/accounts/'.cleanFilename(getname()).'/ban.txt'))) {
		echo '<script>location.href="/";</script>';
	}
	?>
  </head>
  <body>
	<h2>You have been banned</h2>
	Here is the reason the administrator has given you:
	<blockquote><?php echo file_get_contents('./data/accounts/'.cleanFilename(getname()) . '/ban.txt'); ?></blockquote>
	  The administrator can lift the ban.
  </body>
</html>
