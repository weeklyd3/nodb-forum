<?php include('header.php');
?>
<h2>Topics</h2>
Remove them if they are being abusive or are all consisted of low quality posts.
<ul>
<?php 
function delTree($dir) { 
	$files = array_diff(scandir($dir), array('.', '..')); 

	foreach ($files as $file) { 
		(is_dir("$dir/$file") && !is_link("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
	}

	return rmdir($dir); 
} 
foreach( $_POST as $stuff ) {
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
			echo $config->title;
			echo "\n";
			echo '</label></div>';
		}
    }
    closedir($handle);
}
?>
<input type="submit" value="Remove!" />
</form>