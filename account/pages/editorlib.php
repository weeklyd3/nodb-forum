<?php 
function userSubpageEditor(string $contents, bool $readonly, bool $create = false) {
	?><div><label for="contents">Subpage contents:</label><br />
		<?php if (!$readonly) { ?>
	<div style="display:inline-block;border:1px solid;">
		<div class="toolbar">
			<a href="files/" target="_blank">Upload</a>
			|
<label id="insert-menu"><span hidden="hidden">Insert:</span>
	<select id="insert">
		<option selected="selected" disabled="disabled" id="insertplaceholder">Insert...</option>
		<option id="insertimage">Insert Image</option>
		<option id="insertlink">Insert Link</option>
	</select>
</label>
			<script>document.getElementById('insert-menu').style.display = 'inline-block';</script>
			|
			<label>Edit summary: <input type="text" name="summary" <?php if ($create) { ?>disabled="disabled" value="Created page" <?php } else { ?>value="<?php if (isset($_POST['summary'])) { echo htmlspecialchars($_POST['summary']); } ?>" <?php } ?> /></label>
			|
			<label>Save page: <input type="submit" value="Save" /></label>
		</div>
<?php } ?>
		<textarea <?php if ($readonly) { ?>readonly="readonly" <?php } ?>rows="10" style="width:100%;" id="contents" name="contents"><?php echo htmlspecialchars($contents); ?></textarea>
	</div></div></form>
<div class="overlay" id="imageinsert">
	<h3>Insert Image</h3>
	<form action="javascript:;" id="imageinsertform">
		<label>Filename of uploaded image to insert:<br /> <input id="imagefilename" /></label><br />
		<label>Alt text: <input id="image-alt-text" /></label><br />
		<input type="button" class="cancelButtons" value="Cancel" />
		<input type="submit" value="Insert image" />
	</form>
</div>
<div class="overlay" id="linkinsert">
	<h3>Insert Hyperlink</h3>
	<form action="javascript:;" id="linkinsertform">
		<label>Link target: <input id="linktarget" /></label><br />
		<label>Link text: <input id="linktext" /></label><br />
		<input type="button" class="cancelButtons" value="Cancel" />
		<input type="submit" value="Insert link" />
	</form>
</div>
<script src="styles/other/picker.js"></script>
<link rel="stylesheet" href="styles/other/picker.css" />
<script>
	initFilePicker(document.getElementById('imagefilename'), 'image/');
	document.getElementById('insert').addEventListener('change', function() {
		var menu = document.getElementById('insert');
		var option = menu.options[menu.selectedIndex].text;
		console.log(option);
		menu.selectedIndex = 0;
		switch (option) {
			case 'Insert Image':
				document.getElementById('imageinsert').style.display = 'block';
				break;
			case 'Insert Link':
				document.getElementById('linkinsert').style.display = 'block';
				break;
		}
	});
	var cancelButtons = document.querySelectorAll('.cancelButtons');
	for (var s = 0; s < cancelButtons.length; s++) {
		b = cancelButtons[s];
		console.log(b);
		b.addEventListener('click', function() {
			hideAllDialogs();
		});
	}
	function hideAllDialogs() {
		var dialogs = document.querySelectorAll('.overlay');
		for (var i = 0; i < dialogs.length; i++) {
			dialogs[i].style.display = 'none';
		}
	}
	document.getElementById('imageinsertform').addEventListener('submit', function() {
		var alt = document.getElementById('image-alt-text').value;
		var filename = document.getElementById('imagefilename').value;
		var markdownCode = '[![' + alt + '](files/download.php?filename=' + encodeURIComponent(filename) + ')](viewfile.php?filename=' + encodeURIComponent(filename) + ')';
		nodbForum.insertIntoInput(document.getElementById('contents'), markdownCode);
		document.getElementById('imageinsert').style.display = 'none';
	});
	document.getElementById('linkinsertform').addEventListener('submit', function() {
		var linkText = document.getElementById('linktext').value;
		var linkTarget = document.getElementById('linktarget').value;
		var markdownCode = '[' + linkText + '](' + linkTarget + ')';
		nodbForum.insertIntoInput(document.getElementById('contents'), markdownCode);
		document.getElementById('linkinsert').style.display = 'none';
	})
</script>
	<?php
}
class subPage {
	public $type = 'page';
	public $title;
	public $contents;
	public $revisions;
	public function __construct(string $title, string $contents, $editsummary = null) {
		if (!isset($editsummary)) $editsummary = "Created page with contents starting with \"" . substr($contents, 0, 15) . "\"";
		$this->title = $title;
		$this->contents = $contents;
		$this->revisions = array(new pageRevision($contents, $editsummary));
	}
}
class pageRevision {
	public $contents;
	public $time;
	public function __construct(string $contents, string $editsummary) {
		$this->contents = $contents;
		$this->summary = $editsummary;
		$this->time = time();
	}
}
class folder {
	public function __construct(string $name) {
		$this->title = $name;
		$this->pages = array();
		$this->type = "folder";
	}
}
function checkIfPageExists(array $path, $obj) {
	$currentobj = $obj;
	foreach ($path as $index => $element) {
		if (isset($path[$index + 1])) {
			if (!isset($currentobj->folders->$element)) return false;

			$currentobj = $currentobj->folders->$element;
		} else {
			if (!isset($currentobj->files->$element)) return false;
			$currentobj = $currentobj->files->$element;
		}
	}
	return $currentobj;
}