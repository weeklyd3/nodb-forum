<?php 
require 'header.php';
if (isset($_POST['list'])) {
	$list = str_replace("\r", "", $_POST['list']);
	logmsg("ban", "User ". getname() . ' blocked IPs ' . json_encode($list), getname());
	fwrite(fopen("../../blocklist.txt", "w+"), $list);
}
$contents = file_get_contents("../../blocklist.txt");
?>
<p>WARNING: Unexpected things may happen if you block 127.0.0.1!</p>
<form action="app/tools/ipban.php" method="post">
<label>Enter IPs to block, separated by newlines:<br />
<textarea name="list"><?php echo htmlspecialchars($contents); ?></textarea>
</label>
<input type="submit" value="Save" />
</form>