<h2>Available Topics</h2>
<details open="open">
	<summary>Upcoming Events</summary>
	<ul style="list-style:none !important;padding:3px;"><li>
		<ul style="list-style:none;padding:3px;">
			<?php
			$sort = isset($_GET['sort']) ? $_GET['sort'] : "";
				clearstatcache();
				$array = (array) json_decode(file_get_contents(__DIR__.'/../data/community/events/config.json'));
				foreach ($array as $date => $items) {
					if (count((array) $items) === 0) continue;
					// Skip if in past
					if (strtotime($date) < strtotime(date("m/d/Y"))) continue;
					?><li><?php echo explode("@", friendlyDate(strtotime($date . " 00:00")))[0]; 
					?><ul><?php
					foreach ($items as $item) {
						?><li><a href="<?php echo htmlspecialchars($item->url); ?>"><?php echo htmlspecialchars($item->title); ?></a> (<?php echo friendlyDate($item->time); ?>)</li><?php
					}
					?></ul></li><?php
				}
			?>
			<a href="events/calendar.php">View calendar</a>
			<?php if (getname()) {?><li><a href="events">Add item</a></li> <?php } ?>
		</ul>
	</li></ul>
</details>
<p>Search options <strong><?php 
	$size = 10;
	$count = count(scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING)) - 3;
	if (!$sort || $sort == 'active') {
		echo 'Recently active';
	} else {
		if ($sort == 'abc_asc')
			echo "Alphabetical order";
		if ($sort == 'abc_dsc')
			echo 'Backwards alphabetical order';
		if ($sort == 'os')
			echo 'None';
		if ($sort == 'random') {
			echo 'Random order';
		}
	}
	function paginateButton() {
		$items = count(scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_ASCENDING)) - 3;

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$size = 10;
		if (ceil($items/$size) == 1) return;
		if ($page > 1) {
			?> <form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($sort) ? $_GET['sort'] : "abc_asc"; ?>" /><input type="hidden" name="page" value="<?php echo $page - 1; ?>" /><input type="submit" value="&lt;" /></form> <?php
		}
		?> 
		<form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="hidden" name="sort" value="<?php echo isset($sort) ? $_GET['sort'] : "active"; ?>" /><input type="hidden" name="page" value="1" /><input type="submit" value="1" /></form> <form class="inline" action="<?php
		if (!(ceil($items/$size) == 1)) {
				echo $_SERVER['REQUEST_URI'];
				?>?page=<?php
				echo htmlspecialchars(ceil($items/$size));
			?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($sort) ? $_GET['sort'] : "active"; ?>" /><input name="page" type="submit" value="<?php echo htmlspecialchars(ceil($items/$size)); ?>" /></form> <?php
		}
		if ($page < $items/$size) {
			?> <form class="inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET"><input type="hidden" name="sort" value="<?php echo isset($sort) ? $_GET['sort'] : "active"; ?>" /><input type="hidden" name="page" value="<?php echo $page + 1; ?>" /><input type="submit" value="&gt;" /></form> <?php
		}
	}
?></strong></p><style>.inline{display:inline;}</style>
Sort: <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET">
<input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : '1'; ?>" />
<label title="Recently active topics">
<input type="radio" name="sort" <?php
	if ($sort) {
		if ($sort == 'active' || $_GET['sort'] == '') {
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
	if ($sort == 'abc_asc') {
		?>checked="checked"<?php
	}
?> value="abc_asc" />
Ascending alphabetical order
</label>
<label title="Sort by alphabetical order, and list in backwards order">
<input type="radio" name="sort" <?php
	if ($sort == 'abc_dsc') {
		?>checked="checked"<?php
	}
?> value="abc_dsc" />
Descending alphabetical order
</label>
<label title="Display in order used by filesystem">
<input type="radio" name="sort" <?php
	if ($sort == 'os') {
		?>checked="checked"<?php
	}
?> value="os" />
No sort
</label>
<label title="Randomizes room order. Discover something new! Not cryptographically secure.">
<input type="radio" name="sort" <?php
	if ($sort == 'random') {
		?>checked="checked"<?php
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
if (!$sort || $sort == 'active')
	$handle = scan_dir(__DIR__.'/../data/messages/', "msg.json");
if ($sort == 'abc_asc')
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_NONE);
if ($sort == 'abc_asc')
	natcasesort($handle);
if ($sort == 'abc_dsc') 
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_NONE);
if ($sort == 'abc_dsc') 
	natcasesort($handle);
if ($sort == 'abc_dsc') 
	$handle = array_reverse($handle, true);
if (($sort == 'os' || $sort == 'random'))
	$handle = scandir(__DIR__.'/../data/messages/', SCANDIR_SORT_NONE);
if ($sort == 'random')
	shuffle($handle);

$handle = array_diff($handle, array('.', '..', 'index.php'));
$handle = array_values($handle);
if (true) {
	echo "<table style=\"width:100%;\" id=\"topics\">";
	?><tr><th class="topic-header">Name</th><th class="topic-header">Views</th><th class="topic-header">Created</th><th class="topic-header">Active</th><th class="topic-header">Replies</th></tr><?php
	$page = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
	$index = $page * $size;
    for ($i = isset($_GET['page']) ? ceil($_GET['page']-1)*$size : 0; $i < $index + $size; $i++) {
		if (!isset($handle[$i])) {break;}
		$entry = $handle[$i];
		if (is_dir('data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			$del = file_exists('data/messages/'.$entry.'/del.json');
			$config = file_get_contents('data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			if ($del) {
				if ($config->author !== getname()) {
					if (!verifyAdmin()) {
						continue;
					}
				}
			}
			echo '<tr style="width:100%;';
			if ($del) {
				?>background-color:#ffdddd;<?php
			}
			echo '" class="gallery">';
			echo '<td style="border-radius:10px;';
			echo '"><strong><a href="viewtopic.php?room='.htmlspecialchars(urlencode($config->title)).'">';
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
			?><td style="width:0;border-radius:10px;"><?php echo isset($config->views) ? colorNum($config->views) : 0; ?></td>
			<td style="width:0;border-radius:10px;"><?php echo friendlyDate($config->creationTime); ?></td>
			<td style="width:0;border-radius:10px;"><?php 
			clearstatcache();
			echo friendlyDate(filemtime(__DIR__ . '/../data/messages/' . $entry . '/msg.json')); ?></td>
			<td style="width:0;border-radius:10px;<?php $accepted = false; if (isset($config->accepted)) { ?>background-color:lime;color:black !important;<?php $accepted = true; } ?>"><?php 
				if (isset($config->replies)) {
					if (!$accepted) { echo colorNum($config->replies); }
				else { echo $config->replies; }
				} else {
					$msgs = (array) json_decode(file_get_contents(__DIR__ . '/../data/messages/' . $entry . '/msg.json'));
					echo colorNum(count($msgs));
					$config->replies = count($msgs);
					fwrite(fopen(__DIR__ . '/../data/messages/' . $entry . '/config.json', 'w+'), json_encode($config));
				}
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
<style>.topic-header {
	border-radius: 10px;
	color: black;
	background-color: lightblue;
	text-transform: capitalize;
}</style>