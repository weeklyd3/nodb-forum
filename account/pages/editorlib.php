<?php 
function userSubpageEditor(string $contents, bool $readonly, bool $create = false) {
	?><div><label for="contents">Subpage contents:</label><br />
		<?php if (!$readonly) { ?>
	<div style="display:inline-block;border:1px solid;">
		<div class="toolbar">
			<a href="files/" target="_blank">Upload</a>
			|
			<label>Edit summary: <input type="text" name="summary" <?php if ($create) { ?>disabled="disabled" value="Created page" <?php } else { ?>value="<?php if (isset($_POST['summary'])) { echo htmlspecialchars($_POST['summary']); } ?>" <?php } ?> /></label>
			|
			<label>Save page: <input type="submit" value="Save" /></label>
		</div>
<?php } ?>
		<textarea <?php if ($readonly) { ?>readonly="readonly" <?php } ?>rows="7" style="width:100%;" id="contents" name="contents"><?php echo htmlspecialchars($contents); ?></textarea>
	</div></div>
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