<h2>Available Topics</h2>
<details open="open">
	<summary>Upcoming Events</summary>
	<ul style="list-style:none !important;padding:3px;"><li>
		<ul style="list-style:none;padding:3px;">
			<?php 
				clearstatcache();
				$array = (array) json_decode(file_get_contents(__DIR__.'/../data/community/events/config.json'));
				foreach ($array as $value) {
					$time = $value->time;
					if (time() < $time) {
						?><li><a href="<?php echo htmlspecialchars($value->url); ?>"><?php echo htmlspecialchars($value->title); ?></a> <span style="color:gray;"><?php echo friendlyDate($value->time); ?></span></li><?php
					}
				}
			?>
			<?php if (getname()) {?><li><a href="events">Add item</a></li> <?php } ?>
		</ul>
	</li></ul>
</details>
<p>Search options <strong><?php 
	$size = 10;
	$count = count(scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING)) - 3;
	if (!isset($_GET['sort']) || $_GET['sort'] == 'active') {
		echo 'Recently active';
	} else {
		if ($_GET['sort'] == 'abc_asc')
			echo "Alphabetical order";
		if ($_GET['sort'] == 'abc_dsc')
			echo 'Backwards alphabetical order';
		if ($_GET['sort'] == 'os')
			echo 'None';
		if ($_GET['sort'] == 'random') {
			echo 'Random order';
		}
	}
	function paginateButton() {
		$items = count(scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING)) - 3;

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$size = 10;
		if (ceil($items/$size) == 1) return;
		if ($page > 1) {
			?> <form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : "abc_asc"; ?>" /><input type="hidden" name="page" value="<?php echo $page - 1; ?>" /><input type="submit" value="&lt;" /></form> <?php
		}
		?> 
		<form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : "active"; ?>" /><input type="hidden" name="page" value="1" /><input type="submit" value="1" /></form> <form class="inline" action="<?php
		if (!(ceil($items/$size) == 1)) {
				echo $_SERVER['REQUEST_URI'];
				?>?page=<?php
				echo htmlspecialchars(ceil($items/$size));
			?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : "active"; ?>" /><input name="page" type="submit" value="<?php echo htmlspecialchars(ceil($items/$size)); ?>" /></form> <?php
		}
		if ($page < $items/$size) {
			?> <form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : "active"; ?>" /><input type="hidden" name="page" value="<?php echo $page + 1; ?>" /><input type="submit" value="&gt;" /></form> <?php
		}
	}
?></strong></p><style>.inline{display:inline;}</style>
Sort: <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET">
<input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : '1'; ?>" />
<label title="Recently active topics">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'active' || $_GET['sort'] == '') {
			?>checked="checked"<?php
		}
	} else {
		?>checked="checked"<?php
	}
?> value="active" />
Recently active
</label>
<label title="Sorts by case insensitive alphabetical order.">
<input type="radio" name="sort" <?php
	if (isset($_GET['sort'])) {
		if ($_GET['sort'] == 'abc_asc') {
			?>checked="checked"<?php
		}
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
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET">
<?php $items = $count;
?>
<label>Page: <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : "active"; ?>" /><input type="number" min="1" max="<?php echo ceil($items / $size); ?>" value="<?php if (isset($_GET['page']) && ctype_digit($_GET['page'])) { echo htmlspecialchars($_GET['page']); } else { echo '1'; } ?>" name="page" /></label> 
<input type="submit" value="Go!" /></form>
<strong>Showing <em><?php echo $size; ?></em> entries on page <?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?></strong><br />
<?php
paginateButton();
if (!isset($_GET['sort']) || $_GET['sort'] == 'active')
	$handle = scan_dir(__DIR__.'/../data/messages/');
if ($_GET['sort'] == 'abc_asc')
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING);
if (isset($_GET['sort']) && $_GET['sort'] == 'abc_asc')
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

$handle = array_diff($handle, array('.', '..', 'index.php'));
$handle = array_values($handle);
if (true) {
	echo "<table style=\"width:100%;\">";
	$page = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
	$index = $page * $size;
    for ($i = isset($_GET['page']) ? ceil($_GET['page']-1)*$size : 0; $i < $index + $size; $i++) {
		if (!isset($handle[$i])) {break;}
		$entry = $handle[$i];
		if (is_dir('data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			echo '<tr style="width:100%;" class="gallery">';
			$config = file_get_contents('data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			echo '<td style="border-radius:10px;"><strong><a href="webchat.php?room='.htmlspecialchars(urlencode($config->title)).'">';
			echo htmlspecialchars($config->title);
			echo '</a></strong><br />';
			echo 'Created by ';
			echo htmlspecialchars($config->author);
			?><br />Tagged: <?php
			$decodedTags = explode(" ", $config->tags);
			foreach ($decodedTags as $key => $value) {
				?><span class="tag"><a title="Click to show topics tagged <?php echo htmlspecialchars($value); ?>." href="tagged.php?tag=<?php echo htmlspecialchars(urlencode($value)); ?>"><?php echo htmlspecialchars($value); ?></a></span> <?php
			}
			echo '</td>';
			?><td style="width:0;" style="border-radius:10px;">views: <?php echo isset($config->views) ? colorNum($config->views) : 0; ?></td>
			<td style="width:0;" style="border-radius:10px;">created: <?php echo friendlyDate($config->creationTime); ?></td>
			<td style="width:0;" style="border-radius:10px;">active: <?php 
			clearstatcache();
			echo friendlyDate(filemtime(__DIR__ . '/../data/messages/' . $entry . '/msg.json')); ?></td>
			<td style="width:0;" style="border-radius:10px;">replies: <?php 
				$msgs = (array) json_decode(file_get_contents(__DIR__ . '/../data/messages/' . $entry . '/msg.json'));
				echo colorNum(count($msgs));
			?></td><?php
			echo "\n";
		}
    }
	echo '</table>';
}
?>
<style>tr.gallery>td { border-radius: 10px !important; } </style>
There are <?php echo ceil($count/$size); ?> page(s) total.<br />
<?php 
paginateButton();
?>