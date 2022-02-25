<?php 
header("Content-Type: application/json");
require '../../libraries/lib.php';
$search = isset($_GET['search']) ? $_GET['search'] : null;
$dir = scandir(__DIR__ . '/../../files/uploads', SCANDIR_SORT_NONE);
$dir = array_diff($dir, array('..', '.'));
$results = array();
foreach ($dir as $file) {
	if (isset($search)) {
		if (!stripos($file, $search)) continue;
	}
	if (isset(($_GET['mime']))) {
		$mimeType = mime_content_type(__DIR__ . '/../../files/uploads/' . $file);
		if (!startsWith($mimeType, $_GET['mime'])) continue;
	}
	$fileobj = new stdClass;
	$fileobj->name = $file;
	$fileobj->mod = friendlyDate(filemtime(__DIR__ . '/../../files/uploads/' . $file));
	$fileobj->mime = mime_content_type(__DIR__ . '/../../files/uploads/' . $file);
	array_push($results, $fileobj);
}
chdir('../../files/uploads/');
usort($results, function($a, $b) {
	return filemtime($b->name) - filemtime($a->name);
});
echo json_encode($results, 128);