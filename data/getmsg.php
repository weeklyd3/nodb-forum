<?php 
function getMsg($room) {
	include_once(__DIR__ . '/../libraries/lib.php');

	$config = json_decode(file_get_contents(__DIR__ . '/../data/messages/'.cleanFilename($room) . '/config.json'));
	?>
	<div id="topic-firstpost"><?php 

		echo $config->description_html;
		$msgs = (array) json_decode(file_get_contents(__DIR__ . '/../data/messages/'.cleanFilename($room) . '/msg.json'));
	?>
	<p>
	<?php 
		foreach (explode(" ", $config->tags) as $key => $value) {
			?><span class="tag"><a href="tagged.php?tag=<?php echo htmlspecialchars(urlencode($value)); ?>"><?php echo htmlspecialchars($value); ?></a></span> <?php
		}
	?></p>
	<p><a href="share.php?room=<?php echo htmlspecialchars(urlencode($room)); ?>">share</a> <a href="print_topic.php?title=<?php echo htmlspecialchars(urlencode($room)); ?>">print</a> <?php if (getname()) { ?> <a href="flag_topic.php?room=<?php echo htmlspecialchars(urlencode($room)); ?>">flag</a> <?php } ?> <?php if (verifyAdmin() || getname() == $config->author) { ?><a href="edit_topic.php?name=<?php echo htmlspecialchars(urlencode($config->title)); ?>">edit</a><?php } ?> <a href="topic_revs.php?topic=<?php echo htmlspecialchars(urlencode($config->title)); ?>">revisions</a></p></div>
	<h3><?php echo count($msgs); ?> comment(s)</h3>
	<table class="table" width="100%"><?php 
	if (!isset($room)) die("Specify a room!");
	if (!file_exists(__DIR__ . '/../data/messages/'.cleanFilename($room) . '/msg.json')) die("Bad room");
	foreach ($msgs as $key => $value) {
		?><tr><td id="topic-message-<?php echo htmlspecialchars($key); ?>" style="vertical-align:top;" rowspan="3"><?php
		if (isset($value->reply)) {
			?><span style="color:black;background-color:#ffcccb;">@<?php echo htmlspecialchars($value->reply); ?></span><?php
		}
		echo $value->html; ?> <hr /><?php if (getname()) { ?><a href="flag_post.php?room=<?php echo htmlspecialchars(urlencode($room)); ?>&post=<?php echo htmlspecialchars(urlencode($key)); ?>">flag</a> <?php } ?></td><td style="width:0px;" id="topic-user-<?php echo htmlspecialchars($key); ?>"><?php 
			if (file_exists(__DIR__ . '/accounts/'.cleanFilename($value->author) . '/psw.txt')) {
				?><a href="account/viewuser.php?user=<?php echo htmlspecialchars(urlencode($value->author)); ?>"><img src="./data/accounts/<?php echo htmlspecialchars(cleanFilename($value->author)); ?>/avatar.png" alt="Avatar image for <?php echo htmlspecialchars($value->author); ?>" width="100"/> <br /><?php echo htmlspecialchars($value->author); ?></a><?php
			} else {
				?><span style="color:#cccccc;">&lt;user is deleted></span><?php
			}
		?></td></tr><tr><td id="topic-message-date-<?php echo htmlspecialchars($key); ?>"><?php echo friendlyDate($value->time); ?></td></tr><tr><td id="topic-attachment-<?php echo htmlspecialchars($key); ?>">Attachment:<br /><span style="text-overflow: ellipsis;"><?php 
			if (!$value->attach) {
				?>none<?php
			} else {
				if (!file_exists(__DIR__ . '/../files/uploads/'.cleanFilename($value->attach))) {
					echo "(not found): ";
					echo htmlspecialchars(substr($value->attach, 0, 30));
					$var = (strlen($value->attach) > 30) ? "" : "...";
					echo $var;
				} else {
					?><a download="" href="files/uploads/<?php echo htmlspecialchars(urlencode($value->attach)); ?>"><?php echo htmlspecialchars(substr($value->attach, 0, 30)); ?></a><?php
				}
			}
		?></span></td></tr><?php
	}
	?></table><?php 
}
if (isset($_POST['room'])) getMsg($_POST['room']);