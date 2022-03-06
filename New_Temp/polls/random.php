<?php 
$polls = json_decode(file_get_contents('polls.json'), true);
if ((count($polls, COUNT_RECURSIVE) - count($polls)) === 0) {
	header("Location: ../polls");
}
$pollusers = array_keys($polls);
shuffle($pollusers);
$user = array_rand($pollusers);
$user = $pollusers[$user];
$polls[$user] = array_keys($polls[$user]);
$ids = $polls[$user];
shuffle($ids);
$id = array_rand($ids);
$id = $ids[$id];
header("Location: poll.php?id=" . urlencode($id) . "&user=" . urlencode($user));
?>