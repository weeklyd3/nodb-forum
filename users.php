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
  $GLOBALS['size'] = 10;
$handle = array_diff(scandir(__DIR__ . '/data/accounts/'), array('readme.txt', '.', '..', 'default.png'));
$search = isset($_GET['search']) ? $_GET['search'] : "";
$GLOBALS['search'] = $search;
if ($search !== "") $handle = array_filter($handle, function($n) { $user = file_get_contents('data/accounts/' . $n . '/user.txt'); return stripos($user, $GLOBALS['search']) !== false; });
$page = isset($_GET['page'])
	? (
		is_numeric($_GET['page']) ? (int) $_GET['page'] : 1
	)
	: 1;
	?><br />
	  <form action="users.php" method="GET">Need help finding your username? <label>Search: <input type="text" name="search" /></label> <input type="submit" /></form>
	  <br /><?php
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
$admins = json_decode(file_get_contents("config.json"));
$admins = $admins->admins;
?><div id="gallery"><?php
$GLOBALS['counter'] = 0;
$GLOBALS['failEntries'] = 0;
foreach ($handle as $entry) {
		if (in_array($entry, array(".", "..", "default.png", "readme.txt"))) continue;
		$name = file_get_contents(__DIR__ . '/data/accounts/' . cleanFilename($entry) . '/user.txt');
		if ($GLOBALS['failEntries'] < ($page - 1) * ($GLOBALS['size'])) {
			$GLOBALS['failEntries']++;
			continue;
		}
		if ($GLOBALS['counter'] === $GLOBALS['size']) {
			break;
		}
		?>
			<div class="flex">
				<img src="data/accounts/<?php echo htmlspecialchars($entry); ?>/avatar.png" alt="Avatar image" />
				<a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode(file_get_contents('data/accounts/' . $entry . '/user.txt'))); ?>"><?php echo htmlspecialchars(file_get_contents('data/accounts/' . $entry . '/user.txt')); ?></a><br />
				Role: <?php echo in_array( file_get_contents('data/accounts/' . $entry . '/user.txt'), $admins) ? '<span title="Reviews reports, adjusts sitewide settings, and moderates content in addition to posting content.">Administrator</span>' : '<span title="Reads, reports, and adds content.">User</span>'; ?><br />
				Banned: <?php echo file_exists('data/accounts/' . $entry . '/ban.txt') ? "<b>Yes</b>" : "No"; ?><br />
				Joined: <?php echo friendlyDate(filemtime('data/accounts/' . $entry . '/user.txt')); ?><br />
				<?php 
					if (getname()) {
						?><a href="messages/new.php?target=<?php echo htmlspecialchars(urlencode(file_get_contents('data/accounts/' . $entry . '/user.txt'))); ?>">Contact</a><?php
					}
				?>
			</div>
		<?php
		$GLOBALS['counter']++;
	}
?>
</div>
<style>.flex { background-color: #94bdff; margin: 5px; } #gallery { display: flex; flex-wrap: wrap; }</style>