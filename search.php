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
    <title>Searching for <?php echo htmlspecialchars($_GET['query']); ?></title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
  <h2>Search Results</h2>
  <p>Your query: <code><?php echo htmlspecialchars($_GET['query']); ?></code></p>
  <p>Your query was interpreted as <code><?php 
  $GLOBALS['terms'] = array_unique(array_filter(explode(" ", strtolower($_GET['query'])), function($v) { return $v !== ""; }));
  $config = json_decode(file_get_contents('config.json'));
  if (isset($config->searchFilter)) $GLOBALS['terms'] = array_diff($terms, $config->filterWords);
  
  echo htmlspecialchars(implode(" ", $terms));
  
  $tags = isset($_GET['tags']) ? $_GET['tags'] : "";
  $tags = array_filter(array_unique(explode(" ", strtolower($tags))), function($v) { return $v !== ""; });
  $GLOBALS['searchtags'] = $tags;
  ?></code></p>
  <p>Results in topics and posts are shown below. If you want to search the whole site, please <a href="https://google.com/search?q=site:<?php echo htmlspecialchars(urlencode($_SERVER['HTTP_HOST'] . " " . $_GET['query'])); ?>">do a Google Search instead.</a></p>
  <?php 
  $GLOBALS['results'] = array();
$startTime = microtime(true);
  // use usort for the search results
  function addResultToSearch(string $room, ?string $post) {
	  $searchtags = $GLOBALS['searchtags'];
	  $postconfig = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($room) . '/config.json'));
	  $posttags = explode(" ", $postconfig->tags);
	  $terms = $GLOBALS['terms'];
	  if (!(array_intersect($searchtags, $posttags) == $searchtags)) return;
	  if (isset($post)) {
		  // Search in msg.json
		  $json = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($room) . '/msg.json'));
		  $msg = $json->$post;
		  $posttext = strip_tags($msg->html);
		  $matches = custom_substr_count($posttext, $terms);
	  } else {
		  $json = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($room) . '/config.json'));
		  $msg = $json;
		  $posttext = strip_tags($msg->description_html);
		  $matches = custom_substr_count($posttext, $terms) + custom_substr_count($msg->title, $terms);
	  }
	  if ($matches > 0) {
		  $author = $msg->author;
		  $deleted = isset($post) ? isset($msg->del) : file_exists(__DIR__ . '/data/messages/' . cleanFilename($room) . '/del.json');
		  array_push($GLOBALS['results'], new searchResult($room, $post, $matches, $posttext, $author, $postconfig->tags, $deleted));
	  }
	  return;
  }
  $rooms = array_diff(scandir(__DIR__ . '/data/messages', SCANDIR_SORT_NONE), array('.', '..', 'index.php'));
  class searchResult {
	  public function __construct(string $room, ?string $post, int $matches, string $text, string $author, string $tags, bool $deleted = false) {
		  $this->room = $room;
		  $this->post = $post;
		  $this->matches = $matches;
		  $this->text = $text;
		  $this->author = $author;
		  $this->deleted = $deleted;
		  $this->tags = $tags;
	  }
  }
  foreach ($rooms as $room) {
	  $roomcfg = json_decode(file_get_contents('data/messages/' . $room . '/config.json'));
	  $name = $roomcfg->title;
	  
	  addResultToSearch($name, null);
	  $posts = json_decode(file_get_contents('data/messages/' . $room . '/msg.json'));
	  foreach ((array) $posts as $id => $post) {
		  addResultToSearch($name, $id);
	  }
  }
  ?><ul style="list-style: none; padding: 0; margin: 0;"><?php 
	$GLOBALS['results'] = array_filter($GLOBALS['results'], function($result) {
		return !($result->deleted && $result->author !== getname() && !verifyAdmin());
	});
  usort($GLOBALS['results'], function($a, $b) {
	  return ($a->matches) - ($b->matches);
  });
  $regex = "/(";
  $regexterms = array();
  foreach ($GLOBALS['terms'] as $term) {
	  array_push($regexterms, preg_quote(htmlspecialchars($term), "/"));
  }
  $regex .= implode("|", $regexterms);
  $regex .= ")/i";
$endTime = microtime(true);
?><p><?php echo count($GLOBALS['results']); ?> result(s) found in <?php echo round(($endTime - $startTime) * 1000, 3); ?> milliseconds.</p>
	  <?php
  foreach (array_reverse($GLOBALS['results']) as $result) {
	  ?><li style="padding-top: 2px; padding-bottom: 2px;">
	  <h3>
	  <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($result->room)); 
	  if (isset($result->post)) {
		  ?>#topic-message-<?php echo htmlspecialchars(urlencode($result->post));
	  }
	  ?>">
	  <?php
	  echo htmlspecialchars($result->room);
	  $flags = array();
	  if (isset($result->post)) {
		  array_push($flags, 'reply');
	  } else {
		  array_push($flags, 'topic');
	  }
		if ($result->deleted) {
			array_push($flags, 'deleted');
		}
	  ?></a> <?php 
			if (count($flags) > 0) {
				echo "(" . implode($flags, ", ") . ")";
			}
			?></h3>
	  <p><?php echo $result->matches; ?> match(es)</p>
	  <p><?php 
	  $previewStr = substr($result->text, custom_stripos($result->text, $GLOBALS['terms']), 300);
	  $previewStrHighlighted = preg_replace($regex, '<strong class="highlight">$0</strong>', $previewStr);
	  echo $previewStrHighlighted;
	  ?></p>
		  <details class="smaller">
			  <summary>Why did this result appear?</summary>
			  <?php 
	  $whyThisResult = array();
	  if (count($tags) > 0) {
		  array_push($whyThisResult, "Th" . (isset($result->post) ? "e parent" : "is") . " topic was tagged " . htmlspecialchars(implode(" ", $tags)) . ' and you searched for tags ' . htmlspecialchars($result->tags));
	  }
	array_push($whyThisResult, "This result had " . $result->matches . " matches with your search terms");
	  array_push($whyThisResult, "You are authorized to view this topic");
	  ?>
	  <ul><li>
		  <?php echo implode("</li><li>", $whyThisResult); ?>
	  </li></ul>
		  </details>
	  </li><?php
  }
    ?></ul><small><em>Can't find what you're looking for? <strong><?php 
  $tips = array("Try less search terms.", "Check for typos.", "Try different search terms.", "Include terms in the posts.", "Use your browser's find on page feature on this page.", "Wait a few minutes if it was recently created.", "It might have been deleted.", "Check the related tags.", "Create it!");
  shuffle($tips);

  echo $tips[array_rand($tips)]; ?></strong></em></small>
  <style>
  .highlight {
	  border-radius: 5px;
	background-color: lime;
	color: black;
  }</style>