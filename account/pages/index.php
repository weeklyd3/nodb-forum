<<<<<<< HEAD
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
    <title>User Subpages</title>
	<?php
	include('../../public/header.php');
	include('../../styles/inject.php');
	require 'editorlib.php';
	?>
  </head>
  <body>
	<h2>User Subpages (<a href="account/pages/createpage.php">Create page</a>)</h2>
	  <?php 
	$name = isset($_GET['username']) ? (file_exists('../../data/accounts/' . cleanFilename($_GET['username']) . '/psw.txt') ? $_GET['username'] : getname()) : getname();
?>
	  <p><?php 
if ($name === getname()) {
	?>You have write access to these pages.<?php
} else {
	?>You do not have write access, but you may still read the pages.<?php
}
	  ?></p>
	  <div class="youarehere">
	  <?php 
	function formatPath(string $path, string $name) {
		$path = array_values(array_filter(explode("/", $path), function($v) { return $v !== ''; }));
		?><a href="account/pages/index.php?username=<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name) ?>'s Subpages</a> ><?php
		foreach ($path as $index => $item) {
			if (!isset($path[$index + 1])) {
				echo " " . htmlspecialchars($item);
			} else {
				$pathnow = array();
				for ($i = 0; $i <= $index; $i++) {
					array_push($pathnow, $path[$i]);
				}
				$pathnow = implode("/", $pathnow);
				?> <a href="account/pages/index.php?username=<?php echo htmlspecialchars(urlencode($name)); ?>&path=<?php echo htmlspecialchars(urlencode($pathnow)); ?>"><?php echo htmlspecialchars($item); ?></a> ><?php
			}
		}
	}
?>
	  <p>You are here: <?php echo isset($_GET['path']) ? formatPath($_GET['path'], $name) : htmlspecialchars($name) . '\'s Subpages</a>'; ?></p></div>
	<?php 
	$errorMSG = $name . " has no user subpages.";
	$subpageJSON = __DIR__ . '/../../data/accounts/' . cleanFilename($name) . "/subpages.json";
	if (!file_exists($subpageJSON)) {
		exit($errorMSG);
	}
	$subpages = unserialize(base64_decode(file_get_contents($subpageJSON)));
	if (count((array) $subpages) === 0) {
		die("$errorMSG");
	}
$path = isset($_GET['path']) ? $_GET['path'] : '';
		$path = array_filter(explode("/", $path), function($v) { return $v !== ""; });
function validatePath(array $path, $obj, string $baddirmsg) {
	$name = isset($_GET['username']) ? (file_exists('../../data/accounts/' . cleanFilename($_GET['username']) . '/psw.txt') ? $_GET['username'] : getname()) : getname();
	foreach ($path as $index => $item) {
		if (!isset($obj->folders->$item)) {
			if (!isset($path[$index + 1]) && isset($obj->files->$item)) {
				?><h3><?php echo htmlspecialchars($item); ?></h3>
	  			(<b>view</b> | <a href="account/pages/editpage.php?path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>&username=<?php echo htmlspecialchars(urlencode($name)); ?>"><?php echo $name === getname() ? "edit" : "view source"; ?></a> | <a href="account/pages/pagehistory.php?path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>&username=<?php echo htmlspecialchars(urlencode($name)); ?>">view history</a> | <a href="account/pages/createpage.php">new page</a>)
	  			<?php 
				$Parsedown = new Parsedown;
				echo $Parsedown->text($obj->files->$item->contents);
				exit(0);
			}
			die($baddirmsg);
		} else {
			$obj = $obj->folders->$item;
		}
	}
	return $obj;
}
$obj = validatePath($path, $subpages, '<div class="blanket" style="display:block;"><div class="overlay" style="display:block;"><h3>Error</h3><p>The provided directory or file does not exist.</p><p>Please check for typos in the name and the user specified, or <a href="account/pages?username=' . htmlspecialchars(urlencode($name)) . '">return to the root directory</a>.</p></div></div>');
$objone = $subpages;
foreach ($path as $index => $pathitem) {
	if (isset($path[$index + 1])) {
		$objone = $objone->folders->$pathitem;
	} else {
		if (isset($objone->files->$pathitem)) {
			?><h3>Index page for this directory</h3><?php
			$Parsedown = new Parsedown;
			echo $Parsedown->text($objone->files->$pathitem->contents);
		}
	}
}
?>
<h3>Pages and folders in this directory</h3>
<h4>Folders in this directory</h4>
	<?php 
