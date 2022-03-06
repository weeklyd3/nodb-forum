<?php 
if (!isset($_GET['filename'])) {
	die("No filename specified!");
}
require_once '../libraries/lib.php';
header("Content-Type: " . mime_content_type("uploads/" . cleanFilename($_GET['filename'])));
$name = cleanFilename($_GET['filename']);
header('Content-Disposition: inline; filename="' . $name . '"');
readfile("uploads/" . cleanFilename($_GET['filename']));
?>