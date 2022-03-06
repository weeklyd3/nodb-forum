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
	<base href="../../" />
	<?php 
	include('../public/header.php');
	include('../styles/inject.php');
	if (!isset($_GET['title'])) die("Specify title first");
	?>
    <title>Article: <?php echo htmlspecialchars($_GET['title']); ?></title>
	</head>
	<body>
		<?php if (!file_exists('./content/'.cleanFilename($_GET['title']).'/config.json')) die("Bad title"); ?>
		<h2><?php 
		$config = json_decode(file_get_contents('./content/'.cleanFilename($_GET['title']).'/config.json'));
		echo htmlspecialchars($_GET['title']); ?></h2>
		<?php 
		$Parsedown = new Parsedown;
		echo $Parsedown->text($config->text);
		?>
		<table width="100%">
		<tr>
		<td align="right">
		Created <?php echo date('Y-m-d H:i:s', $config->time); ?><br /><?php echo htmlspecialchars($config->author); ?></td></tr></table>
		<?php include('../public/footer.php'); ?>