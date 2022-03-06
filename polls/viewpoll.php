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
    <title>View Poll</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	require_once '../libraries/formfuncs.php';
	if (!isset($_COOKIE['login'])) die("<h2>You are not logged in.</h2><p>Log in to take polls.</p>");
	if (!isset($_GET['user'], $_GET['id'])) die("<h2>Invalid details.</h2><p>Specify user and id.</p>");
	if (!file_exists(__DIR__ . '/polls.json')) {
		die("<h2>Invalid details.</h2><p>Poll file not found.");
	}
	?>
  </head>
  <body>
  <h2><?php 
	$name = $_GET['user'];
	$id = $_GET['id'];
	$user = getname();
  	$json = json_decode(file_get_contents(__DIR__ . '/polls.json'), true);
  echo htmlspecialchars($json[$name][$id]['title']); ?></h2>
  <?php 
  $Parsedown = new Parsedown;
  echo $Parsedown->text($json[$name][$id]['description']);
  ?><hr /><?php
  if (isset($json[$name][$id]['responses'][getname()])) exit("You have already submitted.");
    function response() {
	$name = $_GET['user'];
	$id = $_GET['id'];
	$user = getname();
	$ballot = new stdClass;
	foreach ($_POST as $key => $value) {
	  if (count(explode("-", $key)) !== 2) continue;
	  $num = explode("-", $key)[1];
	  if (!is_numeric($num)) continue;
	  $ballot->$num = $value;
	}
	$j = json_decode(file_get_contents(__DIR__ . '/polls.json'));
	
	if (!isset($j->$name->$id->responses)) $j->$name->$id->responses = new stdClass;
	$j->$name->$id->responses->$user = new stdClass;
	$j->$name->$id->responses->$user = $ballot;
	fwrite(fopen(__DIR__ . '/polls.json', 'w+'), json_encode($j));
	?><p>Your response has been submitted. (<a href="polls/poll.php?user=<?php echo htmlspecialchars(urlencode($_GET['user'])); ?>&id=<?php echo htmlspecialchars(urlencode($_GET['id'])); ?>">view results</a>)</p><?php 
	exit(0);
  }
  if (count($_POST) !== 0) response();
  ?>
	  <p>Submitting response to <strong><?php echo htmlspecialchars($json[$name][$id]['title']); ?></strong> (public) as <strong><?php echo htmlspecialchars(getname()); ?></strong> (username private)</p>
  <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="post">
	<?php 
	$name = $_GET['user'];
	$id = $_GET['id'];
	if (!isset($json[$name][$id]['questions'])) die("<h2>Invalid details.</h2><p>Invalid name or id.</p>");
	viewForm($json[$name][$id]['questions']);
	?><hr /><h3>Done?</h3><p>Your response will be publicly viewable after you submit the form, although it will not be associated with your account. Make sure there is no personal information in this form before submitting it.</p><p>Click to submit: <input type="submit" value="Save response" /></p>
	<p><small>This content is user-submitted, and the owner of this site does not endorse this content.</small></p>