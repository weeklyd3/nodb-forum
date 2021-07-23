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
    <title>Users</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body><h2>Users</h2>This list shows all the members of this board.<?php
echo '<ul>';
$GLOBALS['i'] = 1;
if ($handle = opendir('data/accounts')) {

    while (false !== ($entry = readdir($handle))) {
		$address = 'data/accounts/'.$entry;
        if ($entry != "." && $entry != ".." && is_dir('data/accounts/'.$entry)) {
			$string = htmlspecialchars(file_get_contents('data/accounts/'.$entry.'/user.txt'));
            echo "<li>$string</li>\n";
			$GLOBALS['i']++;
        }
    }

    closedir($handle);
}
echo '</ul>';
?>