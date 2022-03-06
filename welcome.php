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
  	include_once('./public/header.php'); 
	  include_once('./styles/inject.php');
  ?>
  <title>Getting Started</title>
  </head>
  <body>
  <h2>Getting Started with This Site</h2>
  <p>Thank you for registering on this site. Here are some suggestions on what to do next.</p>
  <style>
  .flex-container {
	  display: flex;
	  width: 100%;
  }
  .flex-container div {
	  margin: 3px;
	  max-width: 33%;
	  background-color: blue;
  }
  </style>
  <div class="flex-container">
  <div><h3><a href="files/md.php">Learn the formatting</a></h3>
  <p><a href="files/md_sandbox.php">Test the Markdown here!</a></p>
  <p>This site uses Markdown, which is a bit different from formatting tags used on other forums.</p>
  </div>
  <div>
	<h3><a href="index.php?sort=active">Browse recently active topics</a></h3>
	<p>You might discover something interesting! Or...</p>
	<p><a href="index.php?sort=random">Give me random topics!</a></p>
	<p>You can get an alphabetical index of the topics by <a href="index.php?sort=abc_asc">using alphabetical sort</a> or <a href="index.php?sort=abc_dsc">reversing the alphabetical source.</a></p>
	<p>If what you're looking for is really old, <a href="search.php">search for it.</a></p>
  </div>
  <div>
	<h3><a href="users.php">Other users</a></h3>
	<p>Good job! By registering, you have now increased the size of the community to <?php echo count(scandir('data/accounts')) - 4; ?> user(s).</p>
	<p>There are <b><?php 
	$others = count(scandir('data/accounts')) - 5;
	echo $others; ?></b> other users.</p>
	<p>
	<?php 
	$levels = array(
		'0-0' => "Oh no, you're alone. This forum needs more users!",
		'1-9' => "There are a few other users. They might respond to your questions.",
		'10-99' => "There are more users on this forum than anyone could memorize. That's an increased chance of getting answers.",
		'99-' . (98 + 23678) => "That's a lot of users! (Well, hoping most of them aren't abandoned...)"
	);
	foreach ($levels as $k => $v) {
		$m = explode("-", $k);
		$r = range($m[0], $m[1]);
		if (in_array($others, $r)) {
			echo $levels[$k];
		}
	}
	?></p>
	  <p>Want to see what's going on right now? <a href="logs.php">View logged actions.</a></p>
  </div>
  <div>
  <h3><a href="faq.php">Read the FAQ document</a></h3>
  <p>It contains some common questions that people have asked.</p></div>
  </div>