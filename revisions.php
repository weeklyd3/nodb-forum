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
    <title>Topic Revisions</title>
	<?php
	include('./public/header.php');
	include('./styles/inject.php');
require 'libraries/diff.php';
	if (!isset($_GET['topic'])) die("Specify room first");
	if (!file_exists(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/config.json')) die("Bad title");
	$config = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/config.json'));
	$postcontent = json_decode(file_get_contents(__DIR__ . '/data/messages/'.cleanFilename($_GET['topic']).'/msg.json'));
if (isset($_GET['post']) && isset($postcontent->{$_GET['post']})) $config = $postcontent->{$_GET['post']};
	?>  </head>
  <body>
  <h2>Revisions</h2>
  <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($_GET['topic'])); ?>">Return to topic</a>
  <?php 
  if (!isset($config->revisions)) {
	  ?><details><summary>1. <strong>Original version</strong> by <a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($config->author)); ?>"><?php echo htmlspecialchars($config->author); ?></a> on <?php echo date("Y-m-d H:i:s", $config->creationTime); ?></summary><?php echo $config->description_html; ?><details><summary>Source</summary><pre><code class="lang-markdown"><?php echo htmlspecialchars($config->description); ?></code></pre></details></details><?php
  } else {
	  $revs = array_reverse($config->revisions);

	  foreach ($revs as $index => $revision) {
		  ?><details><summary><?php echo count($revs) - $index; ?>. <strong><?php echo htmlspecialchars($revision->summary); ?></strong> by <a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($revision->author)); ?>"><?php echo htmlspecialchars($revision->author); ?></a> on <?php echo date("Y-m-d H:i:s", $revision->time); ?></summary><?php echo $revision->html; ?><details><summary>Source</summary><pre><code class="lang-markdown"><?php echo htmlspecialchars($revision->text); ?></code></pre></details>
		  <?php if (isset($revs[$index + 1])) { ?><details>
			  <summary>Diff</summary>
			  <?php 
				  $pre = $revs[$index+1];
				  diff($pre->text, $revision->text);
			  ?>
		  </details>
		  <?php } ?></details><?php
	  }
  }
  include('./public/footer.php');
  ?><script>hljs.highlightAll();</script>