<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
chdir("../../");
require 'libraries/lib.php';
if (!isset($_GET['name']) && !isset($_GET['page'])) {
	echo json_encode(
		json_decode('{"status":false,"message":"Usage: name URL param for username or page URL param = int: display this page of users"}'), JSON_PRETTY_PRINT
	);
	exit(0);
}
if (isset($_GET['name'])) {
	if (!file_exists("data/accounts/" . cleanFilename($_GET['name']) . "/psw.txt")) {
		echo json_encode(
			json_decode('{"status":false,"message":"User not found"}'), JSON_PRETTY_PRINT
		);
		exit(0);
	} else {
		chdir("data/accounts/" . cleanFilename($_GET['name']));
		$response = new stdClass;
		$response->status = true;
		$response->message = "User found! See details.";
		$response->name = file_get_contents('user.txt');
		$response->details = json_decode(file_get_contents('user.json'));
		$banned = new stdClass;
		$banned->status = file_exists('ban.txt');
		if ($banned->status) $banned->reason = file_get_contents('ban.txt');
		$response->banned = $banned;
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	exit(0);
}
$pagesize = 1;
if (isset($_GET['page'])) {
	if (!is_numeric($_GET['page'])) {
		echo json_encode(json_decode('{"status":false,"message":"Bad page number"}'), JSON_PRETTY_PRINT);
		exit(0);
	}
	if ($_GET['page'] < 1) {
		echo json_encode(json_decode('{"status":false,"message":"Page number cannot be less than zero"}'), JSON_PRETTY_PRINT);
		exit(0);
	}
	if ($_GET['page'] > ceil(count(scandir("data/accounts", SCANDIR_SORT_NONE)) - 3)) {
		echo json_encode(json_decode('{"status":false,"message":"page number too large"}'), JSON_PRETTY_PRINT);
	} else {
		$pagenum = $_GET['page'];
		if ($handle = opendir('data/accounts')) {
			$a = array();
			$b = array();
			$accounts = array();
			while (false !== ($entry = readdir($handle))) {
				if (!is_dir('data/accounts/' . $entry)) continue;
				if (in_array($entry, array('.', '..'))) continue;
				chdir('data/accounts/' . $entry);
				array_push($a, "");
				echo count($a), "\n";
				if (count($a) - 1 < ($pagesize * ($pagenum - 1))) continue;
				echo "b\n";
				$response = new stdClass;
				$response->name = file_get_contents('user.txt');
				$response->details = json_decode(file_get_contents('user.json'));
				$banned = new stdClass;
				$banned->status = file_exists('ban.txt');
				if ($banned->status) $banned->reason = file_get_contents('ban.txt');
				$response->banned = $banned;
				array_push($accounts, $response);
				chdir(__DIR__ . '/../../');
				if (count($b) == $pagesize) break;
				array_push($b, "");
			}
			$b = new stdClass;
			$b->status = true;
			$b->message = 'Done.';
			$b->users = $accounts;
			echo json_encode($b, JSON_PRETTY_PRINT);
		} else {
			header("HTTP/1.1 500 Internal Server Error");
			echo json_encode(json_decode('{"status":false,"message":"Internal server error"}'), JSON_PRETTY_PRINT);
		}
	}
}