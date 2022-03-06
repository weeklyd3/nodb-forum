<?php include_once('header.php'); ?>
<h2>Flags</h2>
<p>Below is a list of all topic flags. Better moderate them until they reach a million entries!</p>
<a href="app/tools/reviewmessagereports.php">Review private message reports</a>
<details open="open">
<summary>Topics</summary>
<ul>
	<?php 
		$stack = array();
		chdir(__DIR__ . '/../../data/messages/');
		$files = array_diff(scandir(".", SCANDIR_SORT_NONE), array('index.php', '.', '..'));
		foreach ($files as $topic) {
			$config = json_decode(file_get_contents('./' . $topic . '/config.json'));
			if (isset($config->flags)) {
				array_push($stack, 'a');
				?><li><details><summary>Topic: <a href="viewtopic.php?room=<?php echo htmlspecialchars(urlencode($config->title)); ?>"><?php echo htmlspecialchars($config->title); ?></a></summary><ul><?php 
					foreach ((array) $config->flags as $author => $flag) {
						if (isset($flag->helpful)) continue;
						?><li><strong><?php 
							echo htmlspecialchars($author);
						?></strong>: <?php 
							echo htmlspecialchars($flag->reason);

							if (isset($flag->modText)) {
								?><details><summary>Text of flag</summary><pre><?php echo htmlspecialchars($flag->modText); ?></pre></details><?php
							}
						?> (<a href="app/tools/close_report.php?room=<?php echo htmlspecialchars(urlencode($topic)); ?>&user=<?php echo htmlspecialchars(urlencode($author)); ?>">close</a>)</li><?php
					}
				?></ul></details></li><?php
			}
		}
		if (count($stack) == 0) { ?> <h3>This queue has been cleared!</h3><p>No review tasks currently. <a href=".">Go back to the home page?</a></p> <?php }
	?>
</ul>
</details>
<details>
<summary>Posts</summary><?php
$msgs = (array) json_decode(file_get_contents('postflags.json'));
		if (count($msgs) == 0) { ?> <h3>This queue has been cleared!</h3><p>No review tasks currently. <a href=".">Go back to the home page?</a></p> <?php } else {
			?><ul><?php
			foreach ($msgs as $topic) {
				$topicconfig = json_decode(file_get_contents(__DIR__ . '/../../data/messages/' . cleanFilename($topic) . '/config.json'));
				$estopicname = htmlspecialchars(urlencode($topicconfig->title));
				$topicmsgs = json_decode(file_get_contents(__DIR__ . '/../../data/messages/' . cleanFilename($topic) . '/msg.json'));
				?><li><details><summary>Topic: <b><?php echo htmlspecialchars($topicconfig->title); ?></b> (<a href="viewtopic.php?room=<?php echo $estopicname; ?>">open topic</a>)</summary><ul>
				
				<?php 
				foreach ((array) $topic as $post => $flags) {
					?><li>(<a href="viewtopic.php?room=<?php echo $estopicname; ?>#topic-message-<?php echo htmlspecialchars(urlencode($post)); ?>">open post)</a>
					<ul>
						<?php foreach ((array) $flags as $user => $flag) {
						?><li><a href="app/tools/close_report.php?room=<?php echo $estopicname; ?>&user=<?php echo htmlspecialchars(urlencode($user)); ?>&post=<?php echo htmlspecialchars(urlencode($post)); ?>">(close)</a>: <strong><?php echo htmlspecialchars($flag->reason); ?></strong><?php if (isset($flag->junkreason)) echo " (" . htmlspecialchars($flag->junkreason) . ')'; ?></li><?php
} ?>
					</ul>
					</li><?php
				}
				?></ul></details></li><?php
			}
		}
	?></ul>
</details>