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
ini_set('display_errors', 1);
error_reporting(E_ALL);
?><html lang="en">
  <head>
    <title>Searching for <?php echo htmlspecialchars($_GET['query']); ?></title>
	<?php
	include_once('./public/header.php');
	include_once('./styles/inject.php');
	?>
  </head>
  <body>
  <?php 
  	$searchterms = array_unique(
		array_filter(explode(" ", $_GET['query']),
		function($string) {
			return !($string === '');
		})
	);
	if (isset($object->searchFilter)) $searchterms = array_values(array_udiff($searchterms, $obj->filterWords, 'strcasecmp'));
  if (!isset($_GET['query']) || $_GET['query'] == '' || (isset($_GET['query']) && count($searchterms) === 0)) {
	  ?><!-- no query -->
	  <h2>Search Here</h2>
	  <p>Either you entered nothing, or your search consisted only of too common words.</p>
	  <center>
	  <form action="search.php" method="GET">
	  <label style="display:none;" for="query">Search query:</label><input type="search" id="query" name="query" placeholder="What are you looking for?" style="font-size:30px; width: 80%; text-align:center;"/>
	  <input type="submit" value="&gt;" style="font-size:30px;" />
	  </form>
	  </center>
	  <h2>How to Search</h2>
	  <p>Enter your search terms, split by spaces, and press <kbd>></kbd> or hit <kbd>Enter</kbd>.</p>
	  <?php
	  include_once('./public/footer.php');
	  exit(0);
  }
  function getmsg($room) {
	  $config = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($room) . '/config.json'));
	  $string = $config->author . ' on ' . friendlyDate($config->creationTime) . ' [first message]: ' . strip_tags($config->description_html) . ', ';
	  $msgs = json_decode(file_get_contents(__DIR__ . '/data/messages/' . cleanFilename($room) . '/msg.json'));
	  foreach ($msgs as $msg) {
		  $string .= $msg->author . " on " . friendlyDate($msg->time) . ': ' . strip_tags($msg->html) . ", ";
	  }
	  return $string;
  }
  ?>
  <table style="width:100%;">
  <tr style="background-color:#f1c1e6;"><td>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</td></tr>
  <?php 
	$entries = array_diff(scandir(__DIR__ . '/data/messages', SCANDIR_SORT_NONE), array('..', '.', 'index.php'));

	$results = array();
	// Change dir to avoid many occurrences of
	//     __DIR__ . '/data/messages'
	// in the code.
	chdir(__DIR__ . '/data/messages/');
	foreach ($entries as $entry) {
		if (!is_dir($entry)) continue;
		$config = json_decode(file_get_contents($entry . '/config.json'));
		$titleContains = contains($config->title, $searchterms);
		$bodyContains = custom_substr_count(html_entity_decode(getmsg($entry)), $searchterms);
		if ($titleContains || ($bodyContains != 0)) {
			$results[$config->title] = $bodyContains;
		}
	}
    arsort($results, SORT_NUMERIC);

	$page = isset($_GET['page']) ? (is_numeric($_GET['page']) ? $_GET['page'] : 1) : 1;
	$pagesize = 15;

	foreach ($results as $roomname => $value) {
		$config = json_decode(file_get_contents(cleanFilename($roomname) . '/config.json'));
		$del = file_exists(cleanFilename($roomname) . '/del.json');
		if ($del) {
			if (!verifyAdmin() && $config->author !== getname()) continue;
		}
		?><tr class="found"<?php 
			if ($del) {
				?> style="background-color:rgb(255, 221, 221);"<?php
			}
		?>><td><h3><a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($config->title)); ?>"><?php echo htmlspecialchars($config->title); ?></a> (match count: <?php echo custom_substr_count(getmsg($roomname), $searchterms); ?>)</h3><p><?php
			$msgs = html_entity_decode(getmsg($roomname));
			$pos = custom_stripos($msgs, $searchterms);
			if ($pos > 30) $view = substr($msgs, $pos - 30, 400);
			if ($pos <= 30) $view = substr($msgs, 0, 400);
			$hlterms = array_map(function($input) {
				return preg_quote(htmlspecialchars($input), '/');
			}, $searchterms);
			$regex = "/(" . implode("|", $hlterms) . ")/i";
			$preg = preg_replace($regex, '<span class="hit">$0</span>', htmlspecialchars($view));
			echo $preg;

		?></p></td></tr><?php
	}
  ?>
  <tr style="background-color:#f1c1e6;"><td>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</td></tr>
  </table>
  <small><em>Can't find what you're looking for? <strong><?php 
  $tips = array("Try less search terms.", "Check for typos.", "Try different search terms.", "Include terms in the posts.", "Use your browser's find on page feature on this page.", "Wait a few minutes if it was recently created.", "It might have been deleted.", "Check the related tags.", "Create it!");
  shuffle($tips);

  echo $tips[array_rand($tips)]; ?></strong></em></small>
  <style>
  body, html {
	background: rgb(218,166,245);
	background: -moz-linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	background: -webkit-linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	background: linear-gradient(0deg, rgba(218,166,245,1) 0%, rgba(45,231,253,1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#daa6f5",endColorstr="#2de7fd",GradientType=1);
}
.hit {
	background-color: #00ff00;
	font-weight: bold;
	border-radius: 3px;
}
.found {
	background-color: white;
	color: black;
	border-radius: 3px;
}
</style>
<script> 
function clearMark() {
	const links = document.querySelectorAll('span.hit');
	for (var i = 0; i < links.length; i++) {
		links[i].removeAttribute('class');
	}
}
</script>
<a href="javascript:;" onclick="clearMark()">Clear all marks</a>
<?php 
chdir(__DIR__);