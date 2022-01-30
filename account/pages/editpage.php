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
    <base href="../../" />
    <title>Edit User Subpages</title>
	<?php
	include('../../public/header.php');
	include('../../styles/inject.php');
	require 'editorlib.php';
	?>
  </head>
  <body>
<?php 
if (!isset($_GET['path'], $_GET['username'])) die("You must specify a username and path.");

$datapath = __DIR__ . '/../../data/accounts/' . cleanFilename($_GET['username']) . '/subpages.json';
if (!file_exists($datapath)) {
	die("Your input was invalid.");
}
$obj = unserialize(base64_decode(file_get_contents($datapath)));
$path = array_filter(explode("/", $_GET['path']), function($v) { return $v !== ''; });
if (!checkIfPageExists($path, $obj)) {
	die("The specified page does not exist.");
}
$info = checkIfPageExists($path, $obj);
$readonly = getname() !== $_GET['username'];
if (isset($_POST['contents'])) {
	if (!$readonly) {
		?><p>Saving your page...</p><?php
	}
}
?>
<h2><?php echo $readonly ? "View source for " : "Edit "; echo htmlspecialchars($info->title); ?></h2>
<?php 
if ($readonly) {
	?><p>You do not have permission to edit this page, however, you can still view its source.</p><?php
}
?>
<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<?php 
userSubpageEditor($info->contents, $readonly);
?>
</form>