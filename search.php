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
?><html>
  <head>
    <title>Searching for <?php echo htmlspecialchars($_GET['query']); ?></title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	?>
  </head>
  <body>
  <?php if (!isset($_GET['query']) || $_GET['query'] == '') {
	  echo "Type query first.";
	  include('./public/footer.php');
	  exit(0);
  }
  ?>
  <table style="width:100%;">
  <tr style="background-color:#f1c1e6;"><td>Search Results for "<?php echo htmlspecialchars($_GET['query']); ?>"</td></tr>
  <?php 
		if ($handle = scandir('data/messages')) {
			natcasesort($handle);
			$stuff = array();
			foreach ($handle as $key => $entry)
				if (custom_substr_count(strtoupper(strip_tags(file_get_contents('data/messages/'.$entry.'/webchat.txt'))), explode(" ", $_GET['query'])) > 0) $stuff[(string) custom_substr_count(strtoupper(strip_tags(file_get_contents('data/messages/'.$entry.'/webchat.txt'))), explode(" ", $_GET['query']))] = $entry;
			ksort($stuff);
			$stuff = array_reverse($stuff);
		foreach ($stuff as $key => $entry) {
			if (!file_exists('data/messages/'.$entry.'/config.json')) continue;
			$name = json_decode(file_get_contents("data/messages/".$entry."/config.json"));
			if ((!empty($name->title) && 
				$entry != "" && $entry != "." && $entry != ".." 
				&& file_exists('data/messages/'.$entry.'/config.json')
				 && (contains($name->title, explode(" ", $_GET['query'])) || contains($name->description, explode(" ", $_GET['query'])))) || $_GET['query'] == "") {

				echo '<tr><td style="background:white;color:black;border-radius:3px;"><h3><a href="webchat.php?room='.htmlspecialchars($name->title).'">'.htmlspecialchars($name->title)."</a>";
				?> <em><?php echo custom_substr_count(strip_tags(file_get_contents('data/messages/'.$entry.'/webchat.txt')), explode(" ", $_GET['query'])); ?> match(es)</em><?php
				echo "</h3>";
				?><ul><li>Created <?php
				echo date('Y-m-d H:i:s', $name->creationTime);
				?></li><li>by <?php
				echo htmlspecialchars($name->author);
				?></li><li><strong>Hit:</strong><br /><?php 
					$chat = htmlspecialchars_decode(strip_tags(file_get_contents('data/messages/'.$entry.'/webchat.txt')));
					$chat = str_ireplace(array("\r", "\n", "\r\n"), " ", $chat);
					$index = stripos($chat, $_GET['query']) ? stripos($chat, $_GET['query']) : 0;
					$max = 50;
					if ($index < $max)
						$index = 0;
					else 
						$index -= $max;
					$extract = substr($chat, $index, 750);
					$regexp = array();
					foreach (explode(" ", $_GET['query']) as $value) 
						array_push($regexp, preg_quote($value, '/'));
					$regexp = implode("|", $regexp);
					echo "<!-- regular expression is /(".htmlspecialchars($regexp).')/i -->';
					$extract = preg_replace("/(".$regexp.")/i", "<span style=\"font-weight:bold;background-color:lightblue;border-radius:3px;\" class=\"hit\">$0</span>", $extract);
					echo $extract;
				?></li></ul><?php
				echo "</td></tr>";
			}
		}

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
}</style>
<script> 
function clearMark() {
	const links = document.getElementsByClassName('hit');
	for (const value of links) {
		value.setAttribute('style', '');
	}
}
</script>
<a href="javascript:;" onclick="clearMark()">Clear all marks</a>
<?php include('./public/footer.php'); ?>