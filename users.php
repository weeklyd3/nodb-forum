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
?><html lang="en">
  <head>
    <title>Users</title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body><h2>Users</h2>This list shows all the members of this board.<?php
  $GLOBALS['size'] = 2;
$handle = array_diff(scandir(__DIR__ . '/data/accounts/'), array('index.php', '.', '..', 'default.png'));
$page = isset($_GET['page'])
	? (
		is_numeric($_GET['page']) ? (int) $_GET['page'] : 1
	)
	: 1;
	?><br /><?php
	$total = ceil((count($handle)) / $GLOBALS['size']);
echo "Page " . $page . ' of ' . $total;
function paginate($handle, $page, $total) {
	if (count($handle) < $GLOBALS['size']) return;
	?><div class="pagination"><a href="?page=1">first</a> <?php if ($page > 1) { ?><a href="?page=<?php
	echo $page - 1;
	?>">back</a> <?php }
	if ($page < $total) { ?><a href="?page=<?php
	echo $page + 1;
	?>">next</a> <?php }
	?><a href="?page=<?php echo $total; ?>">last</a></div>
	<?php
}
paginate($handle, $page, $total);
?><div id="gallery"><?php
$GLOBALS['counter'] = 0;
if ($handle = opendir(__DIR__ . '/data/accounts')) {
	while (false !== ($entry = readdir($handle))) {
		if (!is_dir(__DIR__ . '/data/accounts/' . $entry)) continue;
		if (in_array($entry, array('default.png', 'index.php', ".", '..'))) continue;
		$GLOBALS['counter'] = $GLOBALS['counter'] + 1;
		if ($GLOBALS['counter'] < $page * $GLOBALS['size']) continue;
		if ($GLOBALS['counter'] == ($page + 1) * $GLOBALS['size']) break;
		?><div id="flex"><img src="data/accounts/<?php echo htmlspecialchars($entry); ?>/avatar.png" /> <?php echo file_exists(__DIR__ . '/data/accounts/' . $entry . '/user.txt') ? htmlspecialchars(file_get_contents(__DIR__ . '/data/accounts/' . $entry . '/user.txt')) : 'false'; ?></div><?php
	}
}
?>
</div>
<style>#flex { background-color: #94bdff; margin: 5px; } #gallery { display: flex; flex-wrap: wrap; }</style>