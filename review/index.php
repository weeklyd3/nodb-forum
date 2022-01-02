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
    <title>Review</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	?>
  </head>
  <body>
	<?php 
	if (!isset($_COOKIE['login'])) die("<h2>You are not logged in.</h2><p>Log in to review.</p>");
	$items = json_decode(file_get_contents('items.json'));
	$items = $items->items;
	if (count($items) == 0) {
		exit("No items to review.");
	}
	function loadItem($items, $action = null, $test = false) {
		if (isset($action)) {
			if ($action !== "skip") {
				if (!isset($_POST['test'])) {
					if ($action === "lqp") {
						$flag = new stdClass;
						$flag->name = getname();
						$flag->modText = "FROM REVIEW";
						$flag->reason = "Low Quality [From Review]";
						$postinfo = json_decode(file_get_contents("../data/messages/" . cleanFilename($_POST['room']) . '/msg.json'));
						$postinfo->{$_POST['message']}->flags->{getname()} = $flag;
						fwrite(fopen("../data/messages/" . cleanFilename($_POST['room']) . '/msg.json', 'w+'), json_encode($postinfo));
					}
					$item = new stdClass;
					$item->room = $_POST['room'];
					$item->message = $_POST['message'];
					$GLOBALS['item'] = $item;
					$items = array_filter($items, function($v) {
						return !(serialize($v) == serialize($GLOBALS['item']));
					});
					$json = new stdClass;
					$json->items = $items;
					fwrite(fopen('items.json', 'w+'), json_encode($json));
				} else {

				}
			}
		}
		// 1/5 chance of it being a test 
		$range = range(1, 5);
		if (array_rand($range) == 0) $test = true;
		else $test = false;
		// Select a review item at random
		shuffle($items);
		$i = $items[array_rand($items)];
		$GLOBALS['roominfo'] = $i;
		if ($test) $i = null;
		return displayItem($i, $items);
	}
	function displayItem($i, $items) {
		$Parsedown = new Parsedown;
		$contents = array('topictitle' => null, 'topic' => null, 'post' => null);
		if ($i == null) {
			// Generate fake test post
			$fakeurls = array(
				"google.com",
				"microsoft.com",
				"bing.com",
				"github.com",
				"repl.it",
				"localhost"
			);
			$url = random($fakeurls);
			$text = random(array("I have", "We have"));
			$text .= " the best service ever, now featuring a ";
			$text .= random(range(10, 30));
			$text .= "-year " . random(array("guarantee", "warranty")) . "! Visit us now! URL: http://";
			$text .= $url;
			$test = true;
			$title = "The " . random(array("absolute", "")) . " best service " . random(array("PROMISE!", "GUARANTEED", "100%")) . "[LINK ATTACHED]";
			$contents['post'] = $Parsedown->text("# " . $title . "\n$text");
			$topictitle = random(array("Program", "Code")) . " will not compile correctly";
			$topic = $Parsedown->text("Why does the below code trigger the error `console.log(...) is not a function`?\n\n    console.log('foo')\n    (function(){})();");
			$contents['topictitle'] = $topictitle;
			$contents['topic'] = $topic;
		} else {
			// Show the actual post now
			$topicinfo = json_decode(file_get_contents("../data/messages/" . cleanFilename($i->room) . '/config.json'));
			$contents['topictitle'] = $topicinfo->title;
			$contents['topic'] = $topicinfo->description_html;
			$postinfo = json_decode(file_get_contents("../data/messages/" . cleanFilename($i->room) . '/msg.json'));
			$postid = $i->message;
			$contents['post'] = $postinfo->$postid->html;
		}
		?><h2>Review</h2>
		<p><?php echo count($items); ?> item(s) left!</p>
		<details>
		<summary>Expand topic view</summary>
		<h3>
		<?php 
		echo htmlspecialchars($contents['topictitle']);
		?></h3>
		<?php echo $contents['topic']; ?>
		</details>
		<h3>Review this comment:</h3>
		<div class="box">
		<?php
		echo $contents['post'];
		?>
		</div>
		<?php
		return $i == null;
	}
	$tested = loadItem($items, isset($_POST['action']) ? $_POST['action'] : null);
	?>
	<!-- A note to everyone about the test system:

	You may have noticed the hidden field saying
	if an item is a test. This is purely intentional;
	the test system is only for people who mindlessly
	press an option without looking at anything just 
	to raise the review count.

	Nothing to be worried about. (That's why the topic 
	is hidden behind a <details>/<summary>.)
	-->
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset>
	<legend>Choose an action</legend>
	<?php 
	if ($tested) {
		?><input type="hidden" name="test" value="" /><?php
	}
	?>
	<input type="hidden" name="room" value="<?php echo htmlspecialchars($GLOBALS['roominfo']->room); ?>" />
	<input type="hidden" name="message" value="<?php echo htmlspecialchars($GLOBALS['roominfo']->message); ?>" />
	<label class="heading"><input type="radio" name="action" value="ok" value="ok" /> No action needed</label>
	<div>This comment is relevant to the question and is high-quality. (Note: flagging it in a new tab does not make "no action needed" okay!)</div>
	<label class="heading"><input type="radio" name="action" value="lqp"> Low Quality</label>
	<div>This post is low quality and should be flagged.</div>
	<label class="heading"><input type="radio" name="action" value="skip"> Next Task</label>
	<div>Not sure? Select this and let someone else review it.</div>
	</fieldset>
	<input type="submit" />
	</form>
	<style>label.heading {font-weight: bold; font-size: 125%;}</style>