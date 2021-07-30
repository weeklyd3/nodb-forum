<h4>Available Topics</h4>
<?php
if ($handle = opendir('data/messages/')) {
	echo "<table style=\"width:100%;\">";
    while (false !== ($entry = readdir($handle))) {
		if (is_dir('data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			echo '<tr style="width:100%;"><td>';
			$config = file_get_contents('data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			echo '<td class="gallery"><strong><a href="webchat.php?room='.$config->title.'">';
			echo $config->title;
			echo '</a></strong><br />';
			echo $config->description_html;
			echo '</td></tr>';
			echo "\n";
		}
    }
	echo '</table>';
    closedir($handle);
}
?>