<?php include('header.php');
?>
<h2>Edit TOS</h2>
<?php 
	if (isset($_POST['hidden'])) {
		$handle = fopen('../../tos_raw.php', 'w+');
		$status = fwrite($handle, $_POST['tos']);
		if ($status) {
			echo 'Updated successfully<br />';
		} else {
			echo 'Could not update, your changes are below.<br />';
		}
	}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<textarea name="tos" required="required" minlength="1" style="width:100%;" rows="15"><?php 
if (!isset($_POST['hidden'])) {
	echo htmlspecialchars(file_get_contents('../../tos_raw.php'));
} else {
	echo htmlspecialchars($_POST['tos']);
}?></textarea>
<input type="hidden" name="hidden" value="change" />
<br />
<input type="submit" value="Save" />
</form>
<strong>IMPORTANT:</strong> Do the following before editing the TOS:
<ul>
	<li><a href="articles/write.php">Write an article</a> documenting the change.</li>
	<li><a href="app/tools/banner.php">Add a banner</a> informing others of the change. Link to your article.</li>
</ul>