$esname = htmlspecialchars(urlencode($name));
$espath = htmlspecialchars(urlencode(implode('/', $path)));
if (isset($obj->folders)) {
	?><div class="flex"><?php
	foreach ($obj->folders as $title => $folder) {
		$estitle = htmlspecialchars(urlencode($title));
		?><div>
			<img src="img/icons/FolderIcon.png" alt="" />
			<a href="account/pages?username=<?php echo $esname; ?>&path=<?php echo $espath; ?>/<?php echo $estitle; ?>"><?php echo htmlspecialchars($title); ?></a>
		</div><?php
	}
	?></div><?php
} else {
	?><p>This folder has no subfolders.</p><?php
}
?>
<h4>Pages in this directory</h4><?php
if (isset($obj->files)) {
	?><div class="flex"><?php
	foreach ($obj->files as $title => $details) {
		$estitle = htmlspecialchars(urlencode($title));
		?><div>
			<img src="img/icons/PageIcon.png" alt="" />
			<a href="account/pages?username=<?php echo $esname; ?>&path=<?php echo $espath; ?>/<?php echo $estitle; ?>"><?php echo htmlspecialchars($title); ?></a>
		</div><?php
	}
	?></div><?php
} else {
	?><p>This folder has no pages.</p><?php
}
	?>
<style>
	.flex {
		display: flex;
		flex-wrap: wrap;
	}
	.flex div {
		overflow: hidden;
		white-space: nowrap;
		padding: 7px;
		width: 250px;
		max-width: 100%;
		text-overflow: ellipsis;
		border: 1px solid;
		color: black;
		background-color: white;
		border-radius: 3px;
		margin: 3px;
		max-height: calc(1em + 14px);
	}
=======
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
    <title>User Subpages</title>
	<?php
	include('../../public/header.php');
	include('../../styles/inject.php');
	require 'editorlib.php';
	?>
  </head>
  <body>
	<h2>User Subpages (<a href="account/pages/createpage.php">Create page</a>)</h2>
	  <?php 
	$name = isset($_GET['username']) ? (file_exists('../../data/accounts/' . cleanFilename($_GET['username']) . '/psw.txt') ? $_GET['username'] : getname()) : getname();
?>
	  <p><?php 
if ($name === getname()) {
	?>You have write access to these pages.<?php
} else {
	?>You do not have write access, but you may still read the pages.<?php
}
	  ?></p>
	  <div class="youarehere">
	  <?php 
	function formatPath(string $path, string $name) {
		$path = array_values(array_filter(explode("/", $path), function($v) { return $v !== ''; }));
		?><a href="account/pages/index.php?username=<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name) ?>'s Subpages</a> ><?php
		foreach ($path as $index => $item) {
			if (!isset($path[$index + 1])) {
				echo " " . htmlspecialchars($item);
			} else {
				$pathnow = array();
				for ($i = 0; $i <= $index; $i++) {
					array_push($pathnow, $path[$i]);
				}
				$pathnow = implode("/", $pathnow);
				?> <a href="account/pages/index.php?username=<?php echo htmlspecialchars(urlencode($name)); ?>&path=<?php echo htmlspecialchars(urlencode($pathnow)); ?>"><?php echo htmlspecialchars($item); ?></a> ><?php
			}
		}
	}
?>
	  <p>You are here: <?php echo isset($_GET['path']) ? formatPath($_GET['path'], $name) : htmlspecialchars($name) . '\'s Subpages</a>'; ?></p></div>
	<?php 
	$errorMSG = $name . " has no user subpages.";
	$subpageJSON = __DIR__ . '/../../data/accounts/' . cleanFilename($name) . "/subpages.json";
	if (!file_exists($subpageJSON)) {
		exit($errorMSG);
	}
	$subpages = unserialize(base64_decode(file_get_contents($subpageJSON)));
	if (count((array) $subpages) === 0) {
		die("$errorMSG");
	}
