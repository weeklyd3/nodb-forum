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
    <title>List of Polls</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	require_once '../libraries/formfuncs.php';
	if (!file_exists(__DIR__ . '/polls.json')) {
		die("<h2>Invalid details.</h2><p>Poll file not found.");
	}
	?>
  </head>
  <body>
  <h2>Index of polls</h2>
	  <?php require 'header.php'; ?>
  <p>All the polls, indexed by user and creation date.</p>
  <table class="table exempt-from-format width-100" width="100%">
  <tr>
  <th>User</th><th>Poll</th></tr>
  <?php 
  function getPollList() {
	$polls = json_decode(file_get_contents(__DIR__ . '/polls.json'), true);
	$result = array();
	foreach ($polls as $user => $polls) {
		$result[$user] = array();
		foreach ($polls as $id => $poll) {
			$result[$user][$id] = $poll;
		}
	}
	return $result;
  }
  $polls = getPollList();

  foreach ($polls as $author => $polls) {
	  ?><tr><td rowspan="<?php echo count($polls);
	  ?>"><?php echo htmlspecialchars($author); 
	  ?></td><?php 
	  foreach ($polls as $id => $obj) {
		  ?><td><?php 
		  formatPollLink($id, $author, $obj['title']);
		  ?></td></tr><tr><?php
	  }
	  ?><td colspan="2">--end--</td></tr><?php
  }
  ?>
  </table>
