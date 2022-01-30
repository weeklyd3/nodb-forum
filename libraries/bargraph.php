<?php
require __DIR__ . '/colorcodes.php';
function barGraph(string $caption, array $values) {
	$maxvalue = max(array_values($values));
	?>
	<!-- To create a bar graph like
		 this, follow these steps:
		 1. Include the file 
		    /libraries/bargraph.php.
		 2. Call barGraph("caption", $values);
		 
		 NOTE: "caption" is the table caption 
		       and $values is an array where 
			   string keys are the names 
			   and the numerical values 
			   are the values.
			   
		 DO NOT EVER try to create these 
		 by hand. Thanks! -->
	<table class="graph exempt-from-format" style="border:1px solid;width:100%;">
		<caption style="color:white;background-color:blue;border:1px solid;width:100%;caption-side:top;"><b><?php echo htmlspecialchars($caption); ?></b></caption>
		<tr>
			<td width="0" style="width:0px;"></td>
			<td style=" display: flex; flex-flow: row nowrap;justify-content:space-evenly;margin-left:0;">
				<span width="0"><?php echo $maxvalue/4; ?></span>
				<span width="0"><?php echo $maxvalue/2; ?></span>
				<span width="0"><?php echo $maxvalue/4 * 3; ?></span>
			</td></tr>
		<?php 
	$currentColorIndex = 0;
	$allcolors = allcolors();
	unset($allcolors['WHITE']);
	$colors = array_values($allcolors);
	// Select dark colors
	$colors = array_filter($colors, function($f) {
		return (hexdec(substr($f, 1, 6))) < (hexdec("FFFFFF")/2);
	});
	$colors = array_values($colors);
	foreach ($values as $title => $data) {
		if ($currentColorIndex === count($colors)) $currentColorIndex = 0;
	?><tr>
			<td width="0"><?php echo htmlspecialchars($title); ?> <i>(<?php echo htmlspecialchars($data); ?>)</i></td>
			<td>
				<div style="height:50px;background-color:<?php echo $colors[$currentColorIndex]; ?>;width:<?php echo round($data/$maxvalue * 100, 20); ?>%;">&nbsp;</div>
			</td>
		</tr><?php
		$currentColorIndex += 1;
		}
		?>
	</table><?php
}