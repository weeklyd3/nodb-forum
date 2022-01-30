<?php include('header.php'); 
$config = json_decode(file_get_contents('../../config.json'));
?>
<h2>Show Banners</h2>
<?php 
	if (isset($_POST['banner'])) {
		$config->banner = $_POST['banner'];
		if ($_POST['banner'] =='') 
			unset($config->banner);
		$status = fwrite(fopen('../../config.json', 'w+'), json_encode($config));
		logmsg("setting", getname() . " changed sitewide banner", getname());
		if ($status) {
			echo 'Success!<br />'; 
		} else {
			echo 'Could not save<br />';
		}
	}
?>
You may want to link to an <a href="articles/">article</a> for more information.
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Banner (leave empty to remove): (don't make it too long)
<textarea name="banner" style="width:100%;display:block;"><?php 
	if (isset($config->banner)) {
		echo htmlspecialchars($config->banner);
	}
?></textarea>
</label>
<input type="submit" value="Save" />
</form>