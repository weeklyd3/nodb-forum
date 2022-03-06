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
    <title>Uploaded Files</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
<h2>All Uploaded Files</h2>
<p>This is a list of all the files that have been uploaded. These works are licensed under the <a href="http://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike 4.0 International License</a>, so you can use them under <a href="http://creativecommons.org/licenses/by-sa/4.0/#deed-conditions" target="_blank">the terms</a>. <strong>Violation of the license will terminate your rights to these works!</strong></p>
	  <p>Want to contribute your own work? <a class="fakebutton" href="files/">Upload files</a></p>
<table class="table">
	<tr>
		<td style="background-color:blue;text-align:center;" colspan="6">List of files</td>
	</tr>
	<tr>
		<th>Name</th>
		<th>Extension</th>
		<th>Modified</th>
		<th>Download</th>
		<th>Open in new tab</th>
		<th>View Online</th>
	</tr>
	<?php 
		$files = scandir("uploads/", SCANDIR_SORT_NONE);
		$files = array_diff($files, array("sample.txt", ".", ".."));
		foreach ($files as $file) {
			$info = pathinfo("uploads/" . $file);
			?><tr>
				<td><?php echo htmlspecialchars($info['filename']); ?></td>
				<td>.<?php echo htmlspecialchars($info['extension']); ?></td>
				<td><?php echo friendlyDate(filemtime("uploads/" . $file)); ?></td>
				<td><a href="files/download.php?filename=<?php echo htmlspecialchars(urlencode($file)); ?>" download="<?php echo htmlspecialchars($file); ?>">Download</a></td>
				<td><a href="files/download.php?filename=<?php echo htmlspecialchars(urlencode($file)); ?>" target="_blank">Open</a></td>
				<td><a href="viewfile.php?filename=<?php echo htmlspecialchars(urlencode($file)); ?>" target="_blank">View</a></td>
			</tr><?php
		}
	?>
</table>