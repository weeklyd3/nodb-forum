<?php
require 'header.php';
?>
<h2>Search Filters</h2>
<?php 
if (isset($_POST['a'])) {
	$a = isset($config->searchFilter);
	if ($a) 
		unset($config->searchFilter);
	else
		$config->searchFilter = true;

	fwrite(fopen(__DIR__ . '/../../config.json', 'w+'), json_encode($config));
}
if (!isset($config->searchFilter)) {
	?>Search filters are disabled. Click the button below to enable filters.<?php
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="submit" name="a" value="<?php echo isset($config->searchFilter) ? 'Disable' : 'Enable'; ?> search filter" />
</form>
<?php 
if (!isset($config->searchFilter)) exit(0); ?>
<h3>Filter these words</h3>
<?php 
	if (isset($_POST['filterThese'])) {
		$userwords = array_unique(
			array_filter(explode("\n", $_POST['filterThese']),
			function($a) {
				return $a !== "";
			})
		);
		$config->filterWords = $userwords;
		fwrite(fopen(__DIR__ . '/../../config.json', 'w+'), json_encode($config));
	}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<label>Words to filter, separated by newlines:<br />
	<textarea rows="<?php echo count(isset($config->filterWords) ? $config->filterWords : $words); ?>" name="filterThese" style="width:100%;"><?php 
		echo implode("\n",
			isset($config->filterWords) ? $config->filterWords : $words
		);
	?></textarea></label><br />
	<input type="submit" value="Save" />
</form>