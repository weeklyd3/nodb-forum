<?php
/*
    Forum Software
    Copyright (C) 2022 contributors

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
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
    <title>Public Logs</title>
  </head>
  <body><h2>Logs</h2>
<p>These logs contain logged actions. They are provided to everyone.</p>
	  <p>Note that some log details may be long and will be truncated. Click on the truncated part to view the whole entry.</p>
	  <style>
details.oneway[open] > summary > .hidetext {
display: none;
}
details.oneway:not([open]) > summary > .hiddentext {
display: none;
}
details.oneway {
	margin: 0;
	padding: 0;
}
details.oneway > summary {
	list-style: none;
	margin: 0;
	padding: 0;
}
</style>
<table>
	<tr>
		<th>Time</th>
		<th>User</th>
		<th>Subject</th>
		<th>Details</th>
	</tr>
<?php
$log = json_decode(file_get_contents('log.json'));
$log = array_reverse($log);
$types = array();
foreach ($log as $entry) {
	array_push($types, $entry->subject);
	if (isset($_GET['noinclude-' . $entry->subject])) continue;
	?><tr>
		<td><?php echo friendlyDate($entry->time); ?></td>
		<td><?php echo htmlspecialchars($entry->user); ?></td>
		<td><?php echo htmlspecialchars($entry->subject); ?></td>
		<td><?php 
		if (strlen($entry->details) > 30) {
			?><details class="oneway"><summary><span class="hidetext"><?php echo htmlspecialchars(substr($entry->details, 0, 30)); ?><span title="Click the truncated details string to see the whole string." style="color: #dddddd;">...</span></span></summary>
			<label><span hidden="hidden">Long contents:</span><input type="text" readonly="readonly" style="width: 100%;" value="<?php echo htmlspecialchars($entry->details); ?>" class="fake" /></label></details><?php
		} else
		echo htmlspecialchars($entry->details); ?></td>
	</tr><?php
}
?>
</table>
<div style="float: right; background-color: #eeeeee; color: black; border: 1px solid;">
<details class="oneway">
<summary>
<span class="hidetext">Additional options [expand]</span>
<span class="hiddentext">[collapse log options]</span>
</summary>
<details class="oneway">
<summary>
<span class="hidetext">
Filter log
<?php if (count($_GET) !== 0) {
	?><strong>(active)</strong> <?php
}
?>
[expand]
</span>
<span class="hiddentext">
[collapse log filtering]
</span>
</summary>
<p>Do not show the following types of log entries:</p>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<?php 
$types = array_unique($types);
foreach ($types as $type) {
	?><label><input type="checkbox" name="noinclude-<?php echo htmlspecialchars($type); ?>" value="true" 
	<?php 
	if (isset($_GET['noinclude-' . $type])) { ?>checked="checked"<?php } ?>
	/>
	<?php echo htmlspecialchars($type); ?>
	</label><br /><?php
}
?>
<input type="submit" value="Filter log" />
<input type="reset" value="Load current settings" />
</form>
</details>
<details class="oneway">
<summary>
<span class="hidetext">
Export/download log
</span>
<span class="hiddentext">[collapse and cancel log download]</span>
</summary>
<form action="export_log.php" target="_blank" download="">
<label>
Select file format:
<select name="type">
<option value="text">
Text file (.txt)
</option>
<option value="json">
JSON file (.json)
</option>
</select>
</label>
<br />
<input type="submit" value="Download log" />
</form>
</details>
</details>
</div>