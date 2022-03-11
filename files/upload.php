<h2>File Upload</h2>
<style>
summary {
	list-style: none;
	display: inline-block;
}
label:not(.fileupload) {
	border: 1px solid;
	display: inline-block;
	width: 100%;
}
h4 {
	display: block;
}
input[type=radio]:checked + label {
	background-color: blue;
	font-style: italic;
}
input[type=radio] + label:hover {
	background-color: skyblue;
	color: black;
}
input[type=radio] {
	display: none;
}
</style>
<?php
require '../libraries/getlicenses.php';
$licenses = getlicenses();
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
		$fileDetails = file_exists("../file_details.json") ? file_get_contents("../file_details.json") : "{}";
		$fileDetails = json_decode($fileDetails);
		$information = new stdClass;
		$information->uploader = getname();
		$information->license = $_POST['license'];
		$information->extendedLicense = $licenses[$_POST['license']];
		$information->details = $_POST['details'];
		$fileDetails->{cleanFilename(basename($_FILES['image']['name']))} = $information;
		fwrite(fopen("../file_details.json", "w+"), json_encode($fileDetails));
		echo "File is valid, and was successfully uploaded. <ul><li>URL: http://";
		echo $_SERVER['HTTP_HOST'];
		echo '/files/download.php?filename=';
		echo htmlspecialchars(urlencode($name));
		echo "</li><li>File name (for chat): ".htmlspecialchars($name).'</li></ul>';
		?><p>You can <a href="viewfile.php?filename=<?php echo htmlspecialchars(urlencode($name)); ?>">view the file online</a>, or upload another file below.</p>
<hr /><?php
	} else {
		echo "<strong>Your file could not be uploaded!</strong> This might be because of your file being too large (see max size below), or because the destination filename is taken. Please try again with a smaller file and a different name. Thanks!\n";
	}

	print "</pre>";
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<h3>Choose the file to upload</h3>
<label for="image" class="fileupload">Text file or image: </label>
<input type="file" accept="text/*, image/*" required="required" id="image" name="image" />
<div>
<h3>Fill in details</h3>
<p>If the form refuses to submit, make sure that these fields are filled out.</p>
<details>
<summary>1. Choose/change licensing information (click to open selection box)</summary>
<div class="box">
<?php 
foreach ($licenses as $name => $details) {
	?>
	<input required="required" type="radio"
	name="license"
	value="<?php 
	echo htmlspecialchars($name); ?>" id="license-<?php echo htmlspecialchars($name); ?>" />
	<label for="license-<?php echo htmlspecialchars($name); ?>"><h4><?php echo htmlspecialchars($name); ?></h4>
	<?php echo htmlspecialchars($details); ?></label><?php
}
?>
</div>
</details>
<label class="fileupload">
2. Add details about the file. This can include:
<ul>
<li>Required attribution or other text required to meet licensing conditions</li>
<li>A link to the license</li>
<li>Evidence that the author of the file is true</li>
<li>A brief summary of the file</li></ul>
<textarea rows="5" cols="50" name="details" required="required"></textarea>
</label>
</div>
<input type="submit" value="Start Upload >>" />
<input type="hidden" name="upload" value="upload" />
</form>
The maximum file size defined in <code>php.ini</code> is <code><?php echo ini_get("upload_max_filesize"); ?></code>. Uploading a file larger than that will result in an error.
<p>You are promising that one of the conditions below is true:</p>
<ul>
<li>You are the copyright holder, and you agree to license the file under <a href="http://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a>. This license is not revokable.</li>
<li>You got the work from somewhere else, the file was licensed under a free content license, and you provide attribution and other requirements in the details field to comply with the license.</li>
</ul>
<p>If you do not know the license, please do not upload this file.</p>