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
    <title>User Subpages</title>
	<?php
include ('../../public/header.php');
include ('../../styles/inject.php');
require 'editorlib.php';
?>
  </head>
  <body>
	  <h2>Create a User Subpage</h2>
	<?php
if (!isset($_COOKIE['login'])) {
?>Log in to write subpages<?php
	exit(0);
}
function savePage() {

	if (isset($_POST['title'], $_POST['contents'])) {
?><p>Saving your page... please wait.</p><?php
		if (!file_exists(__DIR__ . '/../../data/accounts/' . cleanFilename(getname()) . '/subpages.json')) $obj = json_decode("{}");
		else $obj = unserialize(base64_decode(file_get_contents(__DIR__ . '/../../data/accounts/' . cleanFilename(getname()) . '/subpages.json')));
		$path = explode("/", $_POST['title']);
		$path = array_values(array_filter($path, function ($m) {
			return $m !== "";
		}));
		$currentobj = $obj;
		$objPath = "";
		foreach ($path as $index => $element) {
			if (!isset($path[$index + 1])) {
				$objPath .= '->files->{' . json_encode($element) . '}';
				if (!isset($currentobj->files)) {
					$currentobj->files = json_decode("{}");
				}
				$currentobj->files->$element = new subPage($element, $_POST['contents']);
				$currentobj = $currentobj->files->$element;
				$GLOBALS['currentobj'] = $currentobj;
				eval('$obj' . $objPath . ' = $currentobj;');
				break;
			}
			else {
				$objPath .= ('->folders->{' . json_encode($element) . '}');
				if (!isset($currentobj->folders)) {
					$currentobj->folders = json_decode('{}');
					$currentobj->folders->$element = new folder($element);

				}
				$currentobj = $currentobj->folders->$element;
			}
			$GLOBALS['currentobj'] = $currentobj;
			eval('$obj' . $objPath . ' = $currentobj;');
		}
		fwrite(fopen("../../data/accounts/" . cleanFilename(getname()) . '/subpages.json', 'w+'), base64_encode(serialize($obj)));
		?><p>Page saved. <a href="<?php echo htmlspecialchars("account/pages/index.php?username=" . urlencode(getname()) . "&path=" . urlencode(implode("/", $path))); ?>">Click to visit it now.</a></p><?php
	}
}
savePage();
?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<label>Enter the path and filename, without the leading slash. If the page already exists, an error will occur.
		<input required="required" type="text" name="title" value="<?php if (isset($_POST['title'])) {
	echo htmlspecialchars($_POST['title']);
} ?>" />
		</label>
		<details><summary>Examples of directories</summary>
			<ul>
				<li><code>/foo</code> or <code>foo</code>: page named <code>foo</code> in root directory</li>
				<li><code>foo/bar</code>: Page <code>bar</code> in directory <code>foo</code>, which also functions as the index page of <code>foo/bar/</code></li>
			</ul></details>
		<?php
$contents = isset($_POST['contents']) ? $_POST['contents'] : "";
userSubpageEditor($contents, false, true);
?>
