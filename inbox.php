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
	<?php
	include_once('./styles/inject.php');
	include_once('./libraries/lib.php');
	
	if (!isset($_COOKIE['login'])) die("Log in to view inbox"); ?>
    <title>Inbox</title>
	</head>
	<body>
		<ul style="list-style:none;padding:0;"><?php 
			if (!file_exists(__DIR__ . '/data/accounts/' . cleanFilename(getname()) . '/inbox.json')) die("You have no inbox items.");
			$a = json_decode(file_get_contents(__DIR__ . '/data/accounts/' . cleanFilename(getname()) . '/inbox.json'));
			foreach (array_reverse($a->items) as &$i) {
				?><li style="border:1px solid;<?php if (!$i->read) { ?> font-weight:bold;background-color:#87CEEB;<?php } ?>"><a href="<?php echo htmlspecialchars($i->url); ?>" style="text-decoration:none;" target="_blank"><?php echo friendlyDate($i->time); ?><h2><?php echo htmlspecialchars($i->type); ?></h2><?php echo htmlspecialchars($i->text); ?></a></li><?php
				$i->read = true;
			}
			if (count($a->items) === 0) {
				?>You have no notifications :( <?php
			}
			fwrite(fopen(__DIR__ . '/data/accounts/' . cleanFilename(getname()) . '/inbox.json', 'w+'), json_encode($a));
		?></ul>