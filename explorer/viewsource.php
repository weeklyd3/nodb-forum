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
	<base href="../../" />
    <title>Source Code Examiner</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	/* $loc = $_SERVER['PHP_SELF'];
	if (!(endsWith($loc, '/'))) {
		header('Location: '.$_SERVER['PHP_SELF'].'/');
	} */
	?>
  </head>
  <body>
  <h2>File reader</h2>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
<label for="address">File: </label><input type="text" name="address" id="address" placeholder="index.php" size="30" /><input type="submit" value="Load" />
</form>
  <?php
$address = $_GET['address'];
$contents = explode("\n", file_get_contents('../'.$address));
if (file_get_contents('../'.$address)) {
	echo '<code><big>'.$address.'</big></code>';
	echo '<br><code>' . count($contents) . ' lines, '.strlen(file_get_contents('../'.$address)).' bytes</code>';
	echo "<ol style=\"word-break:break-all;\">";
	for ($i = 0; $i < count($contents); $i++) {
		echo '<li><pre>'.htmlspecialchars($contents[$i]).'</pre></li>';
	}
	echo '</ol>';
} else {
	echo "<span style=\"color:white; background-color:red;\">Error while reading file</span>";
}
include('../public/footer.php');
?>
</body>
</html>