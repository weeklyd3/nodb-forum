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
?><html>
  <head>
	<base href=".." />
    <title>Calendar</title>
	<?php
	include('../public/header.php');
	include('../styles/inject.php');
	?>
  </head>
  <body>
  <?php
  function events(array $events): void {
	  ?><ul class="nobullet calendar-list">
	  <?php foreach ($events as $event) {
		  ?><li><a href="<?php echo htmlspecialchars($event['url']); ?>"><?php echo htmlspecialchars($event['title']); ?></a> <?php
		  echo date("H:i", $event['time']); ?></li><?php
	  } 
	  ?></ul><?php
  }
  function calendar(array $options, array &$info): void {
	  $e = (array) json_decode(file_get_contents("../data/community/events/config.json"), true);
	  ?><div width="100%" class="horizontal-scroll"><table class="exempt-from-format table calendar" width="100%"><?php
	  $time = time();
	  if (!isset(
		  $options['month'],
		  $options['year']
	  )) {
		  $month = date("m", $time);
		  $year  = date("Y", $time);
	  } else {
		  $month = $options['month'];
		  $year = $options['year'];
		  if (!is_numeric($month)) {
			  $month = date("m", $time);
		  } else {
			  if (!in_array((int) $month, range(1, 12))) {
				  $month = date("m", $time);
			  }
		  }
		  $y = date("Y", $time);
		  $year = is_numeric($year) ? ($year % 1 === 0 ? $year : $y) : $y;
	  }
	  $info['month'] = $month;
	  $info['year']  = $year;
	  $daytime = strtotime("$month/01/$year 00:00");
	  $offset = date("w", $daytime);
	  $daysofm = date("t", $daytime);
	  ?><tr><?php
	  for ($j = 0; $j < 7; $j++) {
		  $k = 5 + $j;
		  ?><th><?php echo date("l", strtotime("9/$k/2021 00:00")); ?></th><?php
	  }
	  ?></tr><tr class="calendar-row"><?php
	  $day = 1;
	  for ($i = 0; $i < $offset; $i++) {
	  	?><td></td><?php
	  }
	  $counted = $offset;
	  for ($i = 0; $i < (7 - $offset); $i++) {
		  $month = $info['year'];
		  $year = $info['year']
		  ?><td valign="top"><div class="bg-skyblue black"><?php echo $day; ?></div><?php if (isset($e["$month/$day/$year"])) { events((array) $e["$month/$day/$year"]); }?></td><?php
		  $day++;
		  $counted++;
	  }
	  ?></tr><tr class="calendar-row"><?php
	  for (; $day < ($daysofm + 1); $day++) {
		  $month = $info['month'];
		  $year  = $info['year'];
		  ?><td valign="top"><div class="bg-skyblue black"><?php echo $day; ?></div><?php if (isset($e["$month/$day/$year"])) { events((array) $e["$month/$day/$year"]); }?></td><?php
		  $counted++;
		  if ($counted % 7 === 0) {
			  ?></tr><tr class="calendar-row"><?php
		  }
	  }
	  ?></tr></table></div><?php
  } 
  $monthnames = array(
	  "January",
	  "February",
	  "March",
	  "April",
	  "May",
	  "June",
	  "July",
	  "August",
	  "September",
	  "October",
	  "November",
	  "December"
  );
	$info = array();
  calendar($_GET, $info);
    ?>
	<div style="font-size:20px;">
	<a href="events/calendar.php?month=<?php 
		if ($info['month'] == 1) {
			?>12&year=<?php echo $info['year'] - 1;
		} else {
			echo $info['month'] - 1;
			?>&year=<?php echo $info['year'];
		}
	?>">Previous</a>
	<a href="events/calendar.php">Today</a>
	<a href="events/calendar.php?month=<?php 
		if ($info['month'] == 12) {
			?>1&year=<?php echo $info['year'] + 1;
		} else {
			echo $info['month'] + 1;
			?>&year=<?php echo $info['year'];
		}
	?>">Next</a>
	</div>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
  <label>Month: <select name="month">
  <?php 
	foreach ($monthnames as $month => $name) {
		?><option value="<?php echo $month + 1; ?>"<?php if ($month + 1 == $info['month']) { ?> selected="selected"<?php } ?>><?php echo $name; ?></option><?php
	}
  ?>
  </select></label>
  <label>Year: <input type="number" name="year" value="<?php echo $info['year']; ?>" /></label>
  <input type="submit" value="Display calendar" />
  </form><?php