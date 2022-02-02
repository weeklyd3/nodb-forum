<?php include_once('header.php');
?>
<h2>Topics</h2>
<p>
Remove them if they are being abusive or are all consisted of low quality posts.</p>
<ul>
<?php 
class Deleted {
	public function __construct(string $reason, ?string $details) {
		$this->reason = $reason;
		$this->extendedReason = $details;
		$this->time = time();
		$this->user = getname();
	}
}
foreach( $_POST as $name => $stuff ) {
	if (is_dir('../../data/messages/'.cleanFilename($stuff))) {
		$d       = new Deleted($_POST['reason'], $_POST['details']);
		$address = __DIR__ . '/../../data/messages/' .
		                     cleanFilename($stuff) . 
							 "/del.json";

		fwrite(fopen($address, 'w+'), json_encode($d));
		?><li>Room "<?php echo htmlspecialchars($stuff); ?>" was deleted.</li><?php
	}
}
?>
</ul>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Deletion reason:
<select name="reason">
<?php 
deletionReasons();
?>
</select>
<label>More details: <input type="text" name="details" /></label><br />
<input type="submit" value="Remove!" />
<?php
if ($handle = opendir('../../data/messages/')) {
    while (false !== ($entry = readdir($handle))) {
		if (is_dir('../../data/messages/'.$entry) && $entry!='.' && $entry!='..') {
			echo '<div><input type="checkbox" name="'.htmlspecialchars($entry).'" value="'.htmlspecialchars($entry).'" id="'.htmlspecialchars($entry).'" /><label for="'.htmlspecialchars($entry).'">';
			$config = file_get_contents('../../data/messages/'.$entry.'/config.json');
			$config = json_decode($config);
			echo htmlspecialchars($config->title);
			echo "\n";
			echo '</label></div>';
		}
    }
    closedir($handle);
}
?>
<input type="submit" value="Remove!" />
</form>