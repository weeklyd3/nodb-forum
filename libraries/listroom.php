<h4>Available Topics</h4>
<p>Search options <strong><?php 
	if (!isset($_GET['sort']) || $_GET['sort'] == 'abc_asc') {
		echo 'Alphabetical order';
	} else {
		if ($_GET['sort'] == 'abc_dsc')
			echo 'Backwards alphabetical order';
		if ($_GET['sort'] == 'os')
			echo 'None';
		if ($_GET['sort'] == 'random') {
			echo 'Random order';
		}
	}
?></strong></p>
Sort: <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET">
<label title="Sorts by case insensitive alphabetical order.">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'abc_asc') {
			?>checked="checked"<?php
		}
	} else {
		?>checked="checked"<?php
	}
?> value="abc_asc" />
Ascending alphabetical order
</label>
<label title="Sort by alphabetical order, and list in backwards order">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'abc_dsc') {
			?>checked="checked"<?php
		}
	}
?> value="abc_dsc" />
Descending alphabetical order
</label>
<label title="Display in order used by filesystem">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'os') {
			?>checked="checked"<?php
		}
	}
?> value="os" />
No sort
</label>
<label title="Randomizes room order. Discover something new! Not cryptographically secure.">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'random') {
			?>checked="checked"<?php
		}
	}
?> value="random" />
Shuffle
</label>
<input type="submit" value="Go" />
</form>
<?php
if (!isset($_GET['sort']) || $_GET['sort'] == 'abc_asc')
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING);
if (!isset($_GET['sort']) || $_GET['sort'] == 'abc_asc')
	natcasesort($handle);
if (isset($_GET['sort']) && $_GET['sort'] == 'abc_dsc') 
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_DESCENDING);
if (isset($_GET['sort']) && $_GET['sort'] == 'abc_dsc') 
	natcasesort($handle);
if (isset($_GET['sort']) && $_GET['sort'] == 'abc_dsc') 
	$handle = array_reverse($handle, true);
if (isset($_GET['sort']) && ($_GET['sort'] == 'os' || $_GET['sort'] == 'random'))
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_NONE);
if (isset($_GET['sort']) && $_GET['sort'] == 'random')
	shuffle($handle);

if (true) {
	echo "<table style=\"width:100%;\">";
    foreach($handle as $key => $entry) {
		error_reporting(E_ALL);
		if (is_dir('data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			echo '<tr style="width:100%;"><td>';
			$config = file_get_contents('data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			echo '<td class="gallery"><strong><a href="webchat.php?room='.htmlspecialchars($config->title).'">';
			echo htmlspecialchars($config->title);
			echo '</a></strong><br />';
			echo 'Created by ';
			echo htmlspecialchars($config->author);
			echo ' on ';
			echo date('Y-m-d H:i:s', $config->creationTime);
			echo ' UTC';
			?><br />Tagged: <?php
			$decodedTags = explode(" ", $config->tags);
			foreach ($decodedTags as $key => $value) {
				?><span class="tag"><a title="Click to show topics tagged <?php echo htmlspecialchars($value); ?>." href="tagged.php?tag=<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></a></span> <?php
			}
			echo '</td></tr>';
			echo "\n";
		}
    }
	echo '</table>';
}
?>