<?php 
require __DIR__ . '/../../account/pages/editorlib.php';
require __DIR__ . '/../../libraries/lib.php';
if (!isset($_GET['username'], $_GET['path'])) die("Bad input");
$username = $_GET['username'];
$path = $_GET['path'];
$usernameCleaned = cleanFilename($username);
if (!file_exists(__DIR__ . '/../../data/accounts/' . $usernameCleaned . '/subpages.json')) {
	die("Bad user");
}
$subpages = unserialize(base64_decode(file_get_contents(__DIR__ . '/../../data/accounts/' . $usernameCleaned . '/subpages.json')));
$path = array_filter(explode('/', $_GET['path']), function($v) { return $v !== ""; });
$path = array_values($path);
$item = $path[count($path) - 1];
if (!checkIfPageExists($path, $subpages)) {
	die("Bad path");
}
$subpage = checkIfPageExists($path, $subpages);
$contentType  = $subpage->contentType;
switch($contentType) {
	case 'markdown':
	case 'txt':
	header('Content-Type: text/plain');
	break;
	case 'js':
	header('Content-Type: text/javascript');
	break;
	case 'css':
	header('Content-Type: text/css');  
	break;
}
$contents = $subpage->contents;
echo $contents;