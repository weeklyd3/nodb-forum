<?php
require __DIR__ . '/../../libraries/lib.php';
if (!isset($_FILES['image'])) die('{"status":false,"message":"No file provided"}');
if (!getname()) die('{"status":false,"message":"Login required."}');
$uploaddir = __DIR__ . '/../../files/uploads/';
$name = cleanFilename($_FILES['image']['name']);
if (isset($_POST['randomize'])) {
	$name = getname() . '-' . time() . '-' . array_rand(range(1, 2)) . '-' . $name; 
}
$uploadfile = $uploaddir . $name;
if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
	exit('{"status":true,"message":"Upload completed","name":' . json_encode($name) . '}');
} else {
    die('{"status":false,"message":"Aww dang, something went wrong."}');
}