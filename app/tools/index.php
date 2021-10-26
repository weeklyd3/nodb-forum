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
include_once('header.php');
$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'));
$actions = array('updatetime', 'clearstatcache', 'uninstall', 'purgeusers');
$action = isset($_POST['action']) ? $_POST['action'] : null;
function uninstall() {
	return unlink(__DIR__ . '/../../config.json');
}
function updatetime() {
	$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'));
	$config->creationTime = time();
	fwrite(fopen(__DIR__ . '/../../config.json', 'w+'), json_encode($config));
}
function purgeusers() {
	$dir = __DIR__ . '/../../data/accounts';
	$scanned = scandir($dir, SCANDIR_SORT_NONE);

	if (count($scanned) > 14) echo "More than 10 users present<hr />";

	foreach ($scanned as $account) {
		if ($account == '.' || $account == '..') continue;
		if (!is_dir("$dir/$account")) continue;
		if ($account == cleanFilename(getname())) continue;
		delTree("$dir/$account");
	}
}
if (in_array($action, $actions)) {
	$action();
	?>Ran action: <?php echo htmlspecialchars($action); ?><hr /><?php
}
?>
<h2>What is This?</h2>
This is a series of pages designed to help administrators
moderate the forums they run. 
<h3>I got denied access, now what?</h3>
Verify that you are logged in as an administrator. Only the users that are admins may view the /app/tools page.
<p>Try this list:</p>
<ul>
<?php 
	foreach ($config->admins as $admin) {
		?><li><?php echo htmlspecialchars($admin); ?></li><?php
	}
?>
</ul>
<h3>Return to Home Page</h3>
Click the 'Log Out' link at the top:
<br />
<img src="../../img/moderator_logout.png" alt="Click the 'log out' link on the top of the navigation bar." style="max-width:100%;" />
<h3>Misc.</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<ul style="list-style:none; padding:0;">
		<li>Clear file cache: <input type="submit" value="Go" onclick="document.getElementById('action').value='clearstatcache';" /></li>
		<li>Update board setup date to now: <input type="submit" value="Go" onclick="document.getElementById('action').value='updatetime';" /></li>
		<li>Filtering common words from search is currently <strong><?php 
			echo isset($config->searchFilter) ? "enabled" : "disabled"; ?></strong>. <a href="app/tools/searchfilter.php">Search filter settings</a></li>
		<div style="background-color:#ff6464;">
		<h4>Danger Zone</h4>
		<li>Clear installation data: <input type="submit" value="Go" onclick="if(!confirm('WARNING (1): All installation data will be lost! You will need to set up the board again.\n\nWARNING (2): After you uninstall anyone can set up the board.\n\nIf you do not want to uninstall then click Cancel!')){event.preventDefault();}else{document.getElementById('action').value='uninstall';}" /></li>
		<li>Remove ALL users except your account (only works if there are less than 10): <input type="submit" value="Go" onclick="if(!confirm('WARNING: This tool will delete ALL users except your own account.')){event.preventDefault();}else{document.getElementById('action').value='purgeusers';}" /></li>
		</div>
	</ul>
	<input type="hidden" name="action" id="action" value="" />
</form>
<h3>Stats</h3>
<table style="width:100%;" class="table">
	<tbody>
		<tr>
			<th>Name</th>
			<th>Value</th>
		</tr><?php if (isset($config->creationTime)) { ?>
		<tr>
			<td>Board age (days)<sup>*</sup></td>
			<td style="width:0px;"><?php 
				echo ceil((time() - $config->creationTime) / 86400)
			?></td>
		</tr><?php } ?>
		<tr>
			<td><span title="Software size + data size + uploaded file size">Total installation size</span></td>
			<td style="width:0px;"><?php 
				echo getDirSize(__DIR__ . '/../../');
			?></td>
		</tr>
		<tr>
			<td>Size of uploaded files</td>
			<td><?php 
				echo getDirSize(__DIR__ . '/../../files/uploads');
			?></td>
		</tr>
		<tr>
			<td>Size of accounts</td>
			<td><?php 
				echo getDirSize(__DIR__ . '/../../data/accounts');
			?></td>
		</tr>
		<tr>
			<td>Size of topics</td>
			<td><?php 
				echo getDirSize(__DIR__ . '/../../data/messages');
			?></td>
		</tr>
		<tr>
			<td>Data size (bytes)</td>
			<td><?php echo getDirSize(__DIR__ . '/../../data/'); ?></td>
		</tr>
		<tr>
			<td>Users</td>
			<td><?php 
				echo count(scandir(__DIR__ . '/../../data/accounts', SCANDIR_SORT_NONE)) - 4;
			?></td>
		</tr>
		<tr>
			<td>Uploaded files</td>
			<td><?php 
				echo count(scandir(__DIR__ . '/../../files/uploads', SCANDIR_SORT_NONE)) - 3;
			?></td>
		</tr>
		<tr>
			<td>Topics</td>
			<td><?php 
				echo count(scandir(__DIR__ . '/../../data/messages', SCANDIR_SORT_NONE)) - 3;
			?></td>
		</tr><?php if (isset($config->creationTime)) { ?>
		<tr>
			<td>Topics per day<sup>**</sup></td>
			<td><?php 
				$topicsperday = round(round(count(scandir(__DIR__ . '/../../data/messages', SCANDIR_SORT_NONE)) - 3) / ceil((time() - $config->creationTime) / 86400), 2);
				echo $topicsperday;
			?></td>
		</tr>
		<tr>
			<td>Users joining per day<sup>**</sup></td>
			<td><?php 
				echo round(round(count(scandir(__DIR__ . '/../../data/accounts', SCANDIR_SORT_NONE)) - 4) / ceil((time() - $config->creationTime) / 86400), 2);
			?></td>
		</tr><?php } ?>
	</tbody>
</table>
<?php if (isset($config->creationTime)) { ?><small>*rounded up</small><br /><small>**rounded to two decimal places</small><?php } ?>