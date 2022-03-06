<?php 
if (getname()) {
	$time = time();
	fwrite(fopen(__DIR__ . "/../data/accounts/" . cleanFilename(getname()) . "/lastactive.txt", "w+"), $time);
}