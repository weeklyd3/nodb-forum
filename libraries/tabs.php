<?php
/* Function to create tabbed interfaces. */
function tabs($files) {
	/**
	* @param {array} $files - Array of files with $url => $displaytitle
	*/
	?><ul class="tabs"><?php 
	foreach ($files as $file => $title) {
		if ($_SERVER['PHP_SELF'] == $file) {
			?><li class="tab-current"><?php echo htmlspecialchars($title); ?></li><?php
		} else {
			?><li class="tab-link"><a href="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($title); ?></a></li><?php
		}
	}
}
?></ul>