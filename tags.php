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
	$json = json_decode(file_get_contents('config.json'));
	?>
    <title>Forums &mdash; Tag Index</title>
  </head>
  <body>
	<h2>Tag Index (<a href="#creation">jump down</a>)</h2>
	<p><strong>Tags</strong> categorize topics, making it easier to find topics in subjects that you like. For example, if you like <span class="tag">php</span>, you might check back frequently on the <span class="tag">php</span> tag to check for new topics.</p>
	<p><strong>READ BEFORE CREATING NEW TAGS</strong>: We don't like duplicate tags, so make sure there is no tag that expresses your topic. Do not create <span class="tag">flex-box-css</span> if there is a tag called <span class="tag">flexbox</span>!</p>
	<h3>All the tags in the family (<?php echo count($json->tags) - 1; ?>)</h3>
	<div style="display: flex;flex-wrap: wrap;justify-content: center;align-items: center;align-content: center;flex-direction: column;">
		<?php 
		foreach ($json->tags as $key => $value) {
				if ($value != "") {
			?><div style="margin: 3px;"><span class="tag"><a href="tagged.php?tag=<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($value); ?></a></span> </div><?php
			}
		}
		?>
	</div>
	<div id="creation">
		<h3>Creating tags</h3>
		Type a nonexistent tag name into the box, and it will be created when the topic is created.
	</div>
	<?php include('./public/footer.php'); ?>