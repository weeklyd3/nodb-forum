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
    <title>File Viewer</title>
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
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
<label for="address">Address:</label>&nbsp;&nbsp;<input type="text" name="address" id="address" placeholder="data/" size="30" />&nbsp;&nbsp;<input type="submit" value="Load" />
</form>
  <?php
echo "<pre>";
$url = $_GET['address'];
if (!(endsWith($url, '/'))) {
	$url.='/';
}
if ($handle = opendir(getcwd() . '/../' . $url)) {
	echo "<h2>File Viewer</h2>";

	echo "<h3>Entries:</h3><ul>";
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle))) {
		if ($entry != ".." && $entry != ".") {
			echo "<li><a href=\"./explorer/?address=".$_GET['address']."$entry/\">$entry</a> (<a href=\"".$_GET['address']."".$entry."\" target=\"_blank\">open raw</a>) (<a href=\"./explorer/viewsource.php?address=".$_GET['address'].$entry."\">view source</a>)</li>";
		}
	}

	closedir($handle);
} else {
	echo '<script>document.getElementById("address").value="'.htmlspecialchars($url).'";</script>';
	echo "Bad news! You might have a bad address or just simply have specified an extra slash after a filename.";
}
echo "</ul></pre>";
echo '<script>document.getElementById("address").value="'.htmlspecialchars($url).'";</script>';
?>
	<?php
	include('../public/footer.php');
	?>
</body>
</html>