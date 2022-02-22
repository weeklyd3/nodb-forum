<?php 
require_once __DIR__ . '/../libraries/lib.php';
function getMsg(string $room) {
	?>
	
	<style>
	dt, dd {display: inline; margin: 0;}</style><?php
	$folder = __DIR__ . '/messages/' . cleanFilename($room);
	if (!is_dir($folder) || !file_exists("$folder/config.json")) {
		?>Room is corrupted or non-existent.<?php
		return;
	}
	$configPath = "$folder/config.json";
	$msgPath = "$folder/msg.json";
	$delPath = "$folder/del.json";

	$config = json_decode(file_get_contents($configPath));
	$msg = json_decode(file_get_contents($msgPath));
	
	$deleted = false;
	if (file_exists($delPath)) {
		$deleted = true;
	}
	?>
	<div id="topic-firstpost">
	<?php 
	if ($deleted && $config->author !== getname()) {
		?><em>This topic has been deleted. Please see the deletion notice.</em><?php
	} else {
	echo $config->description_html;
	 } ?>
	</div>
	<div>
	Tagged:
	<?php
	foreach (explode(" ", $config->tags) as $tag) {
		?><span class="tag"><a href="tagged.php?tag=<?php echo htmlspecialchars(urlencode($tag)); ?>"><?php echo htmlspecialchars($tag); ?></a></span> <?php
	}
	$esname = htmlspecialchars(urlencode($config->title));
	?></div>
	<div>
	Topic options:
	<ul class="flex options">
	<li><a href="print_topic.php?title=<?php echo $esname; ?>">
	<img src="img/icons/PrintIcon.png" alt="" />
	Printable version</a></li>
	<?php 
	if (verifyAdmin() || $config->author === getname()) { ?><li><a href="edit_topic.php?name=<?php echo $esname; ?>">
	<img src="img/icons/PencilIcon.png" alt="" />
	Edit</li><?php } ?>
	<li><a href="revisions.php?topic=<?php echo $esname; ?>">
	<img src="img/icons/RevisionsIcon.png" alt="" />
	View <?php echo isset($config->revisions) ? count($config->revisions) : 1; ?> revision(s)</a></li>
	<?php 
	if (getname()) {
		?><li><a href="flag_topic.php?room=<?php echo $esname; ?>">
		<img src="img/icons/FlagIcon.png" alt="" />
		Flag topic</a></li><?php 
	} 
if (verifyAdmin() || ($config->author === getname() && count((array) $msg) === 0))	{
	?><li><a href="deletionmgrtopic.php?topic=<?php echo $esname; ?>"><img src="img/icons/XIcon.png" alt="" /> Delete/Undelete</a></li><?php
}
?>
	</ul>
	</div>
	<hr />
	<h3>
	<?php
	$msg = (array) $msg;
	echo count($msg); ?>
	comment(s)</h3>
	<ul class="semantic">
	<?php
	foreach ($msg as $id => $message) {
		$espost = htmlspecialchars(urlencode($id));
		$skip = isset($message->del) && (!verifyAdmin() && $message->author !== getname()) || ($deleted && !verifyAdmin());
		if (isset($message->del) || $deleted) {
			?><div class="border error" style="color:black;background-color:#ffdddd;">This post has been deleted by <?php userlink($message->del->user ?? ($del->user  ?? null)); ?> for the reason: <?php echo htmlspecialchars($message->del->reason ?? "automatic deletion"); ?>. More information:<pre><?php echo htmlspecialchars(isset($message->del->extendedReason) ? $message->del->extendedReason : "None available"); ?></pre></div><?php
		}
		if ($skip) continue;
	?>
	<li id="topic-message-<?php echo $espost; ?>">
	<div class="border">
	<div class="border<?php if (isset($config->accepted)) { if ($config->accepted === $id) { ?> accepted<?php } } ?>">
	<?php echo $message->html; ?>
	</div>
	<div class="smaller">
	<dl>
	<dt>Author:</dt>
	<dd><?php userlink($message->author, true); ?></dd>

	<dt>Time:</dt><dd>
	<?php echo friendlyDate($message->time); ?>
	</dd>
	</dl>
	</div>
	<div>Post options:
		<ul class="flex options">
			<?php
		if ($message->author === getname()) { ?>
			<li>
				<a href="markasanswer.php?topic=<?php echo $esname; ?>&post=<?php echo $espost; ?>">
				<img src="img/icons/CheckIcon.png" alt="" />
				Mark as answer</a>
			</li> <?php } ?>
			<?php if (getname()) { ?><li>
				<a href="flag_post.php?room=<?php echo $esname; ?>&post=<?php echo $espost; ?>">
				<img src="img/icons/FlagIcon.png" alt="" /> Flag</a>
			</li><?php } ?>
			<li><a href="revisions.php?topic=<?php echo $esname; ?>&post=<?php echo $espost; ?>"><img src="img/icons/RevisionsIcon.png" /> View <?php echo isset($message->revisions) ? count($message->revisions) : 1; ?> revision(s)</a></li>
		</ul></div>
	</li><?php
	}
	?></ul><?php
}
if (isset($_POST['room'])) {
	getMsg($_POST['room']);
}