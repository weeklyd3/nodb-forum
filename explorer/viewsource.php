<html>
  <head>
	<base href="../../" />
    <title>Source Code Examiner</title>
	<?php
	include('../libraries/lib.php');
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