$path = isset($_GET['path']) ? $_GET['path'] : '';
		$path = array_filter(explode("/", $path), function($v) { return $v !== ""; });
function validatePath(array $path, $obj, string $baddirmsg) {
	$name = isset($_GET['username']) ? (file_exists('../../data/accounts/' . cleanFilename($_GET['username']) . '/psw.txt') ? $_GET['username'] : getname()) : getname();
	foreach ($path as $index => $item) {
		if (!isset($obj->folders->$item)) {
			if (!isset($path[$index + 1]) && isset($obj->files->$item)) {
				?><h3><?php echo htmlspecialchars($item); ?></h3>
	  			(<b>view</b> | <a href="account/pages/editpage.php?path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>&username=<?php echo htmlspecialchars(urlencode($name)); ?>"><?php echo $name === getname() ? "edit" : "view source"; ?></a> | <a href="account/pages/pagehistory.php?path=<?php echo htmlspecialchars(urlencode(implode("/", $path))); ?>&username=<?php echo htmlspecialchars(urlencode($name)); ?>">view history</a> | <a href="account/pages/createpage.php">new page</a>)
	  			<?php 
				$Parsedown = new Parsedown;
				echo $Parsedown->text($obj->files->$item->contents);
				exit(0);
			}
			die($baddirmsg);
		} else {
			$obj = $obj->folders->$item;
		}
	}
	return $obj;
}
$obj = validatePath($path, $subpages, '<div class="blanket" style="display:block;"><div class="overlay" style="display:block;"><h3>Error</h3><p>The provided directory or file does not exist.</p><p>Please check for typos in the name and the user specified, or <a href="account/pages?username=' . htmlspecialchars(urlencode($name)) . '">return to the root directory</a>.</p></div></div>');
$objone = $subpages;
foreach ($path as $index => $pathitem) {
	if (isset($path[$index + 1])) {
		$objone = $objone->folders->$pathitem;
	} else {
		if (isset($objone->files->$pathitem)) {
			?><h3>Index page for this directory</h3><?php
			$Parsedown = new Parsedown;
			echo $Parsedown->text($objone->files->$pathitem->contents);
		}
	}
}
?>
<h3>Pages and folders in this directory</h3>
<h4>Folders in this directory</h4>
	<?php 
$esname = htmlspecialchars(urlencode($name));
$espath = htmlspecialchars(urlencode(implode('/', $path)));
if (isset($obj->folders)) {
	?><div class="flex"><?php
	foreach ($obj->folders as $title => $folder) {
		$estitle = htmlspecialchars(urlencode($title));
		?><div>
			<img src="img/icons/FolderIcon.png" alt="" />
			<a href="account/pages?username=<?php echo $esname; ?>&path=<?php echo $espath; ?>/<?php echo $estitle; ?>"><?php echo htmlspecialchars($title); ?></a>
		</div><?php
	}
	?></div><?php
} else {
	?><p>This folder has no subfolders.</p><?php
}
?>
<h4>Pages in this directory</h4><?php
if (isset($obj->files)) {
	?><div class="flex"><?php
	foreach ($obj->files as $title => $details) {
		$estitle = htmlspecialchars(urlencode($title));
		?><div>
			<img src="img/icons/PageIcon.png" alt="" />
			<a href="account/pages?username=<?php echo $esname; ?>&path=<?php echo $espath; ?>/<?php echo $estitle; ?>"><?php echo htmlspecialchars($title); ?></a>
		</div><?php
	}
	?></div><?php
} else {
	?><p>This folder has no pages.</p><?php
}
	?>
<style>
	.flex {
		display: flex;
		flex-wrap: wrap;
	}
	.flex div {
		overflow: hidden;
		white-space: nowrap;
		padding: 7px;
		width: 250px;
		max-width: 100%;
		text-overflow: ellipsis;
		border: 1px solid;
		color: black;
		background-color: white;
		border-radius: 3px;
		margin: 3px;
		max-height: calc(1em + 14px);
	}
>>>>>>> 0dd6ba65130b774d8e078ba9c410e6bb02f22f53
</style>