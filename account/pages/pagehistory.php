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
    <base href="../../" />
    <title>User Subpage History</title>
	<?php
	include('../../public/header.php');
	include('../../styles/inject.php');
	require 'editorlib.php';
	?>
  </head>
  <body>
	  <?php 
if (!isset($_GET['username'], $_GET['path'])) {
	?><h2>Please enter a user and path.</h2><p> <a href="account/pages">You can also return home.</a></p><?php
	exit(0);
}
	$datapath = __DIR__ . '/../../data/accounts/' . cleanFilename($_GET['username']) . '/subpages.json';
$failmsg = '<h2>Invalid input.</h2><p>No such subpage.</p>';
if (!file_exists($datapath)) {
	exit($failmsg);
}
require '../../libraries/diff.php';
	$ser = unserialize(base64_decode(file_get_contents($datapath)));
$path = array_filter(explode("/", $_GET['path']), function($v) { return $v !== ''; });
if (checkIfPageExists($path, $ser) === false) {
	?><h2>No such subpage</h2><p>That page does not exist.</p><?php
	exit(0);
}
$subpage = checkIfPageExists($path, $ser);
$revisions = $subpage->revisions;
	?>
	<h2>Page History</h2>
	  (<a href="account/pages?username=<?php echo htmlspecialchars(urlencode($_GET['username'])); ?>&path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>">view</a> | <a href="account/pages/editpage.php?path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>&username=<?php echo htmlspecialchars(urlencode($_GET['username'])); ?>"><?php echo $_GET['username'] === getname() ? "edit" : "view source"; ?></a> | <b>view history</b> | <a href="account/pages/createpage.php">new page</a>)
	  <ul>
<?php
$Parsedown = new Parsedown;
foreach ($revisions as $number => $revision) {
	?><li><details><summary><?php echo friendlyDate($revision->time); ?>: (<?php echo colorChange(strlen($revision->contents) -
	strlen(isset($revisions[$number + 1])
		? $revisions[$number + 1]->contents
		: "")); ?>): <i style="color:#eeeeee;"><?php echo htmlspecialchars($revision->summary); ?></i></summary>
	<h3>Page at this time</h3>
		<details>
			<summary>Show page contents</summary>
		<div class="smaller box"><?php echo $Parsedown->text($revision->contents); ?></div></details>
		<h3>Page source at this time</h3>
		<details><summary>View source</summary><pre class="smaller box markdown"><code style="display: block;"><?php echo htmlspecialchars($revision->contents); ?></code></pre></details>
		<h3>Difference</h3>
		<details><summary>Show diff</summary><?php diff(isset($revisions[$number + 1])
		? $revisions[$number + 1]->contents
		: "", $revision->contents); ?></details>
	</details></li><?php
}
?></ul>