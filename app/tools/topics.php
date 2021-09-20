<?php include_once('header.php');
?>
<h2>Topics</h2>
<p>
Remove them if they are being abusive or are all consisted of low quality posts.</p>
<ul>
<?php 
foreach( $_POST as $name => $stuff ) {
	if (is_dir('../../data/messages/'.cleanFilename($stuff))) {
		$rm = delTree('../../data/messages/'.cleanFilename($stuff));
		if ($rm) {
			echo '<li>Room '.htmlspecialchars($stuff).' deleted.</li>';
		} else {
			echo '<li>Room '.htmlspecialchars($stuff).' not deleted.</li>';
		}
	}
}
?>
</ul>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="submit" value="Remove!" />
<?php
if ($handle = opendir('../../data/messages/')) {
    while (false !== ($entry = readdir($handle))) {
		if (is_dir('../../data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			echo '<div><input type="checkbox" name="'.htmlspecialchars($entry).'" value="'.htmlspecialchars($entry).'" id="'.htmlspecialchars($entry).'" /><label for="'.htmlspecialchars($entry).'">';
			$config = file_get_contents('../../data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			echo htmlspecialchars($config->title);
			echo "\n";
			echo '</label></div>';
		}
    }
    closedir($handle);
}
?>
<input type="submit" value="Remove!" />
</form>