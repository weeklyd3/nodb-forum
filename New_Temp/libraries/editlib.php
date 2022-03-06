<?php 
class Revision {
	function __construct($text, $author, $time, $summary) {
		$this->text = $text;
		$this->author = $author;
		$this->time = $time;
		$this->summary = $summary;
		$Parsedown = new Parsedown;
		$this->html = $Parsedown->text($text);
	}
}
?>