<?php
/*
    Forum Software
    Copyright (C) 2021 weeklyd3

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
	<base href="../" />
    <title>data/</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
	<p>This is the <code>data/</code> directory, used for storing data that is generated by the system when a user posts content.</p>
	<p>Nothing to see here!</p>
	<?php
echo "<pre>";
if ($handle = opendir('./')) {
    echo "<h2>Data Explorer</h2>";
    echo "<h3>Entries:</h3><ul>";

    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
		if ($entry != "index.php") {
        	echo "<li><a href=\"data/$entry/\">$entry</a></li>";
		} else {
			echo "<li><a href=\"data/$entry\">$entry</a></li>";
		}
    }

    closedir($handle);
}
echo "</ul></pre>";
	include('../public/footer.php');
	?>
</body>
</html>