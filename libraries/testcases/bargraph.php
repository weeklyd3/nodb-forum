<?php 
require '../bargraph.php';
?>
<p>Random data</p>
<?php
barGraph("Testing", array("Series A" => 30, "Series B" => 30, "Series C" => 0, "Series D" => 1024, "Series E" => 10485.76));
?>
<p>Testing the rounding (1/6 = 0.166666...)</p>
<?php
barGraph("Recurring decimals", array("Foo" => 1, "Bar" => 6));
?>
<p>Made with fake data</p>
<?php
barGraph("Class grades", array("History" => 70, "Biology" => 75, "Chemistry" => 65, "Latin" => 85, "Math" => 96.75, "English" => 94, "I FAILED" => 24.1875));
?>
<p>Many numbers</p>
<?php
function choose20random($range) {
	shuffle($range);
	return $range;
}
barGraph("Many numbers", choose20random(range(1, 20)));