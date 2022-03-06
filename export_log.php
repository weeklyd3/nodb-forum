<?php
if (!isset($_GET['type']) || $_GET['type'] == 'text') {
header("Content-Type: text/plain");
?>
Start of application log
-------------------
<?php
$log = json_decode(file_get_contents('log.json'));
$log = array_reverse($log);
foreach ($log as $entry) {
	?>[<?php echo date("Y-m-d\TH:i:s", $entry->time); ?>] User: <?php echo $entry->user; ?>, subject: <?php echo $entry->subject; ?>, details: <?php echo $entry->details;
	echo "\n";
}
?>
-------------------
End of application log
<?php
} else if ($_GET['type'] == 'json') {
	header("Content-Type: application/json");
	readfile('log.json');
}