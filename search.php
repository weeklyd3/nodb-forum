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
?><html>
  <head>
    <title>Forums &mdash; Searching for <?php echo htmlspecialchars($_GET['query']); ?></title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <table style="width:100%;">
  <tr style="background-color:#f1c1e6;"><td>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</td></tr>
  <?php 
		if ($handle = scandir('data/messages')) {
			natcasesort($handle);
		foreach ($handle as $key => $entry) {
			$name = json_decode(file_get_contents("data/messages/".$entry."/config.json"));
			if ((!empty($name->title) && $entry != "" && $entry != "." && $entry != ".." && file_exists('data/messages/'.$entry.'/config.json') && (stripos($name->title, $_GET['query']) !== false)) || $_GET['query'] == "") {

				echo '<tr><td><a href="webchat.php?room='.htmlspecialchars($name->title).'">'.htmlspecialchars($name->title)."</a></td></tr>";
			}
		}

	}
  ?>
  <tr style="background-color:#f1c1e6;"><td>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</td></tr>
  </table>
  <style>
  body, html {
	background: rgb(218,166,245);
	background: -moz-linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	background: -webkit-linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	background: linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#daa6f5",endColorstr="#2de7fd",GradientType=1);
}</style>