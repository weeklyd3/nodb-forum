<h2>File Upload</h2>
<?php
blockCheck();
if (!isset($_COOKIE['login'])) {
	die("You must log in to upload files.");
}
if (isset($_POST['upload'])) {
	ini_set('upload_tmp_dir', __DIR__ . '/uploads/');
	$uploaddir = __DIR__ . '/uploads/';
	$name = cleanFilename(basename($_FILES['image']['name']));
	$uploadfile = $uploaddir . cleanFilename(basename($_FILES['image']['name']));

	echo '<pre>';
	if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
		echo "File is valid, and was successfully uploaded. <ul><li>URL: http://";
		echo $_SERVER['HTTP_HOST'];
		echo '/files/uploads/';
		echo htmlspecialchars($name);
		echo "</li><li>File name (for chat): ".$name.'</li></ul>';
	} else {
		echo "<strong>Your file could not be uploaded!</strong> This might be because of your file being too large (see max size below), or because the destination filename is taken. Please try again with a smaller file and a different name. Thanks!\n";
	}

	echo '<details><summary>Here is some more debugging info:</summary>';
	echo var_dump($_FILES);

	print "</details></pre>";
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<label for="image">Text file or image: </label>
<input type="file" accept="text/*, image/*" required="required" id="image" name="image" />
<input type="submit" value="Start Upload >>" />
<input type="hidden" name="upload" value="upload" />
</form>
The maximum file size defined in <code>php.ini</code> is <code><?php echo ini_get("upload_max_filesize"); ?></code>. Uploading a file larger than that will result in an error.
<p>By uploading this file, you are promising that you are the copyright holder and you agree to license the file under the <a href="http://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike 4.0 International License</a>. This allows people to re-use the file with attribution, such as a single hyperlink. <strong>Do not submit others' work without permission, and do not get angry when you see people re-using the work under the <a href="https://creativecommons.org/licenses/by-sa/4.0/#deed-conditions" target="_blank">license terms</a>!</strong> If you are unsure, please contact a lawyer.</p>