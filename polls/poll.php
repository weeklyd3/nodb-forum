<?php
/*
    Forum Software
    Copyright (C) 2021 contributors

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.


    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
?><html lang="en">
  <head>
    <title>View Poll</title>
	<base href="../" />
	<?php
	include_once('../public/header.php');
	include_once('../styles/inject.php');
	require_once '../libraries/formfuncs.php';
	?></head><body><?php
	if (!isset($_GET['user'], $_GET['id'])) die("<h2>Invalid details.</h2><p>Specify user and id.</p>");
	if (!file_exists(__DIR__ . '/polls.json')) {
		die("<h2>Invalid details.</h2><p>Poll file not found.");
	}
	$j = json_decode(file_get_contents(__DIR__ . '/polls.json'), true);
	$user = $_GET['user'];
	$id = $_GET['id'];
	$name = getname();
	if (!isset($j[$user][$id])) die("<h2>Bad id</h2><p>Invalid ID</p>");
	$j = $j[$user][$id];
	?><h2><?php echo htmlspecialchars($j['title']); ?></h2><?php require 'header.php'; 
	$Parsedown = new Parsedown;
	echo $Parsedown->text($j['description']);
	?>
	<ul>
		<li><?php echo count($j['questions']); ?> question(s)</li>
		<li><?php echo isset($j['responses']) ? count($j['responses']) : 0; ?> response(s)</li>
	</ul>
	<?php if (!isset($j['responses'][getname()])) { ?><a href="polls/viewpoll.php?user=<?php echo htmlspecialchars(urlencode($user)); ?>&id=<?php echo htmlspecialchars(urlencode($id)); ?>">Take this poll</a><?php } ?>
	<h3>Results</h3>
	<?php 
	if (!isset($j['responses'])) exit("There are no responses.");
	function record($j) {
		$res = array();
		foreach ($j['responses'] as $response) {
			foreach ($response as $question => $answer) {
				if (!isset($res[$question])) $res[$question] = array();
				array_push($res[$question], $answer);
			}
		}
		?><ol><?php
		foreach ($res as $question => $answers) {
			$responses = array();
			?><li><?php 
			$Parsedown = new Parsedown;
			echo $Parsedown->text($j['questions'][$question]['description']);
			echo count($answers); ?> answer(s)
			<br />Breakdown:<ul>
			<?php 
				foreach ($answers as $answer) {
					if (!isset($responses[$answer])) {
						$responses[$answer] = array();
					}
					if (!isset($responses[$answer]['number'])) $responses[$answer]['number'] = 0;
					$responses[$answer]['number']++;
					$responses[$answer]['text'] = $answer;
				}
				foreach ($responses as $answer => $stats) {
					?><li><strong><?php echo htmlspecialchars($answer); ?></strong>: <?php echo $stats['number']; ?> response(s), <?php echo round($stats['number'] * 100/ count($answers), 2); ?>%</li><?php
				}
			?></ul>
			<table style="width:100%;" class="exempt-from-format">
			<?php 
				$graph = array();
				$max = array();
				foreach ($responses as $answer) {
					array_push($max, $answer['number']);
				}
				$m = max($max);
				foreach ($responses as $answer) {
					?><tr>
						<td style="width:120px;"><?php echo htmlspecialchars($answer['text']); ?></td>
						<td><div style="vertical-align:middle;width:<?php echo 100 * $answer['number'] / $m; ?>%;background-color:blue;color:white;padding:20 0 20;"><?php echo round(100 * $answer['number'] / count($answers), 2); ?>%</div></td>
					</tr><?php
				}
			?>
			</table></li><?php
		}
		?></ol><?php
	}
	record($j);