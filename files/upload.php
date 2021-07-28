<h2>File Upload</h2>
<?php
if (!isset($_COOKIE['login'])) {
	die("You must log in to upload files.");
}
if (isset($_POST['upload'])) {
	ini_set('upload_tmp_dir', __DIR__ . '/uploads/');
	$uploaddir = __DIR__ . '/uploads/';
	$uploadfile = $uploaddir . basename($_FILES['image']['name']);

	echo '<pre>';
	if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
		echo "File is valid, and was successfully uploaded. <ul><li>URL: http://";
		echo $_SERVER['HTTP_HOST'];
		echo '/files/uploads/';
		echo $_FILES['image']['name'];
		echo "</li><li>File name (for chat): ".$_FILES['image']['name'].'</li></ul>';
	} else {
		echo "Possible file upload attack!\n";
	}

	echo '<details><summary>Here is some more debugging info:</summary>';
	echo var_dump($_FILES);

	print "</details></pre>";
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<label for="image">Text file or image: </label>
<input type="file" style="display: none;" accept="text/*, image/*" required="required" id="image" name="image" onchange="document.getElementById('current').innerHTML = this.files[0].name + ' (' + this.files[0].size + ' bytes)'; " />
<button onclick="document.getElementById('image').click();">Choose File</button> <br /><span id="current">No file selected</span><br /><br />
<input type="reset" value="<< Cancel" onclick="window.close();" />
<input type="submit" value="Upload >>" />
<input type="hidden" name="upload" value="upload" />
</form>
