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
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
	$Parsedown = new Parsedown;
	
	if (!isset($_GET['tag'])) die("No tag specified"); ?>
    <title>Forums &mdash; Topics Tagged "<?php echo htmlspecialchars($_GET['tag']); ?>"</title>
  </head>
  <body>
	<h2>Showing: Topics Tagged <span class="tag"><?php echo htmlspecialchars($_GET['tag']); ?></span> (<a href="tags.php">all tags</a>)</h2>
	<h3>About This Tag</h3>
	<a href="edit_tag.php?tag=<?php echo htmlspecialchars($_GET['tag']); ?>"><em><small>improve tag info</small></em></a>
	<div>
		<h4>Short description</h4>
		<div style="border:1px solid; padding:3px;white-space:pre-wrap;"><?php 
		$tagName = $_GET['tag'];
		$config = json_decode(file_get_contents('config.json'));
		if (isset($config->descriptions->$tagName)) {
			echo htmlspecialchars($config->descriptions->$tagName->short);
			?></div><hr /><details><summary>More information</summary><div style="border:1px solid;padding:3px;"><?php
			echo $Parsedown->text($config->descriptions->$tagName->long);
			echo '</div></details>';
			?><script>hljs.highlightAll();</script><?php
		} else {
			?> No info yet. Let's <a href="edit_tag.php?tag=<?php echo htmlspecialchars($tagName); ?>">create it!</a><?php
		}
		?>
	</div>
	<ul style="list-style:none;padding:0;">
		<?php 
			$all = scandir("./data/messages");
			natcasesort($all);
			$names = array();
			foreach ($all as $key => $value) {
				$config = json_decode(file_get_contents('./data/messages/'.$value.'/config.json'));
				$tags = $config->tags;
				$tags = explode(" ", $tags);
				if (in_array($_GET['tag'], $tags)) {
					?>
					<li style="background-color:white;color:black;border-radius:3px;"><h3><a href="webchat.php?room=<?php echo htmlspecialchars($config->title); ?>"><?php echo htmlspecialchars($config->title); ?></a></h3>
					Tagged <?php 
						foreach ($tags as $tag) {
							?><span class="tag"<?php 
							if ($tag == $_GET['tag']){echo ' style="font-weight:bold;"';} ?>><a href="tagged.php?tag=<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></a></span> <?php
							array_push($names, 'a');
						}
					?>
					 <br /><br />Created <?php
							echo date('Y-m-d H:i:s', $config->creationTime); ?> by <?php
							echo htmlspecialchars($config->author); ?>.
					</li><?php
				}
			}
			if ($names == array()) {
				?>There are no topics tagged <span class="tag"><?php echo htmlspecialchars($_GET['tag']); ?></span>. <a href="tags.php">Click to view all tags</a><?php
			}
		?>
	</ul>
	<?php include('./public/footer.php'); ?>