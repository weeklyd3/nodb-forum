<?php 
require 'header.php';
?>
<p>This page logs the last 100 page visits. <a href="visitlog.json">Download log (JSON)</a></p>
<p>Legend: <span class="colorpreview" style="background-color: blue;"></span> Your visits <span class="colorpreview" style="background-color: lime; color: black;"></span> Highlighted visits</p>
<table class="table" style="width: 100%;">
<tr>
<th>Time</th>
<th>IP</th>
<th>User</th>
<th>URL</th>
</tr>
<?php
$failmsg = "<tr><td colspan=\"4\">No items.</td></tr>";
if (!file_exists(__DIR__ . '/../../visitlog.json')) {
	die($failmsg);
}
$entries = json_decode(file_get_contents(__DIR__ . '/../../visitlog.json'));
if (count($entries) === 0) {
	die($failmsg);
}
$i = 0;
foreach ($entries as $entry) {
	if ($i == 100) break;
	if (isset($_GET['hideall'])) {
		if ($_GET['name'] != (string) $entry->user) {
			continue;
		}
	}
	?><tr<?php 
	if (isset($_GET['name'])) {
		if ($_GET['name'] === (string) $entry->user) {
			if (!isset($_GET['hideall'])) {
				?> style="background-color:lime;color:black;" title="Matched search query"<?php
			}
		}
	}
	if (getname() === $entry->user) {
		?> style="background-color:blue;" title="Your visit"<?php
	}
	?>><td><?php echo friendlyDate($entry->time); ?></td>
	<td><?php echo htmlspecialchars($entry->ip); ?></td>
	<td><?php 
	if ($entry->user) {
		echo htmlspecialchars($entry->user);
	} else {
		?><i>Anonymous</i><?php
	}
	?></td>
	<td>
	<?php echo htmlspecialchars($entry->url); ?>
	</td>
	</tr><?php
	$i += 1;
}
?>
</table>
<form action="app/tools/visitlog.php" method="get">
<label>Highlight user (leave empty to search for anonymous users): <input type="text" name="name" /></label>
<label><input type="checkbox" name="hideall" value="yes" /> Hide others</label>
<input type="submit" value="Go" />
</form>
<a href="app/tools/visitlog.php">Refresh</a>